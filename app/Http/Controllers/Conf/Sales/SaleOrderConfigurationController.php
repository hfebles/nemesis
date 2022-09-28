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




        $data = SaleOrderConfiguration::all();

        $conf = [
            'title-section' => 'Configuración de los pedidos de venta',
            'group' => 'sales-order-conf',
            'edit' => ['route' => 'order-config.edit', 'id' => $data[0]->id_sale_order_configuration,],

        ];

        //return $data;


        return view('conf.sales.sales-order-conf.index', compact('conf', 'data'));
    }

    public function edit($id)
    {

        $data = SaleOrderConfiguration::whereIdSaleOrderConfiguration($id)->get();
        $dataSubAcc = SubLedgerAccount::where('enabled_sub_ledger_account', '=', '1')->get();
        $l = LedgerAccount::get();

        //return $dataSubAcc;
        //return $data;

        $conf = [
            'title-section' => 'Configuración de los pedidos de venta',
            'group' => 'sales-order-conf',
            'back' => 'order-config.index',

        ];

        /* $dataxx = \DB::select("WITH g as (select * from groups),
                sg as (SELECT * from sub_groups where sub_groups.id_group in (select id_group from groups) ),
                l as (SELECT * from ledger_accounts),
                sl as (SELECT * from sub_ledger_accounts),
                sl2 as (SELECT * from sub_ledger_accounts2s),
                sl3 as (SELECT * from sub_ledger_accounts3s),
                sl4 as (SELECT * from sub_ledger_accounts4s)
                SELECT
                    concat(g.code_group, ' - ', g.name_group) as g,
                    concat(g.code_group, '.', sg.code_sub_group, ' - ', sg.name_sub_group) as sg,
                    concat(g.code_group, '.', sg.code_sub_group,'.', l.code_ledger_account,' - ', l.name_ledger_account) as l,
                    concat(g.code_group, '.', sg.code_sub_group,'.', l.code_ledger_account,'.', sl.code_sub_ledger_account,' - ', sl.name_sub_ledger_account) as sl,
                    concat(g.code_group, '.', sg.code_sub_group,'.', l.code_ledger_account,'.', sl.code_sub_ledger_account,'.', sl2.code_sub_ledger_account2,' - ', sl2.name_sub_ledger_account2) as sl2,
                    concat(g.code_group, '.', sg.code_sub_group,'.', l.code_ledger_account,'.', sl.code_sub_ledger_account,'.', sl2.code_sub_ledger_account2,'.', sl3.code_sub_ledger_account3,' - ', sl3.name_sub_ledger_account3) as sl3,
                    concat(g.code_group, '.', sg.code_sub_group,'.', l.code_ledger_account,'.', sl.code_sub_ledger_account,'.', sl2.code_sub_ledger_account2,'.', sl3.code_sub_ledger_account3,'.', sl4.code_sub_ledger_account4,' - ', sl4.name_sub_ledger_account4) as sl4
                from g
                INNER JOIN sg on sg.id_group = g.id_group
                INNER JOIN l on l.id_sub_group = sg.id_sub_group
                LEFT OUTER JOIN sl on sl.id_ledger_account = l.id_ledger_account
                LEFT OUTER JOIN sl2 on sl2.id_sub_ledger_account = sl.id_sub_ledger_account
                LEFT OUTER JOIN sl3 on sl3.id_sub_ledger_account2 = sl2.id_sub_ledger_account2
                LEFT OUTER JOIN sl4 on sl4.id_sub_ledger_account3 = sl3.id_sub_ledger_account3;
        ");*/



        // return $dataxx;

        //return $data;

        $data2 = \DB::select('select g.code_group, g.name_group, sg.code_sub_group, sg.name_sub_group, c.code_ledger_account, c.name_ledger_account from ledger_accounts as c
                            INNER JOIN sub_groups as sg on sg.id_sub_group = c.id_sub_group
                            INNER JOIN groups as g on g.id_group = sg.id_group
                            GROUP BY g.id_group, sg.id_sub_group, c.id_ledger_account, g.code_group, g.name_group, sg.code_sub_group, sg.name_sub_group, c.code_ledger_account, c.name_ledger_account');

        $dataG = Group::get();
        $dataSG = SubGroup::where('id_group', '=', $dataG[0]->id_group)->get();

        $sg = [];
        $dataLA = [];
        $dataSLA = [];
        $s2 = [];
        $s3 = [];
        $s4 = [];

        
        

        //return $dataSG;
        

        for ($aa = 0; $aa < count($dataG); $aa++) {
            $sg[$aa] = SubGroup::where('id_group', '=', $dataG[$aa]->id_group)->get();
           // echo $aa;

            //echo $dataG[$aa]->id_group;

            for ($i = 0; $i < count($sg[$aa]); $i++) {
               $dataLA[$aa][$i] = LedgerAccount::where('id_sub_group', '=', $sg[$aa][$i]->id_sub_group)->get();
               //echo $sg[$aa][$i]->id_sub_group."<br>";

                for ($a = 0; $a < count($dataLA[$aa][$i]); $a++) {
                    $dataSLA[$aa][$i][$a] = SubLedgerAccount::where('id_ledger_account', '=', $dataLA[$aa][$i][$a]->id_ledger_account)->get();

                    for ($b = 0; $b < count($dataSLA[$aa][$i][$a]); $b++) {
                        $s2[$aa][$i][$a][$b] = SubLedgerAccounts2::where('id_sub_ledger_account', '=', $dataSLA[$aa][$i][$a][$b]->id_sub_ledger_account)->get();

                        for ($c = 0; $c < count($s2[$aa][$i][$a][$b]); $c++) {
                            $s3[$aa][$i][$a][$b][$c] = SubLedgerAccounts3::where('id_sub_ledger_account2', '=', $s2[$aa][$i][$a][$b][$c]->id_sub_ledger_account2)->get();

                            for ($f = 0; $f < count($s3[$aa][$i][$a][$b][$c]); $f++) {
                                $s4[$aa][$i][$a][$b][$c][$f] = SubLedgerAccounts4::where('id_sub_ledger_account3', '=', $s3[$aa][$i][$a][$b][$c][$f]->id_sub_ledger_account3)->get();
                            }
                        }
                    }
                }
            }
        }

    //    return $dataLA;
    //     return '...';

        return view('conf.sales.sales-order-conf.edit', compact('conf', 'data', 'dataG',  'dataLA', 'dataSLA', 's2', 's3', 's4', 'sg'));
    }


    public function update(Request $request, $id)
    {

        //return $request;


        $data = $request->except('_token', '_method');
        SaleOrderConfiguration::whereIdSaleOrderConfiguration($id)->update($data);
        return redirect()->route('order-config.index');
    }
}
