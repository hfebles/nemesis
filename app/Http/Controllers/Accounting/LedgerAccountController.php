<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Accounting\LedgerAccount;
use App\Models\Accounting\Group;
use App\Models\Accounting\SubGroup;
use App\Models\Accounting\SubLedgerAccount;
use App\Models\Accounting\SubLedgerAccounts2;
use App\Models\Accounting\SubLedgerAccounts3;
use App\Models\Accounting\SubLedgerAccounts4;
use App\Models\Accounting\TypeLedgerAccounts;

class LedgerAccountController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:accounting-ledger-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|accounting-ledger-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|accounting-ledger-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|accounting-ledger-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $conf = [
            'create' => ['route' =>'ledger-account.create', 'name' => 'Nueva cuenta', 'btn_type' => 2],
            'group' => 'accounting-ledger',
        ];
        

        $data = DB::table('groups')->paginate(10);
        
        $table = [
            'c_table' => 'table table-sm table-bordered table-hover mb-0 ',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Codigo', 'Grupo', ],
            'w_ts' => ['3','7', '90',],
            'c_ths' => 
                [
                'text-center align-middle p-1',
                'text-center align-middle p-1', 
                'align-middle', ],
                
            'tds1' => ['code_group', 'name_group',],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'url' => '/accounting/ledger-account',
            'id' => 'id_group',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        

        return view('accounting.ledger-account.index', compact('conf', 'table'));
        //return $table;

      
    }


    public function show($id){

        $conf = [
            'title-section' => 'Grupo contable',
            'group' => 'accounting-ledger',
            'back' => 'sales-order.index',
        ];

        $data = DB::select('select g.code_group, g.name_group, sg.code_sub_group, sg.name_sub_group, c.code_ledger_account, c.name_ledger_account from ledger_accounts as c
                            INNER JOIN sub_groups as sg on sg.id_sub_group = c.id_sub_group
                            INNER JOIN groups as g on g.id_group = sg.id_group
                            GROUP BY g.id_group, sg.id_sub_group, c.id_ledger_account, g.code_group, g.name_group, sg.code_sub_group, sg.name_sub_group, c.code_ledger_account, c.name_ledger_account');
            
            $dataG = Group::where('id_group', '=', $id)->get()[0];
            $dataSG = SubGroup::where('id_group', '=', $id)->get();
            $dataSGPluck = SubGroup::where('id_group', '=', $id)->pluck('name_sub_group', 'id_sub_group');
            
            $dataSGa = SubGroup::select('id_sub_group')->where('id_group', '=', $id)->get();
            
            $dataLAPluck = LedgerAccount::whereIn('id_sub_group', $dataSGa)->pluck('name_ledger_account', 'id_ledger_account');

        $s= SubLedgerAccount::pluck('name_sub_ledger_account', 'id_sub_ledger_account');
        $sp = SubLedgerAccount::select('id_sub_ledger_account')->get();
        
        $s2p = SubLedgerAccounts2::whereIn('id_sub_ledger_account', $sp)->pluck('name_sub_ledger_account2', 'id_sub_ledger_account2');
        
        $s2g =  SubLedgerAccounts2::select('id_sub_ledger_account2')->get();
        $s3p = SubLedgerAccounts2::whereIn('id_sub_ledger_account2', $s2g)->pluck('name_sub_ledger_account2', 'id_sub_ledger_account2');

        $s3g =  SubLedgerAccounts3::select('id_sub_ledger_account3')->get();
        $s4p = SubLedgerAccounts3::whereIn('id_sub_ledger_account3', $s3g)->pluck('name_sub_ledger_account3', 'id_sub_ledger_account3');

        //return $s3p;



            

            $dataType = TypeLedgerAccounts::where('enabled_type_ledger_account', '=', 1)->pluck('name_type_ledger_account', 'id_type_ledger_account');
            $dataLA = [];
            $dataSLA = [];
            $s2 = [];
            $s3 = [];
            $s4 = [];

            for($i = 0; $i < count($dataSG); $i++){
                $dataLA[$i] = LedgerAccount::where('id_sub_group', '=', $dataSG[$i]->id_sub_group)->get();

                for($a = 0; $a < count($dataLA[$i]); $a++){
                    $dataSLA[$i][$a] = SubLedgerAccount::where('id_ledger_account', '=', $dataLA[$i][$a]->id_ledger_account)->get();


                    for($b = 0; $b < count($dataSLA[$i][$a]); $b++){
                        $s2[$i][$a][$b] = SubLedgerAccounts2::where('id_sub_ledger_account', '=', $dataSLA[$i][$a][$b]->id_sub_ledger_account)->get();
                        //echo $dataSLA[$i][$a][$b]->id_sub_ledger_account;
                                                        
                        for($c = 0; $c < count($s2[$i][$a][$b]); $c++){
                            $s3[$i][$a][$b][$c] = SubLedgerAccounts3::where('id_sub_ledger_account2', '=', $s2[$i][$a][$b][$c]->id_sub_ledger_account2)->get();
                            // echo $s3[$i][$a][$b][$c]->id_sub_ledger_account;

                            for($f = 0; $f < count($s3[$i][$a][$b][$c]); $f++){
                                $s4[$i][$a][$b][$c][$f] = SubLedgerAccounts4::where('id_sub_ledger_account3', '=', $s3[$i][$a][$b][$c][$f]->id_sub_ledger_account3)->get();
                                // echo $s3[$i][$a][$b][$c]->id_sub_ledger_account;
                                                                
                            }
                                                            
                        }
                        
                        
                    }
                                                    
                }

            }

          //return $s4;


           

            

            $conf = [
                'title-section' => 'Grupo contable: '.$dataG->name_group,
                'group' => 'accounting-ledger',
                'back' => 'ledger-account.index',
            ];
            

            
            
           
            return view('accounting.ledger-account.show', compact('conf', 'dataG', 'dataSG', 'dataLA', 'dataSGPluck', 'dataLAPluck', 'dataSLA', 'dataType', 's', 's2', 's3p', 's3', 's4p', 's4'));
            
    }


    public function store(Request $request){

        $data = $request->except('_token');

        $save = new LedgerAccount();

        $save->id_sub_group = $data['id_sub_group'];
        $save->code_ledger_account = $data['code_ledger_account'];
        $save->name_ledger_account = strtoupper($data['name_ledger_account']);
        $save->save();

        

        $message = [
            'type' => 'danger',
            'message' => 'Se registro con éxito',
        ];           

        return redirect()->back()->with('message', $message);


    }


    
}
