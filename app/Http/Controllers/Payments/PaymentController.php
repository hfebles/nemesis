<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Accounting\AccountingEntriesController;
use App\Http\Controllers\Accounting\MovesAccountsController;
use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountingEntries;
use App\Models\Accounting\TypeLedgerAccounts;
use App\Models\Conf\Bank;
use App\Models\Payments\Payments;
use App\Models\Sales\Invoicing;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:payment-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|payment-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|payment-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|payment-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $conf = [
            'title-section' => 'Pagos Recibidos',
            'group' => 'payment',
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Fecha', 'Cliente', 'Factura', 'Banco', 'Monto'],
            'w_ts' => ['3','','','','','',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['date_payment', 'name_client', 'ref_name_invoicing', 'name_bank', 'amount_payment'],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,  
            'show' => true,
            'url' => "/accounting/payments",
            'id' => 'id_payment',
        'data' => Payments::select('id_payment','date_payment', 'name_client', 'ref_name_invoicing', 'name_bank', 'amount_payment')
                                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                                ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice')
                                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                                ->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];



        

        return view('accounting.payments.index', compact('conf', 'table'));
    }


    public function store(Request $request){

       // return 

       $data = $request->except('_token');

        $payment = new Payments();
 
        $payment->date_payment = $data['date_payment'];
        $payment->ref_payment = $data['ref_payment'];
        $payment->id_bank = $data['id_bank'];
        $payment->amount_payment = $data['amount_payment'];
        $payment->id_invoice = $data['id_invoice'];
        $payment->id_client = $data['id_client'];
        
        
        $invoce = Invoicing::whereIdInvoicing($payment->id_invoice)->get()[0];

        if($invoce->residual_amount_invoicing == $payment->amount_payment){
            Invoicing::whereIdInvoicing($payment->id_invoice)->update(['residual_amount_invoicing' => 0.00, 'id_order_state' => 5]);
            $payment->save();
        }
        elseif($invoce->residual_amount_invoicing > $payment->amount_payment){
        }else{
            $resto = $payment->amount_payment-$invoce->residual_amount_invoicing;
            Invoicing::whereIdInvoicing($payment->id_invoice)->update(['residual_amount_invoicing' => $resto, 'id_order_state' => 5]);
            $payment->save();
        }

        return redirect()->route('invoicing.show', $data['id_invoice']);
            
        
    }

    public function show($id){
        $conf = [
            'title-section' => 'Pago: ',
            'group' => 'sales-invoicing',
            'back' => 'payments.index',
        ];

        $data = Payments::select('date_payment', 'name_client', 'ref_name_invoicing', 'name_bank', 'amount_payment')
        ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
        ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice')
        ->join('clients', 'clients.id_client', '=', 'payments.id_client')
        ->whereIdPayment($id)
        ->get()[0];

        // return $data;

        return view('accounting.payments.show', compact('data', 'conf'));
    }
}
