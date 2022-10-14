<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Accounting\AccountingEntriesController;
use App\Http\Controllers\Accounting\MovesAccountsController;
use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountingEntries;
use App\Models\Accounting\TypeLedgerAccounts;
use App\Models\Conf\Bank;
use App\Models\Payments\Payments;
use App\Models\Sales\DeliveryNotes;
use App\Models\Sales\Invoicing;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:payment-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|payment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|payment-edit', ['only' => ['edit', 'update']]);
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
            'ths' => ['#', 'Fecha', 'Cliente', 'Factura', 'Pedido', 'Banco', 'Monto'],
            'w_ts' => ['3', '', '', '', '', '', '',],
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
            'tds' => ['date_payment', 'name_client', 'ref_name_invoicing', 'ref_name_sales_order', 'name_bank', 'amount_payment'],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,
            'show' => true,
            'url' => "/accounting/payments",
            'id' => 'id_payment',
            'data' => Payments::select('id_payment', 'type_pay', 'date_payment', 'name_client', 'ref_name_invoicing', 'ref_name_delivery_note', 'name_bank', 'amount_payment')
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice', 'left')
                ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note', 'left')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->where('enabled_payment', '=', 1)
                ->orderBy('id_payment', 'ASC')
                ->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        //return $table['data'];

        return view('accounting.payments.index', compact('conf', 'table'));
    }


    public function store(Request $request)
    {



        $data = $request->except('_token');

        //return $data;
        $payment = new Payments();
        $payment->date_payment = $data['date_payment'];
        $payment->ref_payment = $data['ref_payment'];
        $payment->id_bank = $data['id_bank'];
        $payment->amount_payment = $data['amount_payment'];

        $payment->id_client = $data['id_client'];
        $payment->type_pay = $data['type_pay'];


        if ($data['type_pay'] == 1) {
            $invoce = Invoicing::whereIdInvoicing($data['id_invoice'])->get()[0];
            if ($invoce->residual_amount_invoicing == $payment->amount_payment) {
                Invoicing::whereIdInvoicing($data['id_invoice'])->update(['residual_amount_invoicing' => 0.00, 'id_order_state' => 5]);
                $payment->id_invoice = $data['id_invoice'];
                $payment->save();
            } elseif ($invoce->residual_amount_invoicing > $payment->amount_payment) {
                $resto = $invoce->residual_amount_invoicing - $payment->amount_payment;
                Invoicing::whereIdInvoicing($data['id_invoice'])->update(['residual_amount_invoicing' => $resto,]);
                $payment->id_invoice = $data['id_invoice'];
                $payment->save();
            } else {
                $resto = $payment->amount_payment - $invoce->residual_amount_invoicing;
                Invoicing::whereIdInvoicing($data['id_invoice'])->update(['residual_amount_invoicing' => $resto, 'id_order_state' => 5]);
                $payment->id_invoice = $data['id_invoice'];
                $payment->save();
            }
            return redirect()->route('invoicing.show', $data['id_invoice']);
        } else {
            $dn = DeliveryNotes::whereIdDeliveryNote($data['id_delivery_note'])->get()[0];


            //return $dn;
            if ($dn->residual_amount_invoicing == $payment->amount_payment) {
                $payment->id_delivery_note  = $data['id_delivery_note'];
                DeliveryNotes::whereIdDeliveryNote($data['id_delivery_note'])->update(['residual_amount_delivery_note' => 0.00, 'id_order_state' => 7]);

                $payment->save();
            } elseif ($dn->residual_amount_delivery_note > $payment->amount_payment) {
                $resto = $dn->residual_amount_delivery_note - $payment->amount_payment;
                DeliveryNotes::whereIdDeliveryNote($data['id_delivery_note'])->update(['residual_amount_delivery_note' => $resto]);
                $payment->id_delivery_note  = $data['id_delivery_note'];
                $payment->save();
            } else {
                $resto = $payment->amount_payment - $dn->residual_amount_delivery_note;
                DeliveryNotes::whereIdDeliveryNote($data['id_delivery_note'])->update(['residual_amount_delivery_note' => $resto, 'id_order_state' => 7]);
                $payment->id_delivery_note  = $data['id_delivery_note'];
                $payment->save();
            }
            return redirect()->route('deliveries-notes.show', $data['id_delivery_note']);
        }
    }

    public function show($id)
    {
        $conf = [
            'title-section' => 'Pago',
            'group' => 'sales-invoicing',
            'back' => 'payments.index',
        ];

        $data1 = Payments::select('type_pay')->find($id);

        //return $data1;

        if ($data1->type_pay == 1) {
            $data = Payments::select('id_payment', 'date_payment', 'name_client', 'ref_name_invoicing', 'name_bank', 'amount_payment')
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->whereIdPayment($id)
                ->get()[0];
        } else {
            $data = Payments::select('id_payment', 'date_payment', 'name_client', 'ref_name_delivery_note', 'name_bank', 'amount_payment')
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->whereIdPayment($id)
                ->get()[0];
        }



        // return $data;

        return view('accounting.payments.show', compact('data', 'conf'));
    }


    public function imprimirTipos($id, $type)
    {
        // $existe = (count() > 0) ? true : false;

        $existe = Payments::where('id_client', '=', $id)->where('enabled_payment', '=', 1)->get();
        
        if (count($existe) > 0) {

            if ($type = 1) {
                $data = Payments::select('id_payment', 'type_pay', 'date_payment', 'name_client', 'ref_name_invoicing', 'ref_name_delivery_note', 'name_bank', 'amount_payment')
                    ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                    ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice', 'left')
                    ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note', 'left')
                    ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                    ->where('enabled_payment', '=', 1)
                    ->where('payments.id_client', '=', $id)
                    ->orderBy('id_payment', 'ASC')
                    ->orderBy('date_payment', 'ASC')
                    ->get();

                // return $data;

                $pdf = \PDF::loadView('accounting.payments.reportes.cliente', compact('data'))->setPaper('a4', 'landscape');
                return $pdf->stream(date('dmY') . '_pagos_general.pdf');
            } else {

                return 'no';

                
            }
        }else{
            $message = [
                'type' => 'danger',
                'message' => 'el cliente no tiene pagos realizados',
            ];

            return redirect()->route('payments.index')->with('message', $message);
        }
    }

    public function imprimirGeneral()
    {


        $data = Payments::select('id_payment', 'type_pay', 'date_payment', 'name_client', 'ref_name_invoicing', 'ref_name_delivery_note', 'name_bank', 'amount_payment')
            ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
            ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice', 'left')
            ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note', 'left')
            ->join('clients', 'clients.id_client', '=', 'payments.id_client')
            ->where('enabled_payment', '=', 1)
            ->orderBy('id_payment', 'ASC')
            ->orderBy('date_payment', 'ASC')
            ->get();


        $pdf = \PDF::loadView('accounting.payments.reportes.general', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream(date('dmY') . '_pagos_general.pdf');
    }




    public function imprimirPago($id)
    {


        $data1 = Payments::select('type_pay')->find($id);

        //return $data1;

        if ($data1->type_pay == 1) {
            $data = Payments::select('id_payment', 'date_payment', 'name_client', 'ref_name_invoicing', 'name_bank', 'amount_payment')
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->whereIdPayment($id)
                ->get()[0];
        } else {
            $data = Payments::select('id_payment', 'date_payment', 'name_client', 'ref_name_delivery_note', 'name_bank', 'amount_payment')
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->whereIdPayment($id)
                ->get()[0];
        }



        $pdf = \PDF::loadView('accounting.payments.reportes.pagos', compact('data'))->setPaper('a4', 'portrait');
        return $pdf->stream(date('dmY') . '_pagos_general.pdf');
    }
}
