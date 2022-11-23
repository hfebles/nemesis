<?php

namespace App\Http\Controllers\Conf\Sales;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Group;
use App\Models\Accounting\LedgerAccount;
use App\Models\Accounting\SubGroup;
use Illuminate\Http\Request;

use App\Models\Conf\Sales\SaleOrderConfiguration;
use App\Models\Accounting\SubLedgerAccount;
use App\Models\Accounting\SubLedgerAccounts2;
use App\Models\Accounting\SubLedgerAccounts3;
use App\Models\Accounting\SubLedgerAccounts4;

class SaleOrderConfigurationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sales-order-conf-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|sales-order-conf-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|sales-order-conf-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|sales-order-conf-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $data = SaleOrderConfiguration::select('sale_order_configurations.*', 'ledger_accounts.name_ledger_account')
        ->join('ledger_accounts', 'ledger_accounts.id_ledger_account', '=', 'sale_order_configurations.id_ledger_account')->get()[0];
        $conf = [
            'title-section' => 'Configuración de los pedidos de venta',
            'group' => 'sales-order-conf',
            'edit' => ['route' => 'order-config.edit', 'id' => $data->id_sale_order_configuration,],
        ];
        return view('conf.sales.sales-order-conf.index', compact('conf', 'data'));
    }

    public function edit($id)
    {
        $data = SaleOrderConfiguration::whereIdSaleOrderConfiguration($id)->get()[0];
        $typeLedger = LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->pluck('name_ledger_account', 'id_type_ledger_account');
        $conf = [
            'title-section' => 'Configuración de los pedidos de venta',
            'group' => 'sales-order-conf',
            'back' => 'order-config.index',
        ];
        return view('conf.sales.sales-order-conf.edit', compact('conf', 'data','typeLedger'));
    }


    public function update(Request $request, $id)
    {
        $data = $request->except('_token', '_method');
        SaleOrderConfiguration::whereIdSaleOrderConfiguration($id)->update($data);
        return redirect()->route('order-config.index');
    }
}
