<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sales\SalesOrder;
use App\Models\Sales\SalesOrderDetails;

use App\Models\Products\Product;
use App\Models\Sales\Client;

use Illuminate\Support\Facades\Auth;

use App\Models\HumanResources\Workers;
use App\Models\Conf\Exchange;

use App\Models\Conf\Sales\SaleOrderConfiguration;
use App\Models\Conf\Tax;
use App\Models\Payments\Payments;
use App\Models\Sales\DeliveryNotes;
use App\Models\Sales\DeliveryNotesDetails;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sales-order-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|sales-order-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|sales-order-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|sales-order-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $conf = [
            'title-section' => 'Pedidos de venta',
            'group' => 'sales-order',
            'create' => ['route' => 'sales-order.create', 'name' => 'Nuevo pedido'],
        ];

        $data = SalesOrder::select('id_sales_order', 'date_sales_order', 'ref_name_sales_order', 'name_client', 'total_amount_sales_order', 'os.name_order_state', 'c.name_client')
            ->join('clients as c', 'c.id_client', '=', 'sales_orders.id_client', 'left outer')
            ->join('order_states as os', 'os.id_order_state', '=', 'sales_orders.id_order_state', 'left outer')
            ->whereEnabledSalesOrder(1)

            ->orderBy('date_sales_order', 'DESC')
            ->orderBy('sales_orders.id_order_state', 'ASC')
            ->orderBy('id_sales_order', 'DESC')



            ->paginate(15);

        // return $data;


        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Fecha', 'Pedido', 'Cliente', 'Estado', 'Total'],
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
            'tds' => ['date_sales_order', 'ref_name_sales_order', 'name_client', 'name_order_state', 'total_amount_sales_order'],
            'edit' => false,
            'show' => true,
            'edit_modal' => false,
            'url' => "/sales/sales-order",
            'id' => 'id_sales_order',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 5),
        ];
        return view('sales.sales-order.index', compact('conf', 'table'));
    }

    public function create()
    {



        $conf = [
            'title-section' => 'Nuevo pedido',
            'group' => 'sales-order',
            'back' => 'sales-order.index',
        ];

        $dataExchange = Exchange::whereEnabledExchange(1)->where('date_exchange', '=', date('Y-m-d'))->orderBy('id_exchange', 'DESC')->get();

        $dataUsers = Client::whereEnabledClient(1)->get();

        if (count($dataUsers) == 0) {

            $message = [
                'type' => 'warning',
                'message' => 'Debe registrar un cliente',
            ];

            return redirect()->route('clients.index')->with('message', $message);
        }

        //return $dataExchange;
        if (count($dataExchange) == 0) {

            $message = [
                'type' => 'warning',
                'message' => 'Debe registrar un tasa de cambio',
            ];

            return redirect()->route('exchange.index')->with('message', $message);
        } else {
            $dataExchange = $dataExchange[0];
        }

        $dataConfiguration = SaleOrderConfiguration::all();
        if (count($dataConfiguration) == 0) {
            return redirect()->route('order-config.index')->with('success', 'Debe registrat una tasa');
        } else {
            $dataConfiguration = $dataConfiguration[0];
            $config = $dataConfiguration->control_number_sale_order_configuration;
        }

        $datax = SalesOrder::whereEnabledSalesOrder(1)->orderBy('id_sales_order', 'DESC')->get();

        if (count($datax) > 0) {

            if ($config == $datax[0]->ctrl_num) {

                $config = $datax[0]->ctrl_num + 1;
            }
            $config = $datax[0]->ctrl_num + 1;
            // return $config;
        }

        $taxes = Tax::where('billable_tax', '=', 1)->get();

        $dataWorkers = DB::select("SELECT workers.id_worker, workers.firts_name_worker, workers.last_name_worker, group_workers.name_group_worker
                                    FROM workers
                                    INNER JOIN group_workers ON group_workers.id_group_worker = workers.id_group_worker
                                    WHERE name_group_worker = 'VENDEDORES'");

        return view('sales.sales-order.create', compact('conf', 'dataWorkers', 'dataExchange', 'dataConfiguration', 'config', 'taxes'));
    }


    public function store(Request $request)
    {

        $dataConfiguration = SaleOrderConfiguration::all()[0];
        $dataSalesOrder = $request->except('_token');
        $dataDetails = $request->except(
            '_token',
            'id_client',
            'type_payment_sales_order',
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
            'ref_name_sales_order',
            'ctrl_num'
        );



        $saveSalesOrder = new SalesOrder();

        $saveSalesOrder->type_payment = $dataSalesOrder['type_payment_sales_order'];
        $saveSalesOrder->id_client = $dataSalesOrder['id_client'];
        $saveSalesOrder->id_exchange = $dataSalesOrder['id_exchange'];
        $saveSalesOrder->ctrl_num = $dataSalesOrder['ctrl_num'];

        $saveSalesOrder->ref_name_sales_order = $dataConfiguration->correlative_sale_order_configuration . '-' . str_pad($dataSalesOrder['ctrl_num'], 6, "0", STR_PAD_LEFT);

        if (isset($dataSalesOrder['id_worker'])) {
            $saveSalesOrder->id_worker = $dataSalesOrder['id_worker'];
        }

        $saveSalesOrder->id_user = Auth::id();
        $saveSalesOrder->total_amount_sales_order = $dataSalesOrder['total_con_tax'];
        $saveSalesOrder->exempt_amout_sales_order = $dataSalesOrder['exento'];
        $saveSalesOrder->no_exempt_amout_sales_order = $dataSalesOrder['subFac'];
        $saveSalesOrder->total_amount_tax_sales_order = $dataSalesOrder['total_taxes'];
        $saveSalesOrder->date_sales_order = date('Y-m-d');
        $saveSalesOrder->save();



        $saveDetails = new salesOrderDetails();
        $saveDetails->id_sales_order = $saveSalesOrder->id_sales_order;
        $saveDetails->details_order_detail = json_encode($dataDetails);
        $saveDetails->save();




        for ($i = 0; $i < count($dataSalesOrder['id_product']); $i++) {
            $restar =  Product::select('qty_product')->whereIdProduct($dataSalesOrder['id_product'][$i])->get();
            $operacion = $restar[0]->qty_product - $dataSalesOrder['cantidad'][$i];

            Product::whereIdProduct($dataSalesOrder['id_product'][$i])->update(['qty_product' => $operacion]);
        }


        $message = [
            'type' => 'success',
            'message' => 'Se registro el pedido con éxito',
        ];

        return redirect()->route('sales-order.show', $saveSalesOrder->id_sales_order)->with('message', $message);
    }

    public function show($id)
    {



        $data =  DB::select("SELECT so.*, c.address_client, c.phone_client, c.idcard_client, c.name_client, w.firts_name_worker, w.last_name_worker, e.amount_exchange, e.date_exchange
                                FROM sales_orders as so
                                INNER JOIN clients AS c ON c.id_client = so.id_client
                                INNER JOIN exchanges AS e ON e.id_exchange = so.id_exchange
                                LEFT OUTER JOIN workers AS w ON w.id_worker = so.id_worker
                                WHERE so.id_sales_order = $id")[0];

        $conf = [
            'title-section' => 'Pedido: ',
            'group' => 'sales-order',
            'back' => 'sales-order.index',
            'edit' => ['route' => 'sales-order.edit', 'id' => $id],
        ];


        $dataSalesOrderDetails = salesOrderDetails::whereIdSalesOrder($id)->get()[0];

        $obj = json_decode($dataSalesOrderDetails->details_order_detail, true);

        for ($i = 0; $i < count($obj['id_product']); $i++) {
            $dataProducts[$i] =  DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                                                FROM products 
                                                INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                                                INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                                                WHERE products.id_product =" . $obj['id_product'][$i]);
        }


        return view('sales.sales-order.show', compact('conf', 'data', 'dataProducts', 'obj'));
    }



    public function edit($id)
    {

        $data =  DB::select("SELECT so.*, c.address_client, c.phone_client, c.idcard_client, c.name_client, w.firts_name_worker, w.last_name_worker, e.amount_exchange, e.date_exchange
        FROM sales_orders as so
        INNER JOIN clients AS c ON c.id_client = so.id_client
        INNER JOIN exchanges AS e ON e.id_exchange = so.id_exchange
        LEFT OUTER JOIN workers AS w ON w.id_worker = so.id_worker
        WHERE so.id_sales_order = $id")[0];


        if ($data->id_order_state == 2) {
            $message = [
                'type' => 'danger',
                'message' => 'No puede editar la orden si ya fue facturada.',
            ];

            return redirect()->route('sales-order.show', $data->id_sales_order)->with('message', $message);
        } else if ($data->id_order_state == 3) {
            $message = [
                'type' => 'danger',
                'message' => 'No puede editar la orden si ya fue cancelada.',
            ];

            return redirect()->route('sales-order.show', $data->id_sales_order)->with('message', $message);
        } else {

            $conf = [
                'title-section' => 'Pedido: ',
                'group' => 'sales-order',
                'back' => 'sales-order.index',
                'edit' => ['route' => 'sales-order.edit', 'id' => $id],
            ];

            $taxes = Tax::where('billable_tax', '=', 1)->get();
            $dataExchange = Exchange::whereEnabledExchange(1)->where('date_exchange', '=', date('Y-m-d'))->orderBy('id_exchange', 'DESC')->get()[0];




            $dataSalesOrderDetails = salesOrderDetails::whereIdSalesOrder($id)->get()[0];


            $obj = json_decode($dataSalesOrderDetails->details_order_detail, true);


            //return $obj;

            for ($i = 0; $i < count($obj['id_product']); $i++) {
                $dataProducts[$i] =  DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                            FROM products 
                            INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                            INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                            WHERE products.id_product =" . $obj['id_product'][$i]);
            }


            return view('sales.sales-order.edit', compact('conf', 'data', 'dataProducts', 'obj', 'taxes', 'dataExchange'));
        }
    }


    public function update(Request $request, $id)
    {

        $data = $request->except('_token', '_method');

        $saleOrderData = SalesOrder::whereIdSalesOrder($id)->get()[0];
        $dataSalesOrderDetails = salesOrderDetails::whereIdSalesOrder($id)->get()[0];
        $obj = json_decode($dataSalesOrderDetails->details_order_detail, true);

        for ($i = 0; $i < count($obj['id_product']); $i++) {
            $sumar =  Product::select('qty_product')->whereIdProduct($obj['id_product'][$i])->get()[0];
            $operacion = $sumar->qty_product + $obj['cantidad'][$i];
            Product::whereIdProduct($obj['id_product'][$i])->update(['qty_product' => $operacion]);
        }



        $dataDetails = $request->except('_token', 'id_client', 'type_payment_sales_order', 'subFac', 'exento', 'total_taxes', 'total_con_tax', 'noExento', 'subtotal', 'exempt_product', 'subtotal_exento', 'id_worker', 'id_exchange', 'ref_name_sales_order', 'ctrl_num');




        salesOrder::whereIdSalesOrder($id)->update([
            'type_payment' => $data['type_payment_sales_order'],
            'id_client' => $data['id_client'],
            'id_exchange' => $data['id_exchange'],
            'id_user' => Auth::id(),
            'total_amount_sales_order' => $data['total_con_tax'],
            'exempt_amout_sales_order' => $data['exento'],
            'no_exempt_amout_sales_order' => $data['subFac'],
            'total_amount_tax_sales_order' => $data['subFac'],
        ]);


        salesOrderDetails::whereIdSalesOrder($id)->update(['details_order_detail' => json_encode($dataDetails)]);

        for ($i = 0; $i < count($data['id_product']); $i++) {
            $restar =  Product::select('qty_product')->whereIdProduct($data['id_product'][$i])->get();
            $operacion = $restar[0]->qty_product - $data['cantidad'][$i];
            Product::whereIdProduct($data['id_product'][$i])->update(['qty_product' => $operacion]);
        }


        $deliveryNote = DeliveryNotes::where('ref_name_delivery_note', '=', $saleOrderData->ref_name_sales_order)->get();

        if (count($deliveryNote) > 0) {
            $total = $data['subFac'] + $data['exento'];
            $payments = Payments::where('id_delivery_note', '=', $deliveryNote[0]->id_delivery_note)->get();
            if (count($payments) > 0) {               
                $pagos = 0;
                foreach($payments as $pay){
                    $pagos = $pagos+$pay->amount_payment;
                }
                $state = ($pagos>$total) ? 7 : 6 ;

                DeliveryNotes::where('id_delivery_note', '=', $deliveryNote[0]->id_delivery_note)
                    ->update(
                        [
                            'type_payment' => $saleOrderData->type_payment,
                            'id_client' => $saleOrderData->id_client,
                            'id_exchange' => $saleOrderData->id_exchange,
                            'ctrl_num' => $saleOrderData->ctrl_num,
                            'ref_name_delivery_note' => $saleOrderData->ref_name_sales_order,
                            'residual_amount_delivery_note' => $pagos-$total,
                            'total_amount_delivery_note' => $total,
                            'exempt_amout_delivery_note' => $data['exento'],
                            'id_order_state' => $state,
                            'no_exempt_amout_delivery_note' => $data['subFac'],
                            'date_delivery_note' => date('Y-m-d'),
                        ]
                    );

                DeliveryNotesDetails::where('id_delivery_note', '=', $deliveryNote[0]->id_delivery_note)
                    ->update(
                        [
                            'id_delivery_note' => $deliveryNote[0]->id_delivery_note,
                            'details_delivery_notes' => json_encode($dataDetails),
                        ]
                    );
            }
        }



        $message = [
            'type' => 'warning',
            'message' => 'Se actualizo el pedido con éxito',
        ];

        return redirect()->route('sales-order.index')->with('message', $message);
    }


    public function anular($id)
    {

        $dataSalesOrderDetails = salesOrderDetails::whereIdSalesOrder($id)->get()[0];

        $obj = json_decode($dataSalesOrderDetails->details_order_detail, true);

        for ($i = 0; $i < count($obj['id_product']); $i++) {
            $sumar =  Product::select('qty_product')->whereIdProduct($obj['id_product'][$i])->get()[0];
            $operacion = $sumar->qty_product + $obj['cantidad'][$i];
            Product::whereIdProduct($obj['id_product'][$i])->update(['qty_product' => $operacion]);
        }

        SalesOrder::whereIdSalesOrder($id)->update(['id_order_state' => 3]);

        return redirect()->route('sales-order.show', $id);
    }





    public function listar(Request $request)
    {

        if ($request->texto == 'clientes') {
            if (isset($request->param)) {
                $dataClientes =  DB::select("SELECT * 
                                                FROM clients 
                                                WHERE name_client LIKE '%" . $request->param . "%' 
                                                OR idcard_client LIKE '%" . $request->param . "%'");
                return response()->json(
                    [
                        'lista' => $dataClientes,
                        'th' => ['Cedula', 'Nombre o Razon social'],
                        'success' => true,
                        'title' => 'Lista de Clientes'
                    ]
                );
            }
            $dataClientes = Client::whereEnabledClient(1)->get();


            return response()->json(
                [
                    'lista' => $dataClientes,
                    'th' => ['Cedula', 'Nombre o Razon social'],
                    'success' => true,
                    'title' => 'Lista de Clientes'
                ]
            );
        } else {
            if (is_int($request->param) == true) {
                $request->param = "";
            }
            if ($request->param != "") {
                $dataProductos =  DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                                                FROM products 
                                                INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                                                INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                                                INNER JOIN warehouses AS w ON w.id_warehouse = products.id_warehouse
                                                WHERE qty_product > 0 
                                                AND salable_product = 1
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

                $dataProductos =  DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                                                FROM products 
                                                INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                                                INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                                                INNER JOIN warehouses AS w ON w.id_warehouse = products.id_warehouse
                                                WHERE qty_product > 0 
                                                AND salable_product = 1
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
