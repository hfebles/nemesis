<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Accounting\LedgerAccount;


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


       // return LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->paginate(10)
        
 
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
                
            'tds' => ['code_ledger_account', 'name_ledger_account',],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,  
            'show' => true,
            'url' => '/accounting/ledger-account',
            'id' => 'id_ledger_account',
            'data' => LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 10),
        ];

        

        return view('accounting.ledger-account.index', compact('conf', 'table'));
        //return $table;

      
    }


    public function show(Request $request, $id){



        
        $data = LedgerAccount::where('id_ledger_account', '=', $id)->get()[0];

       
        
        $table = [
            'c_table' => 'table table-sm table-bordered table-hover mb-0 ',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Codigo', 'Grupo', ],
            'w_ts' => ['3','7', '90',],
            'c_ths' => 
                [
                'text-start align-middle p-1',
                'text-start align-middle p-1', 
                'align-middle', ],
                
            'tds' => ['code_ledger_account', 'name_ledger_account',],
            'switch' => false,
            'edit' => true,
            'edit_modal' => false,  
            'group' => 'accounting-ledger',
            'show' => false,
            'url' => '/accounting/ledger-account',
            'id' => 'id_ledger_account',
            'data' => LedgerAccount::where('id_type_ledger_account', '=', $data->id_type_ledger_account)->paginate(20),
            'i' => (($request->input('page', 1) - 1) * 20),
        ];

            

            $conf = [
                'title-section' => 'Grupo contable: '.$data->name_ledger_account,
                'group' => 'accounting-ledger',
                'back' => 'ledger-account.index',
            ];
            

            
            
           
            return view('accounting.ledger-account.show', compact('conf', 'table'));
            
    }


    public function store(Request $request){

        $data = $request->except('_token');

        $save = new LedgerAccount();

        $save->id_sub_group = $data['id_sub_group'];
        $save->code_ledger_account = $data['code_ledger_account'];
        $save->name_ledger_account = strtoupper($data['name_ledger_account']);
        $save->save();

        

        $message = [
            'type' => 'success',
            'message' => 'Se registro con éxito',
        ];           

        return redirect()->back()->with('message', $message);


    }

    public function edit($id){

        $conf = [
            'title-section' => 'Cuenta:',
            'group' => 'accounting-ledger',
            'back' => 'ledger-account.index',
        ];

        
        $data = LedgerAccount::where('id_ledger_account', '=', $id)->get()[0];

        $typeLedger = LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->pluck('name_ledger_account', 'id_type_ledger_account');
        
        
        return view('accounting.ledger-account.edit', compact('data', 'conf', 'typeLedger'));
    }

    public function update(Request $request, $id){

        $data = $request->except('_method', '_token');

        LedgerAccount::where('id_ledger_account', '=', $id)->update(['id_type_ledger_account' => $data['id_type_ledger_account'], 'name_ledger_account' => $data['name_ledger_account'], 'code_ledger_account' => $data['code_ledger_account']]);
        // return $request;

        $message = [
            'type' => 'success',
            'message' => 'Edicion realizada con exito',
        ];

      //  return  $data['id_type_ledger_account'];

        return redirect()->route('ledger-account.show', $data['id_type_ledger_account'])->with('message', $message);
    }




    public function searchLedgers(Request $request){

        $data = LedgerAccount::where('id_type_ledger_account', '=', $request->type)->get();
        return $data;
    }


    
}
