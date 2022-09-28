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
         $this->middleware('permission:adm-create|sales-invoices-conf-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|sales-invoices-conf-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|sales-invoices-conf-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request){

        


        $data = InvoicingConfigutarion::all();

        $conf = [
            'title-section' => 'Configuración de las facturas venta',
            'group' => 'sales-invoices-conf',
            'edit' => ['route' => 'invoices-config.edit', 'id' => $data[0]->id_invoicing_configutarion,],

        ];

        //return $data;

        return view('conf.sales.sales-invoice-conf.index', compact('conf', 'data'));
    }

    public function edit($id){

        $data = InvoicingConfigutarion::whereIdInvoicingConfigutarion($id)->get();
        $dataSubAcc = SubLedgerAccount::where('enabled_sub_ledger_account', '=', '1')->pluck('name_sub_ledger_account', 'id_sub_ledger_account');


        

        $conf = [
            'title-section' => 'Configuración de los pedidos de venta',
            'group' => 'sales-invoices-conf',
            'back' => 'invoices-config.index',

        ];

        //return $data;

        return view('conf.sales.sales-invoice-conf.edit', compact('conf', 'data', 'dataSubAcc'));
    }


    public function update(Request $request, $id){

        $data = $request->except('_token', '_method');
        InvoicingConfigutarion::whereIdInvoicingConfigutarion($id)->update($data);
        return redirect()->route('invoices-config.index');
    }
}
