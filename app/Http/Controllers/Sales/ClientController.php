<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sales\Client;
use App\Models\Conf\Country\Estados;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:sales-clients-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|sales-clients-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|sales-clients-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|sales-clients-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $conf = [
            'title-section' => 'Gestion de clientes',
            'group' => 'sales-clients',
            'create' => ['route' =>'clients.create', 'name' => 'Nuevo cliente'],
            'url' => '/sales/clients/create'
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'DNI / RIF', 'Nombre o Razón social', 'Telefono'],
            'w_ts' => ['3','10', '50', '10',],
            'c_ths' => 
                [
                'text-center align-middle p-1',
                'text-center align-middle p-1', 
                'align-middle p-1', 
                'text-center align-middle p-1',],
                
            'tds' => ['idcard_client', 'name_client', 'phone_client'],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'edit_modal' => false, 
            'url' => "/sales/clients",
            'id' => 'id_client',
            'data' => Client::where('enabled_client', '=', '1')->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('sales.clients.index', compact('conf', 'table'));
    }

    public function create(){
        $conf = [
            'title-section' => 'Crear un nuevo cliente',
            'group' => 'sales-clients',
            'back' => 'clients.index',
            'url' => '/sales/clients'
        ];

        $estados = Estados::pluck('estado', 'id_estado');
        return view('sales.clients.create', compact('conf', 'estados'));
    }

    public function store(Request $request){

        $data = $request->except('_token');

        $save = new Client();
        $save->name_client = strtoupper($data['name_client']);
        $save->idcard_client = strtoupper($data['letra']).$data['idcard_client'];
        $save->address_client = strtoupper($data['address_client']);
        $save->id_state = $data['id_state'];

        if(isset($data['phone_client'])){
            $save->phone_client = $data['phone_client'];
        }
        if(isset($data['email_client'])){
            $save->email_client = strtoupper($data['email_client']);
        }
        if(isset($data['zip_client'])){
            $save->zip_client = $data['zip_client'];
        }
        if(isset($data['taxpayer_client'])){
            $save->taxpayer_client = $data['taxpayer_client'];
        }

        $save->save();

        $message = [
            'type' => 'success',
            'message' => 'El cliente, se registro con éxito',
        ];

        return redirect()->route('clients.index')->with('message', $message);
    }

    public function show($id){

        $getClient = Client::whereIdClient($id)->whereEnabledClient(1)->get()[0];
        $getState = Estados::whereIdEstado($getClient->id_state)->get()[0]->estado;

        $conf = [
            'title-section' => 'Datos del cliente: '.$getClient->name_client,
            'group' => 'sales-clients',
            'back' => 'clients.index',
            'edit' => ['route' => 'clients.edit', 'id' => $getClient->id_client],
            'url' => '/sales/clients',
            'delete' => ['name' => 'Eliminar cliente']
        ];

        return view('sales.clients.show', compact('conf', 'getClient', 'getState'));
    }

    public function edit($id){
        $client = Client::whereIdClient($id)->whereEnabledClient(1)->get()[0];
        $estados = Estados::pluck('estado', 'id_estado');
        $letra = substr($client->idcard_client, 0, 1);
        $numero = substr($client->idcard_client, 1);
        $client->idcard_client = $numero;

        $conf = [
            'title-section' => 'Editar cliente: '.$client->name_client,
            'group' => 'sales-clients',
            'back' => ['route' => "./", 'show' => true],
            'url' => '/sales/clients',
        ];

        return view('sales.clients.edit', compact('conf', 'letra', 'client', 'estados'));
    }

    public function update(Request $request, $id){

        $data = $request->except('_token', '_method', 'letra');
        $data['name_client'] = strtoupper($data['name_client']);
        $data['idcard_client'] = strtoupper($request->letra).$data['idcard_client'];
        $data['address_client'] = strtoupper($data['address_client']);
        $data['id_state'] = $data['id_state'];

        if(isset($data['phone_client'])){
            $data['phone_client'] = $data['phone_client'];
        }
        if(isset($data['email_client'])){
            $data['email_client'] = strtoupper($data['email_client']);
        }
        if(isset($data['zip_client'])){
            $data['zip_client'] = $data['zip_client'];
        }
        if(isset($data['taxpayer_client'])){
            $data['taxpayer_client'] = $data['taxpayer_client'];
        }
        if(isset($data['porcentual_amount_tax_client'])){
            $data['porcentual_amount_tax_client'] = $data['porcentual_amount_tax_client'];
        }
     
        Client::whereIdClient($id)->update($data);
        //return isset($data['porcentual_amount_tax_client']);

        

        $message = [
            'type' => 'warning',
            'message' => 'El cliente, se actualizo con éxito',
        ];

        
        

        return redirect()->route('clients.index')->with('message', $message);


        
    }
    
    public function destroy($id){

        $data = Client::whereIdClient($id)->update(
            ['enabled_client' => 0]
        );

        return redirect()->route('clients.index')->with('success','Usuario eliminado con exito');
    }






    function searchCliente(Request $request){
        $data = Client::whereIdcardClient($request->text)->get();
        if(count($data) > 0 ){
            return response()->json(['res' => false, 'msg' => 'El DNI ó RIF ya fueregistrado']);
        }else{
            return response()->json(['res' => true, 'msg' => 'El DNI ó RIF es valido']);
        }
        return $data;
    }


    public function search(Request $request){
        $data = DB::select('SELECT id_client, phone_client, name_client, idcard_client, address_client 
                            FROM clients 
                            WHERE name_client LIKE "%'.$request->text.'%" 
                            OR idcard_client LIKE "%'.$request->text.'%"
                            AND enabled_client = 1');
        return response()->json(['lista' => $data]);

    }
}
