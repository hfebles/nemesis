<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\WithholdingIvaPurchases;
use Illuminate\Http\Request;

class WithholdingIvaPurchasesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:accounting-withholding-purchases-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:accounting-withholding-purchases-create|adm-create', ['only' => ['create', 'store', 'registerRetention']]);
        $this->middleware('permission:adm-edit|accounting-withholding-purchases-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|accounting-withholding-purchases-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $conf = [
            'title-section' => 'Retenciones IVA Compras',
            'group' => 'accounting-withholding-purchases',
        ];

        $data = WithholdingIvaPurchases::select('id_withholding_iva_purchase', 'voucher_number_whp', 'amount_tax_retention_whp', 'date_whp', 's.name_supplier')
            ->join('suppliers as s', 's.id_supplier', '=', 'withholding_iva_purchases.id_supplier')
            ->orderBy('date_whp', 'DESC')
            ->paginate(15);
            

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Razon Social', 'Fecha', 'Numero de comprobante', 'Monto retenido',],
            'w_ts' => ['3', '', '', '', '',],
            'c_ths' =>
            [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
            ],
            'td_number' => [false, false, false, true],
            'tds' => ['name_supplier', 'date_whp', 'voucher_number_whp', 'amount_tax_retention_whp',],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'edit_modal' => false,
            'url' => "/accounting/withholding-purchases",
            'id' => 'id_withholding_iva_purchase',
            'data' => $data,
            'group' => 'accounting-withholding-purchases',
            'i' => (($request->input('page', 1) - 1) * 5),
        ];
        return view('accounting.withholding-purchases.index', compact('conf', 'table'));
    }


    public function registerRetention($arr)
    {

        //  return $arr['date_whp'];
        $voucher_control = WithholdingIvaPurchases::select('voucher_control_whp')
            ->orderBy('id_withholding_iva_purchase', 'DESC')->get();

        if (count($voucher_control) <= 0) {
            $ctrl = 1;
        } else {
            $ctrl = $voucher_control[0]->voucher_control_whp + 1;
        }

        //return $ctrl;
        $voucher_number_whp = date('Ymd') . str_pad($ctrl, 6, "0", STR_PAD_LEFT);

        // return $voucher_number_whp;

        $ret = new WithholdingIvaPurchases();

        $ret->voucher_number_whp = $voucher_number_whp;
        $ret->voucher_control_whp = $ctrl;
        $ret->type_wh = $arr['type_wh'];
        $ret->date_whp = $arr['date_whp'];
        $ret->amount_tax_invoice_whp = $arr['amount_tax_invoice_whp'];
        $ret->amount_base_imponible_whp = $arr['amount_base_imponible_whp'];
        $ret->amount_tax_retention_whp = $arr['amount_tax_retention_whp'];
        $ret->id_supplier = $arr['id_supplier'];
        $ret->id_purchase = $arr['id_purchase'];
        $ret->save();

        // return $re


    }



    public function show($id)
    {


        $data = WithholdingIvaPurchases::select(
            'withholding_iva_purchases.*',
            's.name_supplier',
            's.porcentual_amount_tax_supplier',
            'p.ref_name_purchase',
            \DB::raw('CASE 
                            WHEN type_wh = 1 THEN "Facutura Cliente"
                            ELSE "Factura proveedor"
                            END as tipo')
        )
            ->join('purchases as p', 'p.id_purchase', '=', 'withholding_iva_purchases.id_purchase')
            ->join('suppliers as s', 's.id_supplier', '=', 'withholding_iva_purchases.id_supplier')
            ->find($id);

        $conf = [
            'title-section' => 'Retención IVA Proveedor: ' . $data->voucher_number_whp,
            'group' => 'accounting-withholding-purchases',
            'back' => 'withholding-purchases.index',
            'edit' => ['route' => 'withholding-purchases.edit', 'id' => $data->id_withholding_iva_purchase],
        ];

        return view('accounting.withholding-purchases.show', compact('conf', 'data'));

        return $data;
    }
}
