<?php

namespace App\Http\Controllers\Conf\Sales;

use App\Http\Controllers\Controller;
use App\Models\Accounting\LedgerAccount;
use App\Models\Accounting\SubLedgerAccount;
use App\Models\Conf\Sales\InvoicingConfigutarion;
use Illuminate\Http\Request;

class InvoicingConfigutarionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sales-invoices-conf-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|sales-invoices-conf-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|sales-invoices-conf-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|sales-invoices-conf-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $data = InvoicingConfigutarion::select('invoicing_configutarions.*', 'ledger_accounts.name_ledger_account')
            ->join('ledger_accounts', 'ledger_accounts.id_ledger_account', '=', 'invoicing_configutarions.id_ledger_account')->get()[0];

        $conf = [
            'title-section' => 'Configuración de las facturas venta',
            'group' => 'sales-invoices-conf',
            'edit' => ['route' => 'invoices-config.edit', 'id' => $data->id_invoicing_configutarion,],
        ];
        return view('conf.sales.sales-invoice-conf.index', compact('conf', 'data'));
    }

    public function edit($id)
    {
        $data = InvoicingConfigutarion::whereIdInvoicingConfigutarion($id)->get()[0];
        $typeLedger = LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->pluck('name_ledger_account', 'id_type_ledger_account');
        $conf = [
            'title-section' => 'Configuración de los pedidos de venta',
            'group' => 'sales-invoices-conf',
            'back' => 'invoices-config.index',

        ];
        return view('conf.sales.sales-invoice-conf.edit', compact('conf', 'data', 'typeLedger'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token', '_method');
        InvoicingConfigutarion::whereIdInvoicingConfigutarion($id)->update($data);
        return redirect()->route('invoices-config.index');
    }

    public function getInvConf()
    {
        return InvoicingConfigutarion::all()[0];
    }
}
