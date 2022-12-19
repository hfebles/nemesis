<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Accounting\AccountingEntriesController;
use App\Http\Controllers\Accounting\MovesAccountsController;
use App\Http\Controllers\Controller;
use App\Models\Conf\Bank;
use App\Models\Conf\Country\Estados;
use App\Models\Conf\Exchange;
use App\Models\Conf\Purchases\PurchaseConfig;
use App\Models\Conf\Sales\SaleOrderConfiguration;
use App\Models\Conf\Tax;
use App\Models\HumanResources\Workers;
use App\Models\Payments\Payments;
use App\Models\Payments\Surplus;
use App\Models\Products\Product;
use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseDetails;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\PurchaseOrderDetails;
use App\Models\Purchase\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:purchase-purchase-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|purchase-purchase-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|purchase-purchase-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|purchase-purchase-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $conf = [
            'title-section' => 'Compras',
            'group' => 'purchase-purchase',
            'create' => ['route' => 'purchase.create', 'name' => 'Nueva compra'],
        ];

        $data = Purchase::select('id_purchase', 'date_purchase', 'ref_name_purchase', 'total_amount_purchase', 'os.name_order_state', 's.name_supplier')
            ->join('suppliers as s', 's.id_supplier', '=', 'purchases.id_supplier', 'left outer')
            ->join('order_states as os', 'os.id_order_state', '=', 'purchases.id_order_state', 'left outer')
            ->whereEnabledPurchase(1)
            ->orderBy('date_purchase', 'DESC')
            ->orderBy('purchases.id_order_state', 'ASC')
            ->orderBy('id_purchase', 'DESC')
            ->paginate(15);

            $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Fecha', 'Pedido', 'Proveedor', 'Estado', 'Total'],
            'w_ts' => ['3', '10', '10', '43', '12', '12',],
            'td_number' => [false, false, false, false, true], 
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
            'tds' => ['date_purchase', 'ref_name_purchase', 'name_supplier', 'name_order_state', 'total_amount_purchase'],
            'edit' => false,
            'show' => true,
            'edit_modal' => false,
            'url' => "/purchase/purchase",
            'id' => 'id_purchase',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 15),
        ];
        return view('purchases.purchase.index', compact('conf', 'table'));
    }


    function getNroControl($dataConfiguration)
    {

        $facturas = Purchase::orderBy('ctrl_num', 'ASC')->get();
        $nro2 = [];

        for ($i = 0; $i < sizeof($facturas); $i++) {
            $nro2[$i] = $facturas[$i]->ctrl_num;
        }

        if (sizeof($facturas)) {
            $existe = Purchase::select('ctrl_num')->whereCtrlNum($dataConfiguration->control_number_purchase_config)->get();
            if (sizeof($existe) > 0) {
                if (sizeof(Purchase::select('ctrl_num')->whereCtrlNum($dataConfiguration->control_number_purchase_config + 1)->get())) {
                    $compare_array = range(1, max($nro2));
                    $missing_values = array_diff($compare_array, $nro2);
                    if (range(1, max($nro2)) >= $dataConfiguration->control_number_purchase_config && min($missing_values) < $dataConfiguration->control_number_purchase_config) {
                        $ctrl = $nro2[sizeof($nro2) - 1] + 1;
                    } else if (sizeof(Purchase::select('ctrl_num')->whereCtrlNum($missing_values[key($missing_values)])->get()) == 0) {
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
            'title-section' => 'Nueva compra',
            'group' => 'purchase-purchase',
            'back' => 'purchase.index',
        ];
        $dataExchange = Exchange::whereEnabledExchange(1)->where('date_exchange', '=', date('Y-m-d'))->orderBy('id_exchange', 'DESC')->get();
        $dataSupplier = Supplier::whereEnabledSupplier(1)->get();
        if (count($dataSupplier) == 0) {
            return redirect()->route('supplier.index')->with('message', 'Debe registrar un proveedor');
        }
        if (count($dataExchange) == 0) {
            return redirect()->route('exchange.index')->with('error', 'Debe registrar un tasa de cambio');
        } else {
            $dataExchange = $dataExchange[0];
        }
        $dataConfiguration = PurchaseConfig::find(1);
        if (!isset($dataConfiguration)) {return redirect()->route('purchase-config.index');}

        $ctrl = $this->getNroControl($dataConfiguration);

        $taxes = Tax::where('billable_tax', '=', 1)->get();
        $dataWorkers = Workers::select('workers.id_worker', 'workers.firts_name_worker', 'workers.last_name_worker', 'group_workers.name_group_worker')
            ->join('group_workers', 'group_workers.id_group_worker', '=', 'workers.id_group_worker')
            ->where('name_group_worker', '=', 'COMPRAS')
            ->get();
        return view('purchases.purchase.create', compact('conf', 'dataWorkers', 'dataExchange', 'dataConfiguration', 'ctrl', 'taxes'));
    }




    public function receptions(Request $request)
    {

       // return $request;
        $obj = json_decode(\DB::select("select details_reception from purchase_receptions where id_purchase = $request->id_purchase order by id_purchase_reception DESC")[0]->details_reception, true);
        if (array_sum($obj['pendiente']) > 0) {
            for ($i = 0; $i < count($obj['id_product']); $i++) {
                if ($request->id_product[$i] == $obj['id_product'][$i]) {
                    if ($request->cantidad[$i] == $obj['cantidad'][$i]) {
                        $obj['recibido'][$i] = $request->cantidad[$i];
                        $obj['pendiente'][$i] = $obj['pendiente'][$i] - $request->cantidad[$i];
                        $producto = Product::find($request->id_product[$i])->qty_product;
                        Product::whereIdProduct($request->id_product[$i])->update(['qty_product' => ($request->cantidad[$i] + $producto)]);
                        if (array_sum($obj['pendiente']) == 0) {
                            Purchase::whereIdPurchase($request->id_purchase)->update(['id_order_state' => 12]);
                            $arr = json_encode($obj, true);
                            \DB::select("UPDATE purchase_receptions SET details_reception= '$arr' WHERE id_purchase = $request->id_purchase;");
                            return back();
                        }
                    } elseif ($request->cantidad[$i] <= $obj['cantidad'][$i]) {
                        if ($request->cantidad[$i] != null) {
                            $obj['recibido'][$i] = $request->cantidad[$i];
                            $obj['pendiente'][$i] = $obj['pendiente'][$i] - $request->cantidad[$i];
                            $producto = Product::find($request->id_product[$i])->qty_product;
                            Product::whereIdProduct($request->id_product[$i])->update(['qty_product' => ($request->cantidad[$i] + $producto)]);
                            if (array_sum($obj['pendiente']) == 0) {
                                Purchase::whereIdPurchase($request->id_purchase)->update(['id_order_state' => 12]);
                                $arr = json_encode($obj, true);
                                \DB::select("UPDATE purchase_receptions SET details_reception= '$arr' WHERE id_purchase = $request->id_purchase;");
                                return back();
                            }
                        } else {
                            $obj['recibido'][$i] = 0;
                        }
                    }
                }
            }
            $arr = json_encode($obj, true);
            \DB::select("UPDATE purchase_receptions SET details_reception= '$arr' WHERE id_purchase = $request->id_purchase;");
        } else {
            Purchase::whereIdPurchase($request->id_purchase)->update(['id_order_state' => 12]);
        }
        return back();
    }


    public function validarOrden($id)
    {

        $data = PurchaseOrder::select('*', 'purchase_order_details.*')
            ->join('purchase_order_details', 'purchase_order_details.id_purchase_order', '=', 'purchase_orders.id_purchase_order')
            ->find($id);
        $dataPedido = json_decode($data->details_purchase_order_detail, true);
        for ($i = 0; $i < count($dataPedido['id_product']); $i++) {
            $dataPedido['pendiente'][$i] = $dataPedido['cantidad'][$i];
            $dataPedido['recibido'][$i] = "0";
        }
        $savePurchase = new Purchase();
        $savePurchase->id_supplier = $data->id_supplier;
        $savePurchase->id_exchange = $data->id_exchange;
        $savePurchase->ctrl_num = $data->ctrl_num;
        $savePurchase->id_worker = $data->id_worker;
        $savePurchase->id_user = $data->id_user;
        $savePurchase->total_amount_purchase = $data->total_amount_purchase_order;
        $savePurchase->exempt_amout_purchase = $data->exempt_amout_purchase_order;
        $savePurchase->no_exempt_amout_purchase = $data->no_exempt_amout_purchase_order;
        $savePurchase->total_amount_tax_purchase = $data->total_amount_tax_purchase_order;
        $savePurchase->date_purchase = $data->date_purchase_order;
        $savePurchase->id_order_state = 11;
        $savePurchase->save();
        $saveDetails = new PurchaseDetails();
        $saveDetails->id_purchase = $savePurchase->id_purchase;
        $saveDetails->details_purchase_detail = $data->details_purchase_order_detail;
        $saveDetails->save();
        $da = json_encode($dataPedido);
        PurchaseOrder::whereIdPurchaseOrder($id)->update(['id_order_state' => 9, 'id_purchase' => $savePurchase->id_purchase]);
        \DB::select("insert into purchase_receptions (id_purchase, details_reception) values($savePurchase->id_purchase, '$da')");
        return redirect()->route('purchase.show', $savePurchase->id_purchase)->with('message', 'Orden aprobada con exito');
    }


    public function store(Request $request)
    {

        //return $request;
        
        $data = $request->except('_token');
        
        $dataDetails = $request->except(
            '_token',
            'ref_name_purchase',
            'ctrl_num',
            'ctrl_num_purchase',
            'supplier_order',
            'id_supplier',
            'subFac',
            'exento',
            'total_taxes',
            'total_con_tax',
            'id_exchange',
            'date_purchase',
            'type_payment',
            'noExento',
        );


        $purchase = new Purchase();
        $purchase->ref_name_purchase = $data['ref_name_purchase'];
        $purchase->ctrl_num_purchase = $data['ctrl_num_purchase'];
        $purchase->ctrl_num = $data['ctrl_num'];
        $purchase->total_amount_purchase = $data['total_con_tax'];
        $purchase->exempt_amout_purchase = $data['exento'];
        $purchase->no_exempt_amout_purchase = $data['subFac'];
        $purchase->total_amount_tax_purchase = $data['total_taxes'];
        $purchase->residual_amount_purchase = $data['total_con_tax'];
        $purchase->date_purchase = $data['date_purchase'];
        $purchase->type_payment = $data['type_payment'];
        $purchase->id_exchange = $data['id_exchange'];
        $purchase->id_order_state = 11;
        $purchase->id_supplier = $data['id_supplier'];
        $purchase->id_user = Auth::id();
        $purchase->save();

        $saveDetails = new PurchaseDetails();
        $saveDetails->id_purchase = $purchase->id_purchase;
        $saveDetails->details_purchase_detail = json_encode($dataDetails);
        $saveDetails->save();

        

        for ($i = 0; $i < count($dataDetails['id_product']); $i++) {
            $dataDetails['pendiente'][$i] = $dataDetails['cantidad'][$i];
            $dataDetails['recibido'][$i] = "0";
        }

        PurchaseConfig::find(1)->update(['control_number_purchase_config' => $data['ctrl_num']]);
        $da = json_encode($dataDetails);

        \DB::select("insert into purchase_receptions (id_purchase, details_reception) values($purchase->id_purchase, '$da')");

        $move = (new MovesAccountsController)->createMovesPurchase($purchase->id_purchase, $purchase->date_purchase, 2);
        (new AccountingEntriesController)->saveEntriesPurchase($move, $purchase->id_purchase);
        //return $result;
        return redirect()->route('purchase.show', $purchase->id_purchase)->with('message', 'Se registro la orden con éxito');
    }

    public function show($id)
    {
        $data = Purchase::select('purchases.*',  's.address_supplier', 's.phone_supplier', 's.idcard_supplier', 's.name_supplier', 'w.firts_name_worker', 'w.last_name_worker', 'e.amount_exchange', 'e.date_exchange')
            ->join('suppliers AS s', 's.id_supplier', '=', 'purchases.id_supplier')
            ->join('exchanges AS e', 'e.id_exchange', '=', 'purchases.id_exchange')
            ->join('workers AS w', 'w.id_worker', '=', 'purchases.id_worker', 'left outer')
            ->find($id);

        //return $data;
        $dataProdcs = json_decode(\DB::select("SELECT * FROM purchase_receptions where id_purchase = $id order by id_purchase DESC")[0]->details_reception, true);
        $obj = json_decode(PurchaseDetails::whereIdPurchase($id)->get()[0]->details_purchase_detail, true);
        for ($i = 0; $i < count($dataProdcs['id_product']); $i++) {
            $dataProducts[$i] = Product::select('products.*', 'p.name_presentation_product', 'u.name_unit_product', 'u.short_unit_product')
                ->join('presentation_products AS p', 'p.id_presentation_product', '=', 'products.id_presentation_product')
                ->join('unit_products AS u', 'u.id_unit_product', '=', 'products.id_unit_product')
                ->whereIdProduct($obj['id_product'][$i])
                ->get();
        }

        $dataBanks = Bank::whereEnabledBank(1)->pluck('name_bank', 'id_bank');

        $payments = Payments::select('payments.*', 'name_bank')
            ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
            ->whereIdPurchase($id)
            ->whereTypePay(1)
            ->get();

        $surplus = Surplus::select('amount_surplus', 'payments.id_payment', 'payments.ref_payment', 'payments.date_payment')
            ->join('payments', 'payments.id_payment', '=', 'surpluses.id_payment')
            ->where('surpluses.id_client', '=', $data->id_supplier)
            ->where('used_surplus', '=', 1)
            ->get();

            
        $conf = [
            'title-section' => 'Compra: ' . $data->ref_name_purchase,
            'group' => 'purchase',
            'back' => 'purchase.index',
            'edit' => ['route' => 'purchase.edit', 'id' => $id],
        ];
        return view('purchases.purchase.show', compact('conf', 'data', 'dataProducts', 'obj', 'dataProdcs', 'dataBanks', 'surplus', 'payments'));
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
            $message = [
                'type' => 'danger',
                'message' => 'No puede editar la orden si ya fue facturada.',
            ];
            return redirect()->route('purchase.show', $data->id_sales_order)->with('message', $message);
        } elseif ($data->id_order_state == 3) {
            $message = [
                'type' => 'danger',
                'message' => 'No puede editar la orden si ya fue cancelada.',
            ];
            return redirect()->route('purchase.show', $data->id_sales_order)->with('message', $message);
        } else {

            $conf = [
                'title-section' => 'Pedido: ',
                'group' => 'purchase',
                'back' => 'purchase.index',
                'edit' => ['route' => 'purchase.edit', 'id' => $id],
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
            return view('purchases.purchase.edit', compact('conf', 'data', 'dataProducts', 'obj', 'taxes', 'dataExchange'));
        }
    }


    public function update(Request $request, $id)
    {



        $data = $request->except('_token', '_method');

        $obj = json_decode(PurchaseOrderDetails::whereIdPurchaseOrder($id)->get()[0]->details_purchase_order_detail, true);
        // return $request;
        for ($i = 0; $i < count($obj['id_product']); $i++) {
            $sumar =  Product::select('qty_product')->whereIdProduct($obj['id_product'][$i])->get()[0];
            $operacion = $sumar->qty_product + $obj['cantidad'][$i];
            Product::whereIdProduct($obj['id_product'][$i])->update(['qty_product' => $operacion]);
        }

        $dataDetails = $request->except('_token', 'id_supplier', 'type_payment_purchase_order', 'subFac', 'exento', 'total_taxes', 'total_con_tax', 'noExento', 'subtotal', 'exempt_product', 'subtotal_exento', 'id_worker', 'id_exchange', 'ref_name_sales_order', 'ctrl_num');

        PurchaseOrder::whereIdPurchaseOrder($id)->update([
            'type_payment' => $data['type_payment_purchase_order'],
            'id_supplier' => $data['id_supplier'],
            'id_exchange' => $data['id_exchange'],
            'id_user' => Auth::id(),
            'total_amount_purchase_order' => $data['total_con_tax'],
            'exempt_amout_purchase_order' => $data['exento'],
            'no_exempt_amout_purchase_order' => $data['subFac'],
            'total_amount_tax_purchase_order' => $data['subFac'],
        ]);


        PurchaseOrderDetails::whereIdPurchaseOrder($id)->update(['details_purchase_order_detail' => json_encode($dataDetails)]);

        for ($i = 0; $i < count($data['id_product']); $i++) {
            $restar =  Product::select('qty_product')->whereIdProduct($data['id_product'][$i])->get();
            $operacion = $restar[0]->qty_product - $data['cantidad'][$i];
            Product::whereIdProduct($data['id_product'][$i])->update(['qty_product' => $operacion]);
        }


        $message = [
            'type' => 'warning',
            'message' => 'Se actualizo el pedido con éxito',
        ];

        return redirect()->route('purchase.index')->with('message', $message);
    }


    public function anular($id)
    {
        Purchase::whereIdPurchase($id)->update(['id_order_state' => 10]);
        PurchaseOrder::whereIdPurchase($id)->update(['id_order_state' => 10]);
        return redirect()->route('purchase.show', $id);
    }





    public function listar(Request $request)
    {

        if ($request->texto == 'proveedor') {
            if (isset($request->param)) {
                $dataProveedor =  \DB::select("SELECT * 
                                                FROM suppliers 
                                                WHERE name_supplier LIKE '%" . $request->param . "%' 
                                                OR idcard_supplier LIKE '%" . $request->param . "%'");
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



        if ($actual[0]->tax_exempt_product == 1) {
            return response()->json(['respuesta' => true, 'exento' => true]);
        } else {
            return response()->json(['respuesta' => true, 'exento' => false]);
        }
    }

    public function getDataPurchase($id)
    {
        return Purchase::find($id);
    }
}
