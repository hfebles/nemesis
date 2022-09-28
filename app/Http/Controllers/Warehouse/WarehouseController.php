<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Warehouse\Warehouse;
use App\Models\Products\Product;

class WarehouseController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:warehouse-warehouse-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|warehouse-warehouse-create', ['only' => ['create','store']]);
        $this->middleware('permission:adm-edit|warehouse-warehouse-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:adm-delete|warehouse-warehouse-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $conf = [
            'title-section' => 'Gestion de Almacenes',
            'group' => 'warehouse-warehouse',
            'create' => ['route' =>'warehouse.create', 'name' => 'Nuevo almacen', 'btn_type' => 'side'],
            'url' => '/warehouse/warehouse/create'
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Código', 'Almacen',],
            'w_ts' => ['3','10', '',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle', 
                'align-middle',],
                
            'tds' => ['code_warehouse', 'name_warehouse',],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'edit_modal' => false, 
            'url' => "/warehouse/warehouse",
            'id' => 'id_warehouse',
            'data' => Warehouse::where('enabled_warehouse', '=', '1')->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('warehouse.warehouse.index', compact('conf', 'table'));
    }

    public function store(Request $request){
        
        $data = $request->except('token');

        $save = new Warehouse();
        $save->name_warehouse = strtoupper($data['name_warehouse']);
        $save->code_warehouse = strtoupper($data['code_warehouse']);
        $save->save();

        $message = [
            'type' => 'success',
            'message' => 'Se registro con éxito',
        ];
                        
        return redirect()->route('warehouse.index')->with('message', $message);

    }

    public function show(Request $request, $id){

        $getDataWarehouse = Warehouse::whereEnabledWarehouse(1)->whereIdWarehouse($id)->get()[0];


        $conf = [
            'title-section' => 'Almacen: '.$getDataWarehouse->code_warehouse.'/'.$getDataWarehouse->name_warehouse,
            'group' => 'warehouse-warehouse',
            'back' => 'warehouse.index',
            'edit' => ['route' => 'warehouse.edit', 'id' => $getDataWarehouse->id_warehouse],
            'create' => '/products/product/create',
            'url' => '/warehouse/warehouse/create',
            'delete' => ['name' => 'Eliminar Almacen']
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Código', 'Producto', 'Disponible', 'Precio'],
            'w_ts' => ['3','8', '60', '8', '8'],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle', 
                'align-middle',
                'text-center align-middle', 
                'text-center align-middle', ],
                
            'tds' => ['code_product', 'name_product', 'qty_product', 'price_product'],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'edit_modal' => false, 
            'url' => "/products/product",
            'id' => 'id_product',
            'data' => Product::whereIdWarehouse($id)->whereEnabledProduct(1)->paginate(15),
            'i' => (($request->input('page', 1) - 1) * 5),
            'group' => 'product-product',
        ];


        return view('warehouse.warehouse.show', compact('conf', 'getDataWarehouse', 'table'));



    }

    public function edit($id){
        
        $warehouse = Warehouse::whereIdWarehouse($id)->get()[0];


        $conf = [
            'title-section' => 'Editar almacen: '.$warehouse->name_warehouse,
            'group' => 'sales-clients',
            'back' => ['route' => "./", 'show' => true],
            'url' => '/sales/clients',
            
        ];


        return view('warehouse.warehouse.edit', compact('conf', 'warehouse'));
    }

    public function update(Request $request, $id){

        $data = $request->except('_token', '_method');
        Warehouse::whereIdWarehouse($id)->update($data);
        $message = [
            'type' => 'success',
            'message' => 'Se edito con éxito',
        ];
                        
        return redirect()->route('warehouse.show', $id)->with('message', $message);
    }

    public function destroy($id){
        $verifyProducts = Product::select('id_product')->whereIdWarehouse($id)->get();

        if(count($verifyProducts) > 0){
            Product::whereIn('id_product', $verifyProducts)->update(['id_warehouse' => 0]);
            Warehouse::whereIdWarehouse($id)->update(['enabled_warehouse' => 0]);
        }else{
            Warehouse::whereIdWarehouse($id)->update(['enabled_warehouse' => 0]);


        }

        $message = [
            'type' => 'success',
            'message' => 'Se elimino con éxito',
        ];
                        
        return redirect()->route('warehouse.index')->with('message', $message);
    }


}
