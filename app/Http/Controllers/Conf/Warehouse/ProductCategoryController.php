<?php

namespace App\Http\Controllers\Conf\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Conf\Warehouse\ProductCategory;

class ProductCategoryController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:product-category-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|product-category-create', ['only' => ['create','store']]);
        $this->middleware('permission:adm-edit|product-category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:adm-delete|product-category-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request){

        $conf = [
            'title-section' => 'Categoria de productos',
            'group' => 'product-category',
            'create' => ['route' =>'category.create', 'name' => 'Nuevo almacen', 'btn_type' => 2],
            'url' => '/mantenice/product/category'
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Nombre de la categoría',],
            'w_ts' => ['3','10',],
            'c_ths' => 
                [
                'text-center align-middle', 
                'align-middle',],
                
            'tds' => ['name_product_category',],
            'switch' => false,
            'edit' => true, 
            'show' => false,
            'edit_modal' => false,
            'url' => "/mantenice/product/category",
            'id' => 'id_product_category',
            'data' => ProductCategory::where('enabled_product_category', '=', '1')->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
            'group' => 'product-category',
        ];


        return view('warehouse.products.product_category.index', compact('table', 'conf'));
    }

    public function store(Request $request){
        
        $data = $request->except('token');

        $save = new ProductCategory();
        $save->name_product_category = strtoupper($data['name_product_category']);
        $save->save();


        $message = [
            'type' => 'success',
            'message' => 'La categoría '.$save->name_product_category.' se registró con éxito',
        ];

        return redirect()->route('category.index')->with('message', $message);
        
    }

    public function edit($id){
        $getData = ProductCategory::whereIdProductCategory($id)->get()[0];

        //return $getData;
        $conf = [
            'title-section' => 'Editar categoría: '.$getData->name_product_category,
            'group' => 'product-category',
            'back' => 'category.index',
            'url' => '/mantenice/product/category'
        ];
        

        return view('warehouse.products.product_category.edit', compact('conf', 'getData'));
    }


    public function update(Request $request, $id){

        $data = $request->except('_token', '_method');

        $data['name_product_category'] = strtoupper($data['name_product_category']);
        ProductCategory::whereIdProductCategory($id)->update($data);

        $message = [
            'type' => 'warning',
            'message' => 'La categoría '.$data['name_product_category'].' se edito con éxito',
        ];
        
        return redirect()->route('category.index')->with('message', $message);

        //return $request;
    }


}
