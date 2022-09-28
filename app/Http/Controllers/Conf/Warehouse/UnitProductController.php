<?php

namespace App\Http\Controllers\Conf\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Conf\Warehouse\UnitProduct;

class UnitProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-unit-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|product-unit-create', ['only' => ['create','store']]);
        $this->middleware('permission:adm-edit|product-unit-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:adm-delete|product-unit-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $conf = [
            'title-section' => 'Unidades de productos',
            'group' => 'product-unit',
            'create' => ['route' =>'unit.create', 'name' => 'Nueva unidad', 'btn_type' => 2],
            'url' => '/mantenice/product/unit'
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Abreviación', 'Nombre de la categoría',],
            'w_ts' => ['3','10','80',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',
                'align-middle',],
            'tds' => ['short_unit_product', 'name_unit_product',],
            'switch' => false,
            'edit' => true, 
            'show' => false,
            'edit_modal' => false,
            'url' => "/mantenice/product/unit",
            'id' => 'id_unit_product',
            'data' => UnitProduct::where('enabled_unit_product', '=', '1')->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
            'group' => 'product-unit',
        ];


        return view('warehouse.products.product_unit.index', compact('table', 'conf'));
    }

    public function store(Request $request){
        
        $data = $request->except('token');

        $save = new UnitProduct();
        $save->name_unit_product = strtoupper($data['name_unit_product']);
        $save->short_unit_product = strtoupper($data['short_unit_product']);
        $save->save();


        $message = [
            'type' => 'warning',
            'message' => 'La nueva unidad '.$save->name_unit_product.' se registró con exito',
        ];
        
        return redirect()->route('unit.index')->with('message', $message);
    }

    public function edit($id){
        $getData = UnitProduct::whereIdUnitProduct($id)->get()[0];

        //return $getData;
        $conf = [
            'title-section' => 'Editar la unidad: '.$getData->name_unit_product,
            'group' => 'product-unit',
            'back' => 'unit.index',
            'url' => '/mantenice/product/unit'
        ];
        

        return view('warehouse.products.product_unit.edit', compact('conf', 'getData'));
    }


    public function update(Request $request, $id){

        $data = $request->except('_token', '_method');

        $data['name_unit_product'] = strtoupper($data['name_unit_product']);
        $data['short_unit_product'] = strtoupper($data['short_unit_product']);
        UnitProduct::whereIdUnitProduct($id)->update($data);

        $message = [
            'type' => 'warning',
            'message' => 'Unidad '.$data['name_unit_product'].' editado con exito',
        ];
        
        return redirect()->route('unit.index')->with('message', $message);
    }
}
