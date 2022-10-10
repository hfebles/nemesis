<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Accounting\AccountingEntriesController;
use App\Http\Controllers\Accounting\MovesAccountsController;
use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountingEntries;
use App\Models\Accounting\MovesAccounts;
use App\Models\Accounting\SubLedgerAccount;
use App\Models\Accounting\TypeLedgerAccounts;
use App\Models\Conf\Bank;
use App\Models\Conf\Exchange;
use App\Models\Conf\Sales\InvoicingConfigutarion;
use App\Models\Conf\Tax;
use App\Models\Payments\Payments;
use App\Models\Products\Product;
use App\Models\Sales\Client;
use App\Models\Sales\Invoicing;
use App\Models\Sales\InvoicingDetails;
use App\Models\Sales\SalesOrder;
use App\Models\Sales\SalesOrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoicingController extends Controller
{
    
    function __construct()
    {
         $this->middleware('permission:sales-invoices-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:sales-invoices-create|adm-create', ['only' => ['create','store', 'validarPedido']]);
         $this->middleware('permission:adm-edit|sales-invoices-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|sales-invoices-delete', ['only' => ['destroy', 'anularFactura']]);
    }


    public function index(Request $request){

        $conf = [
            'title-section' => 'Facturación',
            'group' => 'sales-invoices',
            'create' => ['route' =>'invoicing.create', 'name' => 'Nueva factura'],
        ];

        $data = Invoicing::select('id_invoicing', 'residual_amount_invoicing', 'ref_name_invoicing', 'date_invoicing', 'name_client', 'total_amount_invoicing', 'os.name_order_state', 'c.name_client')
                            ->join('clients as c', 'c.id_client', '=', 'invoicings.id_client', 'left outer')
                            ->join('order_states as os', 'os.id_order_state', '=', 'invoicings.id_order_state', 'left outer')
                            ->whereEnabledInvoicing(1)
                            ->orderBy('id_invoicing', 'ASC')
                            ->paginate(10);

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Factura', 'Fecha', 'Cliente', 'Estado', 'Total', 'A cobrar'],
            'w_ts' => ['3','10','10','41','12','12', '12',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['ref_name_invoicing', 'date_invoicing', 'name_client', 'name_order_state', 'total_amount_invoicing', 'residual_amount_invoicing'],
            'switch' => false,
            'edit' => false, 
            'show' => true,
            'edit_modal' => false, 
            'url' => "/sales/invoicing",
            'id' => 'id_invoicing',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 5),
        ];
        return view('sales.invoices.index', compact('conf', 'table'));

    }


    public function create(){

        $conf = [
            'title-section' => 'Nueva factura',
            'group' => 'sales-invoices',
            'back' => 'invoicing.index',
        ];

        $dataExchange = Exchange::whereEnabledExchange(1)->where('date_exchange', '=', date('Y-m-d'))->orderBy('id_exchange', 'DESC')->get();

        $dataUsers = Client::whereEnabledClient(1)->get();

        if (count($dataUsers) == 0) {
            return redirect()->route('clients.index')->with('success', "Debe registrar un cliente");
        }

        //return $dataExchange;
        if(count($dataExchange) == 0){
            return redirect()->route('exchange.index')->with('success', 'Debe registrar una tasa cambiaria');
        }else{
            $dataExchange = $dataExchange[0];
        }


        $dataConfiguration = InvoicingConfigutarion::all();

       // return $dataConfig;

        if(count($dataConfiguration) == 0){
            return redirect()->route('invoices-config.index');
        }else{
            $dataConfiguration  = $dataConfiguration [0];
            $config = $dataConfiguration ->control_number_invoicing_configutarion;
        }  

        $datax = Invoicing::whereEnabledInvoicing(1)->orderBy('id_invoicing', 'DESC')->get();

        if(count($datax) > 0){
            if( $config == $datax[0]->ctrl_num){
                $config = $datax[0]->ctrl_num+1;
            }
            $config = $datax[0]->ctrl_num+1;
        }

        $taxes = Tax::where('billable_tax', '=', 1)->get();

        $dataWorkers = \DB::select("SELECT workers.id_worker, workers.firts_name_worker, workers.last_name_worker, group_workers.name_group_worker
                                    FROM workers
                                    INNER JOIN group_workers ON group_workers.id_group_worker = workers.id_group_worker
                                    WHERE name_group_worker = 'VENDEDOR'");

        return view('sales.invoices.create', compact('conf', 'dataWorkers', 'dataExchange', 'dataConfiguration', 'config', 'taxes'));
    }

    public function store(Request $request){

        $dataConfiguration = InvoicingConfigutarion::all()[0];

        $dataInvoice = $request->except('_token');
        $dataDetails = $request->except('_token',
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
                                        'ctrl_num');

     

            $saveInvoice = new Invoicing();

            $saveInvoice->type_payment = $dataInvoice['type_payment_sales_order'];
            $saveInvoice->id_client = $dataInvoice['id_client'];
            $saveInvoice->id_exchange = $dataInvoice['id_exchange'];
            $saveInvoice->ctrl_num = $dataInvoice['ctrl_num'];



            
            
            $saveInvoice->ref_name_invoicing = $dataConfiguration->correlative_invoicing_configutarion.'-'.str_pad($dataInvoice['ctrl_num'], 6, "0", STR_PAD_LEFT);

            if(isset($dataInvoice['id_worker'])){
                $saveInvoice->id_worker = $dataInvoice['id_worker'];
            }
            
            $saveInvoice->id_user = Auth::id();
            $saveInvoice->total_amount_invoicing = $dataInvoice['total_con_tax'];
            $saveInvoice->exempt_amout_invoicing = $dataInvoice['exento'];
            $saveInvoice->no_exempt_amout_invoicing = $dataInvoice['subFac'];
            $saveInvoice->total_amount_tax_invoicing = $dataInvoice['total_taxes'];
            $saveInvoice->date_invoicing = date('Y-m-d');
            $saveInvoice->id_order_state = 4;
            $saveInvoice->residual_amount_invoicing = $dataInvoice['total_con_tax'];
            $saveInvoice->save();


     
            $saveDetails = new InvoicingDetails();
            $saveDetails->id_invoicing = $saveInvoice->id_invoicing;
            $saveDetails->details_invoicing_detail = json_encode($dataDetails);
            $saveDetails->save();


            

            for($i = 0; $i<count($dataInvoice['id_product']); $i++){
                $restar =  Product::select('qty_product')->whereIdProduct($dataInvoice['id_product'][$i])->get();
                $operacion = $restar[0]->qty_product - $dataInvoice['cantidad'][$i];
                
                Product::whereIdProduct($dataInvoice['id_product'][$i])->update(['qty_product'=>$operacion]);
            }



            $message = [
                'type' => 'success',
                'message' => 'Se registro con éxito',
            ];
                            
            return redirect()->route('invoicing.index')->with('message', $message);
    }


    public function validarPedido($id)
    {

        $dataSalesOrder = SalesOrder::whereIdSalesOrder($id)->get()[0];
        $dataDetails = SalesOrderDetails::whereIdSalesOrder($id)->get()[0];
        $dataConfig = InvoicingConfigutarion::all();

        if(count($dataConfig) == 0){
            return redirect()->route('order-config.index')->with('success', 'Debe registrar una tasa');
        }else{
            $dataConfig = $dataConfig[0];
            $config = $dataConfig->control_number_invoicing_configutarion;
        }  

        $datax = Invoicing::whereEnabledInvoicing(1)->orderBy('id_invoicing', 'DESC')->get();

        if(count($datax) > 0){
            
            if( $config == $datax[0]->ctrl_num){
                $config = $datax[0]->ctrl_num+1;
            }
            $config = $datax[0]->ctrl_num+1;
        }

        $inv = new Invoicing();
        $invDetails = new InvoicingDetails();
        $inv->type_payment = $dataSalesOrder['type_payment'];
        $inv->id_client = $dataSalesOrder['id_client'];
        $inv->id_exchange = $dataSalesOrder['id_exchange'];
        $inv->ctrl_num = $config;
        $inv->ref_name_invoicing = $dataConfig->correlative_invoicing_configutarion.'-'.str_pad($config, 6, "0", STR_PAD_LEFT);

        if(isset($dataSalesOrder['id_worker'])){
            $inv->id_worker = $dataSalesOrder['id_worker'];
        }
        
        $inv->id_user = $dataSalesOrder['id_user'];
        $inv->residual_amount_invoicing = $dataSalesOrder['total_amount_sales_order'];
        $inv->total_amount_invoicing = $dataSalesOrder['total_amount_sales_order'];
        $inv->exempt_amout_invoicing = $dataSalesOrder['exempt_amout_sales_order'];
        $inv->id_order_state = 4;
        $inv->no_exempt_amout_invoicing = $dataSalesOrder['no_exempt_amout_sales_order'];
        $inv->total_amount_tax_invoicing = $dataSalesOrder['total_amount_tax_sales_order'];
        $inv->date_invoicing = date('Y-m-d');
        $inv->save();

        $invDetails->id_invoicing = $inv->id_invoicing;
        $invDetails->details_invoicing_detail = $dataDetails['details_order_detail'];
        $invDetails->save();

        SalesOrder::whereIdSalesOrder($id)->update(['id_order_state' => 2, 'id_invoice' => $inv->id]);

        // $move = (new MovesAccountsController)->createMoves($inv->id, 1);
        // $accountEntry = (new AccountingEntriesController)->saveEntries($move['id_move'], $move['type_move'], $inv->id);

        // if($accountEntry == true){
        //     return redirect()->route('invoicing.show', $inv->id);
        // }

        return redirect()->route('invoicing.show', $inv->id_invoicing);
        
    }



    public function show($id){
        $data =  \DB::select("SELECT i.*, c.address_client, c.phone_client, c.idcard_client, c.name_client, w.firts_name_worker, w.last_name_worker, e.amount_exchange, e.date_exchange
        FROM invoicings as i
        INNER JOIN clients AS c ON c.id_client = i.id_client
        INNER JOIN exchanges AS e ON e.id_exchange = i.id_exchange
        LEFT OUTER JOIN workers AS w ON w.id_worker = i.id_worker
        WHERE i.id_invoicing = $id")[0];


    // return $data;

        $conf = [
            'title-section' => 'Factura: '.$data->ref_name_invoicing,
            'group' => 'sales-invoicing',
            'back' => 'invoicing.index',
        ];

        

        $dataDetails = InvoicingDetails::whereIdInvoicing($id)->get()[0];

        $obj = json_decode($dataDetails->details_invoicing_detail, true);

        $dataBanks = Bank::where('enabled_bank', '=', 1)->pluck('name_bank', 'id_bank');

        $payments = Payments::select('payments.*', 'name_bank')
        ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
        ->whereIdInvoice($id)
        ->get();

        $a = DB::select("SELECT  sum(total_amount_invoicing) as suma1
                            FROM invoicings 
                            WHERE invoicings.id_client = $data->id_client");

        

        $b = DB::select("SELECT  sum(amount_payment) as suma2
                            FROM payments 
                            WHERE payments.id_client = $data->id_client");

                            // return [
                            //         'cliente' => $data->id_client,
                            //         'suma1'=> $a[0]->suma1,
                            //         'suma2' => $b[0]->suma2,
                            //         'total' => $b[0]->suma2-$a[0]->suma1,];

        


        for($i = 0; $i<count($obj['id_product']); $i++){
            $dataProducts[$i] =  \DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                                                FROM products 
                                                INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                                                INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                                                WHERE products.id_product =".$obj['id_product'][$i]);
        }

            
        return view('sales.invoices.show', compact('conf', 'data', 'dataProducts', 'obj', 'dataBanks', 'payments'));

               

    }





    public function imprimirFactura($id){
        
        
        $data =  \DB::select("SELECT i.*, c.id_client, c.address_client, c.phone_client, c.idcard_client, c.name_client, w.firts_name_worker, w.last_name_worker, e.amount_exchange, e.date_exchange
        FROM invoicings as i
        INNER JOIN clients AS c ON c.id_client = i.id_client
        INNER JOIN exchanges AS e ON e.id_exchange = i.id_exchange
        LEFT OUTER JOIN workers AS w ON w.id_worker = i.id_worker
        WHERE i.id_invoicing = $id")[0];

        $dataDetails = InvoicingDetails::whereIdInvoicing($id)->get()[0];
        $obj = json_decode($dataDetails->details_invoicing_detail, true);

        $cantita = 0;
        foreach($obj['cantidad'] as $kk){
            $cantita = $cantita+$kk;
       }

       

       

        for($i = 0; $i<count($obj['id_product']); $i++){
            $dataProducts[$i] =  \DB::select("SELECT products.*, p.name_presentation_product, u.name_unit_product, u.short_unit_product
                                                FROM products 
                                                INNER JOIN presentation_products AS p ON p.id_presentation_product = products.id_presentation_product
                                                INNER JOIN unit_products AS u ON u.id_unit_product = products.id_unit_product
                                                WHERE products.id_product =".$obj['id_product'][$i]);
        }
        $dataGeneral = [ 'fecha' => date('d-m-Y'), 'cantidad' =>  $cantita];
        

        //return $data;
        
        $pdf = \PDF::loadView('sales.reportes.facturas2', compact('data', 'dataProducts', 'obj', 'dataGeneral'));
        return $pdf->stream('ejemplo.pdf');
    }


    //anular

    public function anularFactura($id){

        $dataDetails = InvoicingDetails::whereIdInvoicing($id)->get()[0];

        $obj = json_decode($dataDetails->details_invoicing_detail, true);

        for($i = 0; $i<count($obj['id_product']); $i++){
            $sumar =  Product::select('qty_product')->whereIdProduct($obj['id_product'][$i])->get()[0];
            $operacion = $sumar->qty_product + $obj['cantidad'][$i];
            Product::whereIdProduct($obj['id_product'][$i])->update(['qty_product'=>$operacion]);
        }

        Invoicing::whereIdInvoicing($id)->update(['id_order_state'=>3]);

        return redirect()->route('invoicing.show', $id);
       
    }

    


    
}
