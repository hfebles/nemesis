<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Conf\Warehouse\PresentationProduct;
use App\Models\Conf\Warehouse\UnitProduct;
use App\Models\Production\MaterialsList;
use App\Models\Production\MaterialsListDetails;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class MaterialsListController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:production-material-list-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create| production-material-list-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit| production-material-list-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete| production-material-list-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request){
        
        $conf = [
            'title-section' => 'Lista de materiales',
            'group' => 'production-material-list',
            'create' => ['route' =>'material-list.create', 'name' => 'Nueva lista', ],
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Producto', 'Cantidad', 'Unidad', ],
            'w_ts' => ['3','','','',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['name_materials_list', 'qty_product', 'name_unit_product',],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,  
            'show' => true,
            'url' => "/production/material-list",
            'id' => 'id_materials_list',
            'group' => '/production-material-list',
            'data' => MaterialsList::select('id_materials_list', 'name_materials_list', 'qty_product', 'name_unit_product')
                                ->join('products', 'products.id_product', '=', 'materials_lists.id_product')
                                ->join('unit_products', 'products.id_unit_product', '=', 'unit_products.id_unit_product', 'left outer')
                                ->where('salable_product', '=', 1)
                                ->where('enabled_materials_list', '=', 1)
                                ->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('productions.material_list.index', compact('conf', 'table'));

        
    }


    public function create(){

        $conf = [
            'title-section' => 'Crear una nueva lista de materiales',
            'group' => 'production-material-list',
            'back' => 'material-list.index',
            'url' => '#'
        ];

        $products = Product::where('salable_product', '=', 1)->pluck('name_product', 'id_product');
        $units = UnitProduct::pluck('name_unit_product', 'id_unit_product');
        $presentations = PresentationProduct::pluck('name_presentation_product', 'id_presentation_product');

        $productos = Product::where('salable_product', '=', 0)->pluck('name_product', 'id_product');
        $presentaciones = PresentationProduct::pluck('name_presentation_product', 'id_presentation_product');

       return view('productions.material_list.create', compact('conf', 'products', 'units', 'presentations', 'productos', 'presentaciones'));
    }




    public function store(Request $request){

        $data = $request->except('_token', 'id_product_details', 'qtys', 'id_presentation_details');
        $dataDetails = $request->except('_token', 'id_product', 'qty_materials_list', 'id_presentation_product');

        $saveList = new MaterialsList();
        
        $saveList->id_product = $data['id_product'];
        $saveList->name_materials_list = $data['name_materials_list'];
        $saveList->qty_materials_list = $data['qty_materials_list'];
        $saveList->id_presentation = $data['id_presentation_product'];
        $saveList->save();

        $saveDetails = new MaterialsListDetails();
        $saveDetails->id_materials_list = $saveList->id_materials_list;
        $saveDetails->details = json_encode($dataDetails);
        $saveDetails->save();

        



        $message = [
            'type' => 'danger',
            'message' => 'Se registro la lista de materiales con éxito',
        ];
                        
        return redirect()->route('material-list.index')->with('message', $message);
    }


    public function show($id){

        $data = MaterialsList::select('materials_lists.id_materials_list', 'name_product', 'qty_materials_list', 'name_presentation_product', 'details')
        ->join('products', 'products.id_product', '=', 'materials_lists.id_product')
        ->join('presentation_products', 'presentation_products.id_presentation_product', '=', 'materials_lists.id_presentation')
        ->join('materials_list_details', 'materials_list_details.id_materials_list', '=', 'materials_lists.id_materials_list')
        ->where('materials_lists.id_materials_list', '=', $id)
        ->get()[0];

        $objDetails = json_decode($data->details, true);
        
        $dataProductsDetails = [];
        $dataPresentationDetails = [];

        for ($i=0; $i < count($objDetails['id_product_details']); $i++) { 

            $dataProductsDetails[$i] = Product::select('name_product')
                                                ->where('id_product', '=', $objDetails['id_product_details'][$i])
                                                ->get();
            
            $dataPresentationDetails[$i] = PresentationProduct::select('name_presentation_product')
                                                                ->where('id_presentation_product', '=',  $objDetails['id_presentation_details'][$i])
                                                                ->get();
        }

        $conf = [
            'title-section' => 'Lista',
            'group' => 'production-material-list',
            'back' => 'material-list.index',
            'url' => '#',
            'edit' => ['route' => 'material-list.edit', 'id' => $data->id_materials_list],
        ];

        return view('productions.material_list.show', compact('data', 'dataProductsDetails', 'dataPresentationDetails', 'objDetails', 'conf'));
    }


    public function edit($id){

        $data = MaterialsList::select('materials_lists.id_materials_list', 'name_product', 'qty_materials_list', 'name_presentation_product', 'details')
        ->join('products', 'products.id_product', '=', 'materials_lists.id_product')
        ->join('presentation_products', 'presentation_products.id_presentation_product', '=', 'materials_lists.id_presentation')
        ->join('materials_list_details', 'materials_list_details.id_materials_list', '=', 'materials_lists.id_materials_list')
        ->where('materials_lists.id_materials_list', '=', $id)
        ->get()[0];

        $objDetails = json_decode($data->details, true);
        
        $dataProductsDetails = [];
        $dataPresentationDetails = [];

        for ($i=0; $i < count($objDetails['id_product_details']); $i++) { 

            $dataProductsDetails[$i] = Product::select('name_product')
                                                ->where('id_product', '=', $objDetails['id_product_details'][$i])
                                                ->get();

            $dataPresentationDetails[$i] = PresentationProduct::select('name_presentation_product')
                                                                ->where('id_presentation_product', '=',  $objDetails['id_presentation_details'][$i])
                                                                ->get();

        }

        $conf = [
            'title-section' => 'Lista',
            'group' => 'production-material-list',
            'back' => 'material-list.index',
            'url' => '#',
            'delete' => ['name' => 'Eliminar lista'],
        ];

        $products = Product::pluck('name_product', 'id_product');
        $units = UnitProduct::pluck('name_unit_product', 'id_unit_product');
        $presentations = PresentationProduct::pluck('name_presentation_product', 'id_presentation_product');

        $productos = Product::where('salable_product', '=', 0)->pluck('name_product', 'id_product');
        $presentaciones = PresentationProduct::pluck('name_presentation_product', 'id_presentation_product');
        
        return view('productions.material_list.edit', compact('data', 'products', 'presentations', 'dataProductsDetails', 'dataPresentationDetails', 'objDetails', 'conf', 'productos', 'presentaciones'));
    }


    public function update(Request $request, $id){

        $dataDetails = $request->except('_token', 'id_product', 'qty_materials_list', 'id_presentation_product');
        
        MaterialsListDetails::where('id_materials_list', '=', $id)
                                ->update([
                                    'details' => json_encode($dataDetails),
                                ]);


        $message = [
            'type' => 'success',
            'message' => 'Se registro la lista de materiales con éxito',
        ];
                        
        return redirect()->route('material-list.show', $id)->with('message', $message);

    }


    public function destroy($id){

        MaterialsList::where('id_materials_list', '=', $id)->update(['enabled_materials_list' => 0]);
        return redirect()->route('material-list.index');
    }

    
}
