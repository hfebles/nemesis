<?php

namespace App\Http\Controllers\Conf\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Conf\Warehouse\PresentationProduct;

class PresentationProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-presentation-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|product-presentation-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|product-presentation-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|product-presentation-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $conf = [
            'title-section' => 'Presentaciones de los productos',
            'group' => 'product-presentation',
            'create' => ['route' => 'presentation.create', 'name' => 'Nueva presentación', 'btn_type' => 2],
            'url' => '/mantenice/product/presentation'
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Nombre de la presentación',],
            'w_ts' => ['3', '80',],
            'c_ths' =>
            [
                'text-center align-middle',
                'align-middle',
            ],
            'tds' => ['name_presentation_product',],
            'switch' => false,
            'edit' => true,
            'show' => false,
            'edit_modal' => false,
            'group' => 'product-presentation',
            'url' => "/mantenice/product/presentation",
            'id' => 'id_presentation_product',
            'data' => PresentationProduct::where('enabled_presentation_product', '=', '1')->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];


        return view('warehouse.products.product_presentation.index', compact('table', 'conf'));
    }

    public function store(Request $request)
    {
        $data = $request->except('token');
        $save = new PresentationProduct();
        $save->name_presentation_product = strtoupper($data['name_presentation_product']);
        $save->save();
        return redirect()->route('presentation.index')->with('message', 'La nueva presentación ' . $save->name_presentation_product . ' se registró con exito');
    }

    public function edit($id)
    {
        $getData = PresentationProduct::whereIdPresentationProduct($id)->get()[0];

        //return $getData;
        $conf = [
            'title-section' => 'Editar la presentacioón: ' . $getData->name_presentation_product,
            'group' => 'product-presentation',
            'back' => 'presentation.index',
            'url' => '/mantenice/product/presentation'
        ];


        return view('warehouse.products.product_presentation.edit', compact('conf', 'getData'));
    }


    public function update(Request $request, $id)
    {

        $data = $request->except('_token', '_method');
        $data['name_presentation_product'] = strtoupper($data['name_presentation_product']);
        PresentationProduct::whereIdPresentationProduct($id)->update($data);
        return redirect()->route('presentation.index')->with('message', 'La presentación ' . $data['name_presentation_product'] . ' se a editado con exito');
    }
}
