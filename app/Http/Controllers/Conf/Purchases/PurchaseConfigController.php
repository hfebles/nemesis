<?php

namespace App\Http\Controllers\Conf\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Accounting\LedgerAccount;
use App\Models\Conf\Purchases\PurchaseConfig;
use Illuminate\Http\Request;

class PurchaseConfigController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:purchase-config-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|purchase-config-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|purchase-config-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|purchase-config-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = PurchaseConfig::select('purchase_configs.*', 'ledger_accounts.name_ledger_account')
            ->join('ledger_accounts', 'ledger_accounts.id_ledger_account', '=', 'purchase_configs.id_ledger_account')->get()[0];

        $conf = [
            'title-section' => 'Configuración de las facturas de compra',
            'group' => 'purchase-config',
            'edit' => ['route' => 'purchase-config.edit', 'id' => $data->id_purchase_config,],
        ];

        // return $data;
        return view('conf.purchases.purchase-config.index', compact('conf', 'data'));
    }

    public function edit($id)
    {
        $data = PurchaseConfig::find($id);
        $typeLedger = LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->pluck('name_ledger_account', 'id_type_ledger_account');
        $conf = [
            'title-section' => 'Configuración de las facturas de compra',
            'group' => 'purchase-config',
            'back' => 'purchase-config.index',

        ];

        // return $data;
        return view('conf.purchases.purchase-config.edit', compact('conf', 'data', 'typeLedger'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token', '_method');
        PurchaseConfig::whereidPurchaseConfig($id)->update($data);
        return redirect()->route('invoices-config.index');
    }

    public function getInvConf()
    {
        return PurchaseConfig::all()[0];
    }
}
