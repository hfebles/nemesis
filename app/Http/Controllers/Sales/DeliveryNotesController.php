<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Conf\Bank;
use App\Models\Conf\Sales\InvoicingConfigutarion;
use App\Models\Payments\Payments;
use App\Models\Payments\Surplus;
use App\Models\Sales\DeliveryNotes;
use App\Models\Sales\DeliveryNotesDetails;
use App\Models\Sales\SalesOrder;
use App\Models\Sales\SalesOrderDetails;
use Illuminate\Http\Request;

class DeliveryNotesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sales-deliveries-notes-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:sales-deliveries-notes-create|adm-create', ['only' => ['create', 'store', 'validarPedido']]);
        $this->middleware('permission:adm-edit|sales-deliveries-notes-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|sales-deliveries-notes-delete', ['only' => ['destroy', 'anularFactura']]);
    }

    function index(Request $request){

        $conf = [
            'title-section' => 'Notas de entrega',
            'group' => 'sales-invoices',
            'create' => ['route' => 'deliveries-notes.create', 'name' => 'Nueva Nota'],
        ];


        $data = DeliveryNotes::select('id_delivery_note', 'ref_name_sales_order', 'ref_name_delivery_note', 'date_delivery_note', 'name_client', 'total_amount_delivery_note', 'os.name_order_state', 'c.name_client')
            ->join('clients as c', 'c.id_client', '=', 'delivery_notes.id_client', 'left outer')
            ->join('sales_orders as so', 'so.id_invoice', '=', 'delivery_notes.id_delivery_note')
            ->join('order_states as os', 'os.id_order_state', '=', 'delivery_notes.id_order_state', 'left outer')
            ->where('so.id_order_state', '<>', 2)
            ->whereEnabledDeliveryNote(1)
            ->orderBy('id_delivery_note', 'ASC')
            ->paginate(10);

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Pedido', 'Fecha', 'Estado', 'Cliente', 'Total',],
            'w_ts' => ['3', '10', '10', '41', '12', '12',],
            'c_ths' =>
            [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
            ],
            'tds' => ['ref_name_sales_order', 'date_delivery_note', 'name_client', 'name_order_state', 'total_amount_delivery_note'],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'edit_modal' => false,
            'url' => "/sales/deliveries-notes",
            'id' => 'id_delivery_note',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 5),
        ];




        return view('sales.deliveries-notes.index', compact('conf', 'table'));
    }









    public function validarPedido($id)
    {


        
        // return $id;
        $dataSalesOrder = SalesOrder::find($id);





        $dataDetails = SalesOrderDetails::whereIdSalesOrder($id)->get()[0];

        
      //return $dataSalesOrder;

        $inv = new DeliveryNotes();
        $invDetails = new DeliveryNotesDetails();
        $inv->type_payment = $dataSalesOrder['type_payment'];
        $inv->id_client = $dataSalesOrder['id_client'];
        $inv->id_exchange = $dataSalesOrder['id_exchange'];
        $inv->ctrl_num = $dataSalesOrder->ctrl_num;
        $inv->ref_name_delivery_note = $dataSalesOrder->ref_name_sales_order;

        if (isset($dataSalesOrder['id_worker'])) {
            $inv->id_worker = $dataSalesOrder['id_worker'];
        }

        $total = $dataSalesOrder['no_exempt_amout_sales_order']+$dataSalesOrder['exempt_amout_sales_order'];

        $inv->id_user = $dataSalesOrder['id_user'];
        $inv->residual_amount_delivery_note = $total;
        $inv->total_amount_delivery_note = $total;
        $inv->exempt_amout_delivery_note = $dataSalesOrder['exempt_amout_sales_order'];
        $inv->id_order_state = 6;
        $inv->no_exempt_amout_delivery_note = $dataSalesOrder['no_exempt_amout_sales_order'];
        $inv->date_delivery_note = date('Y-m-d');
        $inv->save();

        $invDetails->id_delivery_note = $inv->id_delivery_note;
        $invDetails->details_delivery_notes = $dataDetails['details_order_detail'];
        $invDetails->save();

        SalesOrder::whereIdSalesOrder($id)->update([
            'id_order_state' => 6, 
            'id_invoice' => $inv->id_delivery_note,
            'total_amount_sales_order' =>  $total,
            'residual_amount_sales_order' =>  $total,
            'total_amount_tax_sales_order' => 0,

        ]);

        // $move = (new MovesAccountsController)->createMoves($inv->id, 1);
        // $accountEntry = (new AccountingEntriesController)->saveEntries($move['id_move'], $move['type_move'], $inv->id);

        // if($accountEntry == true){
        //     return redirect()->route('invoicing.show', $inv->id);
        // }

        //return redirect()->route('invoicing.show', $inv->id_invoicing);
        return redirect()->route('deliveries-notes.show', $inv->id_delivery_note);


    }


    public function show($id){
        $data =  \DB::select("SELECT so.*, c.address_client, c.phone_client, c.idcard_client, c.name_client, w.firts_name_worker, w.last_name_worker, e.amount_exchange, e.date_exchange
                                FROM delivery_notes as so
                                INNER JOIN clients AS c ON c.id_client = so.id_client
                                INNER JOIN exchanges AS e ON e.id_exchange = so.id_exchange
                                LEFT OUTER JOIN workers AS w ON w.id_worker = so.id_worker
                                WHERE so.id_delivery_note = $id")[0];


    //    return $data;

        $conf = [
            'title-section' => 'Nota de entrega: ',
            'group' => 'sales-order',
            'back' => 'sales-order.index',
            'edit' => ['route' => 'sales-order.edit', 'id' => $id],
        ];



        $dataBanks = Bank::whereEnabledBank(1)->pluck('name_bank', 'id_bank');

        $payments = Payments::select('payments.*', 'name_bank')
            ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
            ->where('id_delivery_note', '=', $id)
            ->where('type_pay', '=', 2)
            ->get();

            $surplus = Surplus::select('amount_surplus', 'payments.id_payment', 'payments.ref_payment', 'payments.date_payment')
                    ->join('payments', 'payments.id_payment', '=', 'surpluses.id_payment')
                    ->where('surpluses.id_client', '=', $data->id_client)
                    ->where('used_surplus', '=', 1)
                    ->get();
        

        $obj = json_decode(DeliveryNotesDetails::whereIdDeliveryNote($id)->get()[0]->details_delivery_notes, true);

        for($i = 0; $i<count($obj['id_product']); $i++){
            $dataProducts[$i] =  \DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                                                FROM products 
                                                INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                                                INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                                                WHERE products.id_product =".$obj['id_product'][$i]);
        }

        return view('sales.deliveries-notes.show', compact('conf', 'data', 'dataProducts', 'obj', 'dataBanks', 'payments', 'surplus'));
    }


    public function getDataDN($id){

        return DeliveryNotes::whereIdDeliveryNote($id)->get()[0];
    }
    

}
