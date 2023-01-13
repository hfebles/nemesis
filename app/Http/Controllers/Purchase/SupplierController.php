<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Conf\Country\Estados;
use App\Models\Purchase\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:purchase-supplier-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|purchase-supplier-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|purchase-supplier-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|purchase-supplier-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $conf = [
            'title-section' => 'Gestion de proveedores',
            'group' => 'sales-clients',
            'create' => ['route' =>'supplier.create', 'name' => 'Nuevo proveedor'],
            'url' => '/purchase/supplier/create'
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
                
            'tds' => ['idcard_supplier', 'name_supplier', 'phone_supplier'],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'edit_modal' => false, 
            'url' => "/purchase/supplier",
            'id' => 'id_supplier',
            'data' => Supplier::where('enabled_supplier', '=', '1')->paginate(15),
            'i' => (($request->input('page', 1) - 1) * 15),
        ];

        return view('purchases.supplier.index', compact('conf', 'table'));
    }

    public function create(){
        $conf = [
            'title-section' => 'Crear un nuevo proveedor',
            'group' => 'purchase-supplier',
            'back' => 'supplier.index',
            'url' => '/purchase/supplier'
        ];

        $estados = Estados::pluck('estado', 'id_estado');
        return view('purchases.supplier.create', compact('conf', 'estados'));
    }

    public function store(Request $request){

        $data = $request->except('_token');

        $save = new Supplier();
        $save->name_supplier = strtoupper($data['name_supplier']);
        $save->idcard_supplier = strtoupper($data['letra']).$data['idcard_supplier'];
        $save->address_supplier = strtoupper($data['address_supplier']);
        $save->id_state = $data['id_state'];

        if(isset($data['phone_supplier'])){
            $save->phone_supplier = $data['phone_supplier'];
        }
        if(isset($data['email_supplier'])){
            $save->email_supplier = strtoupper($data['email_supplier']);
        }
        if(isset($data['zip_supplier'])){
            $save->zip_supplier = $data['zip_supplier'];
        }
        if(isset($data['taxpayer_supplier'])){
            $save->taxpayer_supplier = $data['taxpayer_supplier'];
        }

        $save->save();

        $message = [
            'type' => 'success',
            'message' => 'El proveedor, se registro con éxito',
        ];

        return redirect()->route('supplier.index')->with('message', $message);
    }

    public function show($id){

        $getClient = Supplier::whereIdSupplier($id)->whereEnabledSupplier(1)->get()[0];
        $getState = Estados::whereIdEstado($getClient->id_state)->get()[0]->estado;

        $conf = [
            'title-section' => 'Datos del proveedor: '.$getClient->name_supplier,
            'group' => 'purchase-supplier',
            'back' => 'supplier.index',
            'edit' => ['route' => 'supplier.edit', 'id' => $getClient->id_supplier],
            'url' => '/purchase/supplier',
            'delete' => ['name' => 'Eliminar proveedor']
        ];

        return view('purchases.supplier.show', compact('conf', 'getClient', 'getState'));
    }

    public function edit($id){
        $supplier = Supplier::whereIdSupplier($id)->whereEnabledSupplier(1)->get()[0];
        $estados = Estados::pluck('estado', 'id_estado');
        $letra = substr($supplier->idcard_supplier, 0, 1);
        $numero = substr($supplier->idcard_supplier, 1);
        $supplier->idcard_supplier = $numero;

        $conf = [
            'title-section' => 'Editar proveedor: '.$supplier->name_supplier,
            'group' => 'purchase-supplier',
            'back' => ['route' => "./", 'show' => true],
            'url' => '/purchase/supplier',
        ];

        return view('purchases.supplier.edit', compact('conf', 'letra', 'supplier', 'estados'));
    }

    public function update(Request $request, $id){

        $data = $request->except('_token', '_method', 'letra');
        $data['name_supplier'] = strtoupper($data['name_supplier']);
        $data['idcard_supplier'] = strtoupper($request->letra).$data['idcard_supplier'];
        $data['address_supplier'] = strtoupper($data['address_supplier']);
        $data['id_state'] = $data['id_state'];

        if(isset($data['phone_supplier'])){
            $data['phone_supplier'] = $data['phone_supplier'];
        }
        if(isset($data['email_supplier'])){
            $data['email_supplier'] = strtoupper($data['email_supplier']);
        }
        if(isset($data['zip_supplier'])){
            $data['zip_supplier'] = $data['zip_supplier'];
        }
        if(isset($data['taxpayer_supplier'])){
            $data['taxpayer_supplier'] = $data['taxpayer_supplier'];
        }
        if(isset($data['porcentual_amount_tax_supplier'])){
            $data['porcentual_amount_tax_supplier'] = $data['porcentual_amount_tax_supplier'];
        }
     
        Supplier::whereIdSupplier($id)->update($data);
        $message = [
            'type' => 'warning',
            'message' => 'El proveedor, se actualizo con éxito',
        ];
        return redirect()->route('supplier.index')->with('message', $message);


        
    }

    public function destroy($id)
    {
        Supplier::whereIdSupplier($id)->update(['enabled_supplier' => 0]);
        $message = [
            'type' => 'danger',
            'message' => 'El proveedor, se elimino con éxito',
        ];
        return redirect()->route('supplier.index')->with('message', $message);
    }






    function searchProveedor(Request $request){
        $data = Supplier::whereIdcardSupplier($request->text)->get();
        if(count($data) > 0 ){
            return response()->json(['res' => false, 'msg' => 'El DNI ó RIF ya fueregistrado']);
        }else{
            return response()->json(['res' => true, 'msg' => 'El DNI ó RIF es valido']);
        }
        return $data;
    }


    public function search(Request $request){
        $data = \DB::select('SELECT id_supplier, phone_supplier, name_supplier, idcard_supplier, address_supplier 
                            FROM clients 
                            WHERE name_supplier LIKE "%'.$request->text.'%" 
                            OR idcard_supplier LIKE "%'.$request->text.'%"
                            AND enabled_supplier = 1');
        return response()->json(['lista' => $data]);

    }
}
