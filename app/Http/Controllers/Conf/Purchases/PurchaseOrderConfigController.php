<?php

namespace App\Http\Controllers\Conf\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Accounting\LedgerAccount;
use App\Models\Conf\Purchases\PurchaseOrderConfig;
use Illuminate\Http\Request;

class PurchaseOrderConfigController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:purchase-order-config-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|purchase-order-config-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|purchase-order-config-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|purchase-order-config-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = PurchaseOrderConfig::find(1);

        $conf = [
            'title-section' => 'Configuración de las ordenes de compra',
            'group' => 'order-config',
            'edit' => ['route' => 'order-config.edit', 'id' => $data->id_purchase_order_config,],
        ];

        // return $data;
        return view('conf.purchases.purchase-order-config.index', compact('conf', 'data'));
    }

    public function edit($id)
    {
        $data = PurchaseOrderConfig::find($id);
        $typeLedger = LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->pluck('name_ledger_account', 'id_type_ledger_account');
        $conf = [
            'title-section' => 'Configuración de las ordenes de compra',
            'group' => 'order-config',
            'back' => 'order-config.index',

        ];

        // return $data;
        return view('conf.purchases.purchase-order-config.edit', compact('conf', 'data', 'typeLedger'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token', '_method');
        PurchaseOrderConfig::whereidPurchaseOrderConfig($id)->update($data);
        return redirect()->route('order-config.index');
    }

    public function getInvConf()
    {
        return PurchaseOrderConfig::find(1);
    }
}
