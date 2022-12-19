<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Conf\Exchange;
use App\Models\Conf\Purchases\PurchaseOrderConfig;
use App\Models\Conf\Sales\SaleOrderConfiguration;
use App\Models\Conf\Tax;
use App\Models\Products\Product;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\PurchaseOrderDetails;
use App\Models\Purchase\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:purchase-order-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|purchase-order-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|purchase-order-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|purchase-order-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $conf = [
            'title-section' => 'Ordenes de compra',
            'group' => 'purchase-order',
            'create' => ['route' => 'purchase-order.create', 'name' => 'Nuevo pedido'],
        ];

        $data = PurchaseOrder::select('id_purchase_order', 'date_purchase_order', 'ref_name_purchase_order', 'total_amount_purchase_order', 'os.name_order_state', 's.name_supplier')
            ->join('suppliers as s', 's.id_supplier', '=', 'purchase_orders.id_supplier', 'left outer')
            ->join('order_states as os', 'os.id_order_state', '=', 'purchase_orders.id_order_state', 'left outer')
            ->whereEnabledPurchaseOrder(1)
            ->orderBy('date_purchase_order', 'DESC')
            ->orderBy('purchase_orders.id_order_state', 'ASC')
            ->orderBy('id_purchase_order', 'DESC')
            ->paginate(15);

        // return $data;


        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Fecha', 'Pedido', 'Proveedor', 'Estado', 'Total'],
            'w_ts' => ['3', '10', '10', '43', '12', '12',],
            'c_ths' =>
            [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
            ],
            'tds' => ['date_purchase_order', 'ref_name_purchase_order', 'name_supplier', 'name_order_state', 'total_amount_purchase_order'],
            'edit' => false,
            'show' => true,
            'edit_modal' => false,
            'url' => "/purchase/purchase-order",
            'id' => 'id_purchase_order',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 15),
        ];
        return view('purchases.purchase-order.index', compact('conf', 'table'));
    }

    function getNroControl($dataConfiguration)
    {

        $facturas = PurchaseOrder::orderBy('ctrl_num', 'ASC')->get();
        $nro2 = [];

        for ($i = 0; $i < sizeof($facturas); $i++) {
            $nro2[$i] = $facturas[$i]->ctrl_num;
        }

        if (sizeof($facturas)) {
            $existe = PurchaseOrder::select('ctrl_num')->whereCtrlNum($dataConfiguration->control_number_purchase_config)->get();
            if (sizeof($existe) > 0) {
                if (sizeof(PurchaseOrder::select('ctrl_num')->whereCtrlNum($dataConfiguration->control_number_purchase_config + 1)->get())) {
                    $compare_array = range(1, max($nro2));
                    $missing_values = array_diff($compare_array, $nro2);
                    if (range(1, max($nro2)) >= $dataConfiguration->control_number_purchase_config && min($missing_values) < $dataConfiguration->control_number_purchase_config) {
                        $ctrl = $nro2[sizeof($nro2) - 1] + 1;
                    } else if (sizeof(PurchaseOrder::select('ctrl_num')->whereCtrlNum($missing_values[key($missing_values)])->get()) == 0) {
                        $ctrl = $missing_values[key($missing_values)];
                    }
                } else {
                    $ctrl = $dataConfiguration->control_number_purchase_config + 1;
                }
            } else {
                $ctrl = $dataConfiguration->control_number_purchase_config;
            }
        } else {
            $ctrl = $dataConfiguration->control_number_purchase_config;
        }

        return $ctrl;
    }


    public function create()
    {

        $conf = [
            'title-section' => 'Nueva orden de compra',
            'group' => 'purchase-order',
            'back' => 'purchase-order.index',
        ];

        $dataExchange = Exchange::whereEnabledExchange(1)->where('date_exchange', '=', date('Y-m-d'))->orderBy('id_exchange', 'DESC')->get();
        $dataSupplier = Supplier::whereEnabledSupplier(1)->get();

        if (count($dataSupplier) == 0) {
            return redirect()->route('supplier.index')->with('error', 'Debe registrar un proveedor');
        }

        if (count($dataExchange) == 0) {
            return redirect()->route('exchange.index')->with('error', 'Debe registrar un tasa de cambio');
        } else {
            $dataExchange = $dataExchange[0];
        }

        $dataConfiguration = PurchaseOrderConfig::find(1);
        if (!isset($dataConfiguration)) {return redirect()->route('order-config.index');}

        $ctrl = $this->getNroControl($dataConfiguration);

        $taxes = Tax::where('billable_tax', '=', 1)->get();
        $dataWorkers = \DB::select("SELECT workers.id_worker, workers.firts_name_worker, workers.last_name_worker, group_workers.name_group_worker
                                    FROM workers
                                    INNER JOIN group_workers ON group_workers.id_group_worker = workers.id_group_worker
                                    WHERE name_group_worker = 'COMPRAS'");

                                    $config = 1;

        return view('purchases.purchase-order.create', compact('conf', 'dataWorkers', 'dataExchange', 'dataConfiguration', 'config', 'taxes'));
    }


    public function store(Request $request)
    {

        return $request;

        $dataConfiguration = SaleOrderConfiguration::all()[0];
        $dataSalesOrder = $request->except('_token');
        $dataDetails = $request->except(
            '_token',
            'id_supplier',
            'type_payment_purchase_order',
            'subFac',
            'exento',
            'total_taxes',
            'total_con_tax',
            'noExento',
            'subtotal',
            'exempt_product',
            'subtotal_exento',
            'id_worker',
            'id_exchange',
            'ref_name_purchase_order',
            'ctrl_num',
            'supplier_order'
        );

        //  return $dataDetails;

        $saveSalesOrder = new PurchaseOrder();

        //$saveSalesOrder->type_payment = $dataSalesOrder['type_payment_purchase_order'];
        $saveSalesOrder->id_supplier = $dataSalesOrder['id_supplier'];
        $saveSalesOrder->id_exchange = $dataSalesOrder['id_exchange'];
        $saveSalesOrder->ctrl_num = $dataSalesOrder['ctrl_num'];

        $saveSalesOrder->ref_name_purchase_order = $dataConfiguration->correlative_sale_order_configuration . '-' . str_pad($dataSalesOrder['ctrl_num'], 6, "0", STR_PAD_LEFT);

        if (isset($dataSalesOrder['id_worker'])) {
            $saveSalesOrder->id_worker = $dataSalesOrder['id_worker'];
        }

        $saveSalesOrder->id_user = Auth::id();
        $saveSalesOrder->total_amount_purchase_order = $dataSalesOrder['total_con_tax'];
        $saveSalesOrder->exempt_amout_purchase_order = $dataSalesOrder['exento'];
        $saveSalesOrder->no_exempt_amout_purchase_order = $dataSalesOrder['subFac'];
        $saveSalesOrder->total_amount_tax_purchase_order = $dataSalesOrder['total_taxes'];
        $saveSalesOrder->date_purchase_order = date('Y-m-d');
        $saveSalesOrder->id_order_state = 8;
        $saveSalesOrder->save();

        $saveDetails = new PurchaseOrderDetails();
        $saveDetails->id_purchase_order = $saveSalesOrder->id_purchase_order;
        $saveDetails->details_purchase_order_detail = json_encode($dataDetails);
        $saveDetails->save();

        PurchaseOrderConfig::find(1)->update(['control_number_purchase_config' => $data['ctrl_num']]);


        return redirect()->route('purchase-order.show', $saveSalesOrder->id_purchase_order)->with('message', 'Se registro la orden con éxito');
    }




    public function show($id)
    {

        $data = PurchaseOrder::select('purchase_orders.*', 's.address_supplier', 's.phone_supplier', 's.idcard_supplier', 's.name_supplier', 'w.firts_name_worker', 'w.last_name_worker', 'e.amount_exchange', 'e.date_exchange')
            ->join('suppliers AS s', 's.id_supplier', '=', 'purchase_orders.id_supplier')
            ->join('exchanges AS e', 'e.id_exchange', '=', 'purchase_orders.id_exchange')
            ->join('workers AS w', 'w.id_worker', '=', 'purchase_orders.id_worker', 'left outer')
            ->where('id_purchase_order', '=', $id)
            ->get()[0];

        $obj = json_decode(PurchaseOrderDetails::whereIdPurchaseOrder($id)->get()[0]->details_purchase_order_detail, true);

        for ($i = 0; $i < count($obj['id_product']); $i++) {
            $dataProducts[$i] = Product::select('products.*', 'p.name_presentation_product', 'u.name_unit_product', 'u.short_unit_product')
                ->join('presentation_products AS p', 'p.id_presentation_product', '=', 'products.id_presentation_product')
                ->join('unit_products AS u', 'u.id_unit_product', '=', 'products.id_unit_product')
                ->whereIdProduct($obj['id_product'][$i])
                ->get();
        }


        $conf = [
            'title-section' => 'Orden de compra: ' . $data->ref_name_purchase_order,
            'group' => 'purchase-order',
            'back' => 'purchase-order.index',
            'edit' => ['route' => 'purchase-order.edit', 'id' => $id],
        ];

        return view('purchases.purchase-order.show', compact('conf', 'data', 'dataProducts', 'obj'));
    }



    public function edit($id)
    {

        $data = PurchaseOrder::select('purchase_orders.*', 's.address_supplier', 's.phone_supplier', 's.idcard_supplier', 's.name_supplier', 'w.firts_name_worker', 'w.last_name_worker', 'e.amount_exchange', 'e.date_exchange')
            ->join('suppliers AS s', 's.id_supplier', '=', 'purchase_orders.id_supplier')
            ->join('exchanges AS e', 'e.id_exchange', '=', 'purchase_orders.id_exchange')
            ->join('workers AS w', 'w.id_worker', '=', 'purchase_orders.id_worker', 'left outer')
            ->where('id_purchase_order', '=', $id)
            ->get()[0];


        if ($data->id_order_state == 2) {
            return redirect()->route('purchase-order.show', $data->id_sales_order)->with('error', 'No puede editar la orden si ya fue facturada.');
        } elseif ($data->id_order_state == 3) {
            return redirect()->route('purchase-order.show', $data->id_sales_order)->with('error', 'No puede editar la orden si ya fue cancelada.');
        } else {

            $conf = [
                'title-section' => 'Pedido: ',
                'group' => 'purchase-order',
                'back' => 'purchase-order.index',
                'edit' => ['route' => 'purchase-order.edit', 'id' => $id],
            ];

            $taxes = Tax::where('billable_tax', '=', 1)->get();
            $dataExchange = Exchange::whereEnabledExchange(1)->where('date_exchange', '=', date('Y-m-d'))->orderBy('id_exchange', 'DESC')->get()[0];
            $obj = json_decode(PurchaseOrderDetails::whereIdPurchaseOrder($id)->get()[0]->details_purchase_order_detail, true);

            for ($i = 0; $i < count($obj['id_product']); $i++) {
                $dataProducts[$i] = Product::select('products.*', 'p.name_presentation_product', 'u.name_unit_product', 'u.short_unit_product')
                    ->join('presentation_products AS p', 'p.id_presentation_product', '=', 'products.id_presentation_product')
                    ->join('unit_products AS u', 'u.id_unit_product', '=', 'products.id_unit_product')
                    ->whereIdProduct($obj['id_product'][$i])
                    ->get();
            }



            return view('purchases.purchase-order.edit', compact('conf', 'data', 'dataProducts', 'obj', 'taxes', 'dataExchange'));
        }
    }


    public function update(Request $request, $id)
    {
        $data = $request->except('_token', '_method');
        $obj = json_decode(PurchaseOrderDetails::whereIdPurchaseOrder($id)->get()[0]->details_purchase_order_detail, true);
        for ($i = 0; $i < count($obj['id_product']); $i++) {
            $sumar =  Product::select('qty_product')->whereIdProduct($obj['id_product'][$i])->get()[0];
            $operacion = $sumar->qty_product + $obj['cantidad'][$i];
            Product::whereIdProduct($obj['id_product'][$i])->update(['qty_product' => $operacion]);
        }
        $dataDetails = $request->except('_token', 'id_supplier', 'type_payment_purchase_order', 'subFac', 'exento', 'total_taxes', 'total_con_tax', 'noExento', 'subtotal', 'exempt_product', 'subtotal_exento', 'id_worker', 'id_exchange', 'ref_name_sales_order', 'ctrl_num');
        PurchaseOrder::whereIdPurchaseOrder($id)->update([
            'id_supplier' => $data['id_supplier'],
            'id_exchange' => $data['id_exchange'],
            'id_user' => Auth::id(),
            'total_amount_purchase_order' => $data['total_con_tax'],
            'exempt_amout_purchase_order' => $data['exento'],
            'no_exempt_amout_purchase_order' => $data['subFac'],
            'total_amount_tax_purchase_order' => $data['total_taxes'],
        ]);
        PurchaseOrderDetails::whereIdPurchaseOrder($id)->update(['details_purchase_order_detail' => json_encode($dataDetails)]);
        for ($i = 0; $i < count($data['id_product']); $i++) {
            $restar =  Product::select('qty_product')->whereIdProduct($data['id_product'][$i])->get();
            $operacion = $restar[0]->qty_product - $data['cantidad'][$i];
            Product::whereIdProduct($data['id_product'][$i])->update(['qty_product' => $operacion]);
        }
        return redirect()->route('purchase-order.index')->with('message', 'Se actualizo el pedido con éxito');
    }


    public function anular($id)
    {
        PurchaseOrder::whereIdPurchaseOrder($id)->update(['id_order_state' => 10]);
        return redirect()->route('purchase.show', $id);
    }





    public function listar(Request $request)
    {

        if ($request->texto == 'proveedor') {
            if (isset($request->param)) {
                $dataProveedor = Supplier::where('name_supplier', 'like', "%$request->param%")
                    ->orWhere('idcard_supplier', 'like', "%$request->param%")->get();

                return response()->json(
                    [
                        'lista' => $dataProveedor,
                        'th' => ['Cedula', 'Nombre o Razon social'],
                        'success' => true,
                        'title' => 'Lista de proveedores'
                    ]
                );
            }
            $dataProveedor = Supplier::whereEnabledSupplier(1)->get();


            return response()->json(
                [
                    'lista' => $dataProveedor,
                    'th' => ['Cedula', 'Nombre o Razon social'],
                    'success' => true,
                    'title' => 'Lista de Proveedores'
                ]
            );
        } else {
            if (is_int($request->param) == true) {
                $request->param = "";
            }
            if ($request->param != "") {
                $dataProductos =  \DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                                                FROM products 
                                                INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                                                INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                                                INNER JOIN warehouses AS w ON w.id_warehouse = products.id_warehouse
                                                WHERE qty_product > 0 
                                                AND name_product LIKE '%" . $request->param . "%' 
                                                OR code_product LIKE '%" . $request->param . "%'
                                                ORDER BY products.name_product ASC");


                return response()->json(
                    [

                        'lista' => $dataProductos,
                        'th' => ['Codigo', 'Descripcion', 'Unidad', 'Presentacion', 'Cantidad', 'Precio', 'Ref $'],
                        'success' => true,
                        'title' => 'Lista de Productos'

                    ]
                );
            } else {

                $dataProductos =  \DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                                                FROM products 
                                                INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                                                INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                                                INNER JOIN warehouses AS w ON w.id_warehouse = products.id_warehouse
                                                WHERE qty_product > 0 
                                                ORDER BY products.name_product ASC");

                return response()->json(
                    [

                        'lista' => $dataProductos,
                        'th' => ['Codigo', 'Descripcion', 'Unidad', 'Presentacion', 'Cantidad', 'Precio', 'Ref $'],
                        'success' => true,
                        'title' => 'Lista de Productos'

                    ]
                );
            }
        }
    }


    public function disponible(Request $request)
    {
        $data = $request;

        $actual = Product::select('qty_product', 'tax_exempt_product', 'product_usd_product')->whereIdProduct($data['producto'])->get();


        if ($data['cantidad'] <= $actual[0]->qty_product) {
            if ($actual[0]->tax_exempt_product == 1) {
                return response()->json(['respuesta' => true, 'exento' => true]);
            } else {
                return response()->json(['respuesta' => true, 'exento' => false]);
            }
        } else {
            return response()->json(['respuesta' => false, 'cantid' => $actual[0]->qty_product]);
        }
    }
}
