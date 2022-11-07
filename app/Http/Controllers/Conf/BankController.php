<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use App\Models\Accounting\LedgerAccount;
use Illuminate\Http\Request;

use App\Models\Conf\Bank;


class BankController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:banks-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|banks-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|banks-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|banks-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $conf = [
            'title-section' => 'Bancos',
            'group' => 'banks',
            'create' => ['route' =>'banks.create', 'name' => 'Nuevo banco', 'btn_type' => 2,],
        ];

        

        $typeLedger = LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->pluck('name_ledger_account', 'id_type_ledger_account');

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Nombre',],
            'w_ts' => ['3','',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['name_bank',],
            'switch' => false,
            'edit' => false,
            'edit_modal' => true,  
            'show' => false,
            'url' => "/mantenice/banks",
            'id' => 'id_bank',
            'group' => 'banks',
            'data' => Bank::whereEnabledBank(1)->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];
        return view('conf.banks.index', compact('conf', 'table', 'typeLedger'));

    }

    public function store(Request $request){

        $data = $request->except('_token');

      //  return $data;
        $save = new Bank();
        
        $save->name_bank = strtoupper($data['name_bank']);
        $save->description_bank = strtoupper($data['description_bank']);
        $save->account_number_bank = $data['account_number_bank'];
        $save->id_ledger_account = $data['id_ledger_account'];
        $save->save();
        
        $message = [
            'type' => 'success',
            'message' => 'Banco registrado con éxito',
        ];
        
        return redirect()->route('banks.index')->with('message', $message);

    }

    public function editModal(Request $request){
        
        $typeLedger = LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->pluck('name_ledger_account', 'id_type_ledger_account');
        $data = Bank::select('name_bank', 'description_bank', 'account_number_bank', 'id_ledger_account')->whereIdBank($request->id)->get()[0];
        //return $data;

        $id_type = LedgerAccount::select('id_type_ledger_account')->where('id_ledger_account', '=', $data->id_ledger_account)->get()[0]->id_type_ledger_account;
        $dataBankLedgers = LedgerAccount::where('id_ledger_account', '=', $data->id_ledger_account)->get()[0];

      


       
        

        $response =[
            'data' => $data,
            'dataGroupLedgerBank' =>  LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->where('id_type_ledger_account', '=', $id_type)->get()[0],
            'dataTypeLedgerBank' => LedgerAccount::where('id_ledger_account', '=', $data->id_ledger_account)->get()[0],
            
            'groupLedgers' => LedgerAccount::whereRaw('LENGTH(code_ledger_account) <= 2')->pluck('name_ledger_account', 'id_type_ledger_account'),
            'typesLedgers' => LedgerAccount::where('id_type_ledger_account', '=', $id_type)->get()
        ];
        
        return $response;
    }



    public function update(Request $request, $id){

        
        $data = $request->except('_token', '_method');
        $bank = Bank::whereIdBank($id);

        //return $data;
        
        
        $data['name_bank'] = strtoupper($data['name_bank']);
        $data['description_bank'] = strtoupper($data['description_bank']);

        //  if($data['id_sub_ledger_account'] == 'Seleccione'){
        //      $data['id_sub_ledger_account'] = null;
        //  }else{
        //     $data['id_sub_ledger_account'] = $data['id_sub_ledger_account'];
        //  }

        $bank->update($data);

        
        $message = [
            'type' => 'warning',
            'message' => 'Banco actualizado con éxito',
        ];
        
        return redirect()->route('banks.index')->with('message', $message);
    }

}
