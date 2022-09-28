<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use App\Models\Accounting\SubLedgerAccount;
use Illuminate\Http\Request;

use App\Models\Conf\Tax;


class TaxController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:taxes-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|taxes-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|taxes-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|taxes-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $conf = [
            'title-section' => 'Impuestos',
            'group' => 'taxes',
            'create' => ['route' =>'taxes.create', 'name' => 'Nuevo impuesto', 'btn_type' => 2,],
        ];

        $dataSubAcc = SubLedgerAccount::where('enabled_sub_ledger_account', '=', '1')->pluck('name_sub_ledger_account', 'id_sub_ledger_account');

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Nombre', 'Monto',],
            'w_ts' => ['3','','',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['name_tax', 'amount_tax',],
            'switch' => false,
            'edit' => false,
            'edit_modal' => true,  
            'show' => false,
            'url' => "/mantenice/taxes",
            'id' => 'id_tax',
            'group' => 'taxes',
            'data' => Tax::paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];
        return view('conf.taxes.index', compact('conf', 'table', 'dataSubAcc'));

    }

    public function store(Request $request){

        $data = $request->except('_token');

        $save = new Tax();
        
        $save->name_tax = strtoupper($data['name_tax']);
        $save->amount_tax = $data['amount_tax'];
        $save->id_sub_ledger_account = $data['id_sub_ledger_account'];
        
        if(isset($data['billable_tax'])){
            $save->billable_tax = $data['billable_tax'];
        }
        
        
        $save->save();


        $message = [
            'type' => 'success',
            'message' => 'Impuesto registrado con éxito',
        ];           

        return redirect()->route('taxes.index')->with('message', $message);

    }

    public function editModal(Request $request){
        
        $response =[
            'accs' => $dataSubAcc = SubLedgerAccount::where('enabled_sub_ledger_account', '=', '1')->get(),
            'data' => Tax::whereIdTax($request->id)->get()[0],
        ];
        return $response;
    }



    public function update(Request $request, $id){

        $data = $request->except('_token', '_method');
        $tax = Tax::whereIdTax($id);
        
       //return $data;
        
        if(isset($data['billable_tax'])){
            if ($data['billable_tax'] == 'on'){
                $data['billable_tax'] = 1;
            }
        }else{
            $data['billable_tax'] = 0;
        }
        
        $data['name_tax'] = strtoupper($data['name_tax']);

         if($data['id_sub_ledger_account'] == 'Seleccione'){
             $data['id_sub_ledger_account'] = null;
         }else{
            $data['id_sub_ledger_account'] = $data['id_sub_ledger_account'];
         }

        $tax->update($data);

        
        
        return redirect()->route('taxes.index')->with('success', 'Impuesto actualizado con exito');


        //return $save;
    }


}
