<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\WithholdingIvaSales;
use Illuminate\Http\Request;

class WithholdingIvaSalesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:accounting-withholding-sales-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:accounting-withholding-sales-create|adm-create', ['only' => ['create', 'store', 'registerRetention']]);
        $this->middleware('permission:adm-edit|accounting-withholding-sales-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|accounting-withholding-sales-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return "holis";
    }



    public function show($id)
    {


        $data = WithholdingIvaSales::select(
            'withholding_iva_sales.*',
            'c.name_client',
            'c.porcentual_amount_tax_client',
            'i.ref_name_invoicing',
            \DB::raw('CASE 
                            WHEN type_wh = 1 THEN "Facutura Cliente"
                            ELSE "Factura proveedor"
                            END as tipo')
        )
            ->join('invoicings as i', 'i.id_invoicing', '=', 'withholding_iva_sales.id_invoice')
            ->join('clients as c', 'c.id_client', '=', 'withholding_iva_sales.id_client')
            ->find($id);

           // return $data;

        $conf = [
            'title-section' => 'Retención IVA Proveedor: ' . $data->voucher_number_whs,
            'group' => 'accounting-withholding-purchases',
            'back' => 'withholding-purchases.index',
            'edit' => ['route' => 'withholding-sales.edit', 'id' => $data->id_withholding_iva_sale],
        ];

        return view('accounting.withholding-sales.show', compact('conf', 'data'));

        //return $data;
    }


    public function edit($id){

        $data = WithholdingIvaSales::find($id);

        $conf = [
            'title-section' => 'Retención IVA Proveedor: ',
            'group' => 'accounting-withholding-purchases',
            'back' => 'withholding-purchases.index',
        ];

        return view('accounting.withholding-sales.edit', compact('data', 'conf'));
    }




    public function registerRetention($arr)
    {


        $ret = new WithholdingIvaSales();
        $ret->type_wh = $arr['type_wh'];
        $ret->date_whs = $arr['date_whs'];
        $ret->amount_tax_invoice_whs = $arr['amount_tax_invoice_whs'];
        $ret->amount_base_imponible_whs = $arr['amount_base_imponible_whs'];
        $ret->amount_tax_retention_whs = $arr['amount_tax_retention_whs'];
        $ret->id_client = $arr['id_client'];
        $ret->id_invoice  = $arr['id_invoice'];
        $ret->save();
    }


    public function update(Request $request, $id)
    {
        $route = app('router')->getRoutes(url()->previous())->match(app('request')->create(url()->previous()))->getName();
        
        $data = WithholdingIvaSales::find($id);
        $data->voucher_number_whs = $request->voucher_number_whs;
        $data->save();


        if ($route == 'withholding-sales.edit'){
            return redirect()->route('withholding-sales.show', $id);
        }else{
            return back();
        }

        
    }
}
