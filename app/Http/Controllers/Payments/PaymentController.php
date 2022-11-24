<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Accounting\AccountingEntriesController;
use App\Http\Controllers\Accounting\MovesAccountsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Delivery\DeliveryController;
use App\Http\Controllers\Sales\DeliveryNotesController;
use App\Http\Controllers\Sales\InvoicingController;
use App\Models\Accounting\AccountingEntries;
use App\Models\Accounting\TypeLedgerAccounts;
use App\Models\Conf\Bank;
use App\Models\Payments\Payments;
use App\Models\Payments\Surplus;
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
            'tds' => [
                'date_payment',
                'name_client',
                'ref_name_invoicing',
                'ref_name_sales_order',
                'name_bank',
                'amount_payment',
                'estadoFactura',
                'estadoPedido',
                'invoicings.id_order_state',
            ],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,
            'show' => true,
            'url' => "/accounting/payments",
            'id' => 'id_payment',
            'data' => Payments::select(
                'id_payment',
                'type_pay',
                'date_payment',
                'name_client',
                'ref_payment',
                'ref_name_invoicing',
                'ref_name_delivery_note',
                'name_bank',
                'amount_payment',
                'invoicings.id_order_state as invOd',
                'delivery_notes.id_order_state as dnOd',
                \DB::raw('CASE 
                            WHEN invoicings.id_order_state = 3 THEN "Facutura Cancelada"
                            END as estadoFactura'),
                \DB::raw('CASE 
                            WHEN delivery_notes.id_order_state = 3 THEN "Pedido Cancelado"
                            END as estadoPedido'),
            )
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice', 'left')
                ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note', 'left')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->where('enabled_payment', '=', 1)
                ->orderBy('id_payment', 'ASC')
                ->paginate(15),
            'i' => (($request->input('page', 1) - 1) * 15),
        ];

        //return $table['data'];

        return view('accounting.payments.index', compact('conf', 'table'));
    }


    public function store(Request $request)
    {



        $data = $request->except('_token');

        // return $data;
        $payment = new Payments();
        $payment->date_payment = $data['date_payment'];
        $payment->ref_payment = $data['ref_payment'];
        $payment->id_bank = $data['id_bank'];
        $payment->amount_payment = $data['amount_payment'];
        $payment->id_client = $data['id_client'];
        $payment->type_pay = $data['type_pay'];





        if ($data['type_pay'] == 1) {

            $invoice = (new InvoicingController)->getDataInv($data['id_invoice']);
            if ($invoice->residual_amount_invoicing == $payment->amount_payment) {
                Invoicing::whereIdInvoicing($invoice->id_invoicing)->update(['residual_amount_invoicing' => 0.00, 'id_order_state' => 5]);
                $payment->id_invoice = $invoice->id_invoicing;
                $payment->save();

                $move = (new MovesAccountsController)->createMoves($invoice->id_invoicing, $payment->date_payment, 3);
                $result = (new AccountingEntriesController)->saveEntriesPayments($move, $invoice->id_invoicing, $payment->amount_payment, $payment->id_bank);
            } elseif ($invoice->residual_amount_invoicing > $payment->amount_payment) {
                $resto = $invoice->residual_amount_invoicing - $payment->amount_payment;
                Invoicing::whereIdInvoicing($data['id_invoice'])->update(['residual_amount_invoicing' => $resto,]);
                $payment->id_invoice = $data['id_invoice'];
                $payment->save();
                $move = (new MovesAccountsController)->createMoves($invoice->id_invoicing, $payment->date_payment, 3);
                $result = (new AccountingEntriesController)->saveEntriesPayments($move, $invoice->id_invoicing, $payment->amount_payment, $payment->id_bank);
            } else {
                $resto = $payment->amount_payment - $invoice->residual_amount_invoicing;
                $payment->id_invoice = $data['id_invoice'];
                $payment->save();
                $move = (new MovesAccountsController)->createMoves($invoice->id_invoicing, $payment->date_payment, 3);
                $result = (new AccountingEntriesController)->saveEntriesPayments($move, $invoice->id_invoicing, $payment->amount_payment, $payment->id_bank);

                Surplus::create([
                    'amount_surplus' => $resto,
                    'id_payment' => $payment->id_payment,
                    'id_client' => $invoice->id_client,
                ]);


                Invoicing::whereIdInvoicing($payment->id_invoice)->update(['residual_amount_invoicing' => 0.00, 'id_order_state' => 5]);
            }

            return redirect()->route('invoicing.show', $data['id_invoice']);
        } else {
            $dn = (new DeliveryNotesController)->getDataDN($data['id_delivery_note']);
            if ($dn->residual_amount_delivery_note == intval($payment->amount_payment)) {
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
                $payment->id_delivery_note  = $data['id_delivery_note'];
                $payment->save();
                Surplus::create([
                    'amount_surplus' => $resto,
                    'id_payment' => $payment->id_payment,
                    'id_client' => $dn->id_client,
                ]);
                DeliveryNotes::whereIdDeliveryNote($data['id_delivery_note'])->update(['residual_amount_delivery_note' => 0.00, 'id_order_state' => 7]);
            }
            return redirect()->route('deliveries-notes.show', $data['id_delivery_note']);
        }
    }


    public function registerPayBySurplus($id, $invoice, $type)
    {

        $surplus = Surplus::select('id_surplus', 'amount_surplus')->where('id_payment', '=', $id)->get()[0];
        $dataPay = Payments::whereIdPayment($id)->get()[0];

        Surplus::where('id_surplus', '=', $surplus->id_surplus)->update(['used_surplus' => 0]);

        $payment = new Payments();
        $payment->date_payment =  $dataPay->date_payment;
        $payment->ref_payment = $dataPay->ref_payment . '/2';
        $payment->id_bank = $dataPay->id_bank;

        $payment->id_client = $dataPay->id_client;
        $payment->type_pay = $type;

        if ($type == 1) {
            $payment->id_invoice = $invoice;

            $inv = (new InvoicingController)->getDataInv($invoice);
            if ($inv->residual_amount_invoicing == $surplus->amount_surplus) {
                Invoicing::whereIdInvoicing($invoice)->update(['residual_amount_invoicing' => 0.00, 'id_order_state' => 5]);
                $payment->amount_payment = $surplus->amount_surplus;
                $payment->save();
                Surplus::where('id_surplus', '=', $surplus->id_surplus)->update(['id_payment_used' => $payment->id_payment]);
            } elseif ($inv->residual_amount_invoicing > $surplus->amount_surplus) {
                $resto = $inv->residual_amount_invoicing - $surplus->amount_surplus;
                $payment->amount_payment = $surplus->amount_surplus;
                $payment->save();
                Invoicing::whereIdInvoicing($invoice)->update(['residual_amount_invoicing' => $resto,]);
                Surplus::where('id_surplus', '=', $surplus->id_surplus)->update(['id_payment_used' => $payment->id_payment]);
            } else {
                $resto = $surplus->amount_surplus - $inv->residual_amount_invoicing;
                $payment->amount_payment = $inv->residual_amount_invoicing;
                $payment->save();
                Surplus::create([
                    'amount_surplus' => $resto,
                    'id_payment' => $payment->id_payment,
                    'id_client' => $inv->id_client,
                ]);
                Surplus::where('id_surplus', '=', $surplus->id_surplus)->update(['id_payment_used' => $payment->id_payment]);
                Invoicing::whereIdInvoicing($invoice)->update(['residual_amount_invoicing' => 0.00, 'id_order_state' => 5]);
            }
            return redirect()->route('invoicing.show', $invoice);
        } else {
            $payment->id_delivery_note = $invoice;
            $dn = (new DeliveryNotesController)->getDataDN($invoice);
            if ($dn->residual_amount_delivery_note == $surplus->amount_surplus) {
                DeliveryNotes::whereIdDeliveryNote($invoice)->update(['residual_amount_delivery_note' => 0.00, 'id_order_state' => 7]);
                $payment->amount_payment = $surplus->amount_surplus;
                $payment->save();
                Surplus::where('id_surplus', '=', $surplus->id_surplus)->update(['id_payment_used' => $payment->id_payment]);
            } elseif ($dn->residual_amount_delivery_note > $surplus->amount_surplus) {
                $resto = $dn->residual_amount_delivery_note - $surplus->amount_surplus;
                $payment->amount_payment = $surplus->amount_surplus;
                $payment->save();
                DeliveryNotes::whereIdDeliveryNote($invoice)->update(['residual_amount_delivery_note' => $resto]);
                Surplus::where('id_surplus', '=', $surplus->id_surplus)->update(['id_payment_used' => $payment->id_payment]);
            } else {
                $resto = $surplus->amount_surplus - $dn->residual_amount_delivery_note;
                $payment->amount_payment = $dn->residual_amount_delivery_note;
                $payment->save();
                Surplus::create([
                    'amount_surplus' => $resto,
                    'id_payment' => $payment->id_payment,
                    'id_client' => $dn->id_client,
                ]);
                Surplus::where('id_surplus', '=', $surplus->id_surplus)->update(['id_payment_used' => $payment->id_payment]);
                DeliveryNotes::whereIdDeliveryNote($invoice)->update(['residual_amount_delivery_note' => 0.00, 'id_order_state' => 7]);
            }
            return redirect()->route('deliveries-notes.show', $invoice);
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
            $data = Payments::select('id_payment', 'date_payment', 'name_client', 'ref_name_invoicing', 'name_bank', 'amount_payment', 'ref_payment')
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->whereIdPayment($id)
                ->get()[0];
        } else {
            $data = Payments::select('id_payment', 'date_payment', 'name_client', 'ref_name_delivery_note', 'name_bank', 'amount_payment', 'ref_payment')
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->whereIdPayment($id)
                ->get()[0];
        }



        // return $data;

        return view('accounting.payments.show', compact('data', 'conf'));
    }


    public function imprimirTipos($id)
    {


        $existe = Payments::where('id_client', '=', $id)->where('enabled_payment', '=', 1)->get();

        if (count($existe) > 0) {
            $data = Payments::select(
                'id_payment',
                'type_pay',
                'date_payment',
                'ref_payment',
                'name_client',
                'ref_name_invoicing',
                'ref_name_delivery_note',
                'name_bank',
                'amount_payment'
            )
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice', 'left')
                ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note', 'left')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->where('enabled_payment', '=', 1)
                ->where('payments.id_client', '=', $id)
                ->orderBy('id_payment', 'ASC')
                ->orderBy('date_payment', 'ASC')
                ->get();

            //return $data;

            $pdf = \PDF::loadView('accounting.payments.reportes.cliente', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->stream(date('dmY') . '_pagos_general.pdf');
        } else {
            return redirect()->route('payments.index')->with('error', 'el cliente no tiene pagos realizados');
        }
    }


    public function imprimirPagoFechas(Request $request)
    {



        $data = Payments::select('id_payment', 'type_pay', 'date_payment', 'ref_payment', 'name_client', 'ref_name_invoicing', 'ref_name_delivery_note', 'name_bank', 'amount_payment')
            ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
            ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice', 'left')
            ->join('delivery_notes', 'delivery_notes.id_delivery_note', '=', 'payments.id_delivery_note', 'left')
            ->join('clients', 'clients.id_client', '=', 'payments.id_client')
            ->where('enabled_payment', '=', 1)
            ->whereBetween('date_payment', [$request->fechaDesde, $request->fechaHasta])
            ->orderBy('id_payment', 'ASC')
            ->orderBy('date_payment', 'ASC')
            ->get();

        if (count($data) > 0) {
            $pdf = \PDF::loadView('accounting.payments.reportes.general', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->stream(date('dmY') . '_pagos_general.pdf');
        } else {
            $message = [
                'type' => 'danger',
                'message' => 'No hay resultados para el rango de fechas seleccionado.',
            ];

            return redirect()->route('payments.index')->with('message', $message);
        }
    }

    public function imprimirGeneral()
    {


        $data = Payments::select(
            'id_payment',
            'type_pay',
            'date_payment',
            'ref_payment',
            'name_client',
            'ref_name_invoicing',
            'ref_name_delivery_note',
            'name_bank',
            'amount_payment',
            \DB::raw('CASE 
                    WHEN invoicings.id_order_state = 3 THEN "Facutura Cancelada"
                    END as estadoFactura'),
            \DB::raw('CASE 
                    WHEN delivery_notes.id_order_state = 3 THEN "Pedido Cancelado"
                    END as estadoPedido'),

        )
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
            $data = Payments::select('id_payment', 'date_payment', 'name_client', 'ref_payment', 'ref_name_invoicing', 'name_bank', 'amount_payment')
                ->join('banks', 'banks.id_bank', '=', 'payments.id_bank')
                ->join('invoicings', 'invoicings.id_invoicing', '=', 'payments.id_invoice')
                ->join('clients', 'clients.id_client', '=', 'payments.id_client')
                ->whereIdPayment($id)
                ->get()[0];
        } else {
            $data = Payments::select('id_payment', 'date_payment', 'name_client', 'ref_payment', 'ref_name_delivery_note', 'name_bank', 'amount_payment')
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
