<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Conf\Warehouse\PresentationProduct;
use App\Models\Production\MaterialsList;
use App\Models\Production\MaterialsListDetails;
use App\Models\Production\ProductionOrder;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Svg\Tag\Rect;

class ProductionOrderController extends Controller
{
    public function index(Request $request)
    {

        $conf = [
            'title-section' => 'Ordenes de producción',
            'group' => 'production-order',
            'create' => ['route' => 'production-order.create', 'name' => 'Nueva orden de producción',],
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Nro', 'Nombre', 'Cantidad', 'Producto', 'Desde', 'Hasta', 'Estado'],
            'w_ts' => ['3', '', '', '', '', '', '', '',],
            'c_ths' =>
            [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
            ],
            'tds' => ['number_production_order', 'name_production_order', 'planned_qty_production_order', 'name_product', 'date_from_production_order', 'date_to_production_order', 'name_production_order_state',],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,
            'show' => true,
            'url' => "/production/production-order",
            'id' => 'id_production_order',
            'group' => 'production-order',
            'data' => ProductionOrder::select('id_production_order', 'production_orders.id_product', 'number_production_order', 'name_production_order', 'planned_qty_production_order', 'date_from_production_order', 'date_to_production_order', 'name_product', 'name_production_order_state',)
                ->join('products', 'products.id_product', '=', 'production_orders.id_product')
                ->join('production_order_states', 'production_order_states.id_production_order_state', '=', 'production_orders.id_production_order_state')
                ->where('salable_product', '=', 1)
                ->where('enabled_production_order', '=', 1)
                ->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('productions.production-order.index', compact('conf', 'table'));
    }

    public function create()
    {

        $conf = [
            'title-section' => 'Crear nueva order de producción',
            'group' => 'production-order',
            'back' => 'production-order.index',
            'url' => '#'
        ];

        $products = Product::where('salable_product', '=', 1)->pluck('name_product', 'id_product');

        return view('productions.production-order.create', compact('conf', 'products'));
    }


    public function validateQtys(Request $request)
    {
        $detalles = MaterialsListDetails::whereIdMaterialsList($request->id)->get()[0]->details;
        $obj = json_decode($detalles, true);
        $arr = [];
        for ($i = 0; $i < count($obj['id_product_details']); $i++) {
            if (($obj['qtys'][$i] * intval($request->qty_planned)) < Product::find($obj['id_product_details'][$i])->qty_product) {
                $arr[$i] = $obj['qtys'][$i] * intval($request->qty_planned);
            } else {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'La cantidad de la materia prima en stock no se encuentra disponible para produccir, intente con una cantidad menor',
                ]);
            }
        }
        return $arr;
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $saveOrder = new ProductionOrder();
        $saveOrder->name_production_order = $data['name_production_order'];
        $saveOrder->date_from_production_order = $data['date_from_production_order'];
        $saveOrder->date_to_production_order = $data['date_to_production_order'];
        $saveOrder->planned_qty_production_order = $data['planned_qty_production_order'];
        $saveOrder->id_product = $data['id_product'];
        $saveOrder->id_material_list = $data['id_material_list'];
        $saveOrder->id_user = Auth::id();
        $saveOrder->save();
        return redirect()->route('production-order.index')->with('message', 'Se registro con éxito');
    }

    public function show($id)
    {
        $data = ProductionOrder::select('id_production_order', 'name_production_order', 'date_from_production_order', 'date_to_production_order', 'planned_qty_production_order', 'name_product', 'id_material_list', 'materials_list_details.details', 'name_materials_list', 'id_production_order_state')
            ->join('products', 'products.id_product', '=', 'production_orders.id_product')
            ->join('materials_lists', 'materials_lists.id_materials_list', '=', 'production_orders.id_material_list')
            ->join('materials_list_details', 'materials_list_details.id_materials_list', '=', 'materials_lists.id_materials_list')
            ->where('id_production_order', '=', $id)
            ->get()[0];

        $objDetails = json_decode($data->details, true);
        $dataProductsDetails = [];
        $dataPresentationDetails = [];
        for ($i = 0; $i < count($objDetails['id_product_details']); $i++) {
            $dataProductsDetails[$i] = Product::select('name_product')
                ->where('id_product', '=', $objDetails['id_product_details'][$i])
                ->get();
            $dataPresentationDetails[$i] = PresentationProduct::select('name_presentation_product')
                ->where('id_presentation_product', '=',  $objDetails['id_presentation_details'][$i])
                ->get();
        }
        $conf = [
            'title-section' => 'Order de producción: ' . $data->name_production_order,
            'group' => 'production-order',
            'back' => 'production-order.index',
            'url' => '#'
            'edit' => ['route' => 'material-list.edit', 'id' => $data->id_materials_list],
        ];
        return view('productions.production-order.show', compact('conf', 'data', 'dataProductsDetails', 'dataPresentationDetails', 'objDetails'));
    }



    public function finalice($id)
    {
        $data = ProductionOrder::select('id_product', 'planned_qty_production_order')
            ->where('id_production_order', '=', $id)
            ->get()[0];
        $producto = Product::select('qty_product')->find($data->id_product);
        Product::whereIdProduct($data->id_product)->update(['qty_product' => $producto->qty_product + $data->planned_qty_production_order]);
        ProductionOrder::where('id_production_order', '=', $id)->update(['id_production_order_state' => 3]);
        return back();
    }


    public function aprove($id)
    {
        $data = ProductionOrder::select('id_production_order', 'name_production_order', 'date_from_production_order', 'date_to_production_order', 'planned_qty_production_order', 'name_product', 'id_material_list', 'materials_list_details.details', 'name_materials_list')
            ->join('products', 'products.id_product', '=', 'production_orders.id_product')
            ->join('materials_lists', 'materials_lists.id_materials_list', '=', 'production_orders.id_material_list')
            ->join('materials_list_details', 'materials_list_details.id_materials_list', '=', 'materials_lists.id_materials_list')
            ->where('id_production_order', '=', $id)
            ->get()[0];
        $objDetails = json_decode($data->details, true);
        for ($i = 0; $i < count($objDetails['id_product_details']); $i++) {
            $restar =  Product::select('qty_product')->whereIdProduct($objDetails['id_product_details'][$i])->get();
            $ope = $objDetails['qtys'][$i] * $data->planned_qty_production_order;
            $operacion = $restar[0]->qty_product - $ope;
            Product::whereIdProduct($objDetails['id_product_details'][$i])->update(['qty_product' => $operacion]);
        }
        ProductionOrder::where('id_production_order', '=', $id)->update(['id_production_order_state' => 2]);
        return back();
    }

    public function traerListaMateriales(Request $request)
    {
        $dataMaterialList = MaterialsList::select('materials_lists.*', 'materials_list_details.details')
            ->join('materials_list_details', 'materials_list_details.id_materials_list', '=', 'materials_lists.id_materials_list')
            ->where('materials_lists.id_materials_list', '=', $request->id)
            ->where('enabled_materials_list', '=', 1)
            ->get();
        $obj = json_decode($dataMaterialList[0]['details'], true);
        $products = [];
        $presentations = [];
        for ($i = 0; $i < count($obj['id_product_details']); $i++) {
            $products[$i] = Product::select('id_product', 'name_product')->whereIdProduct($obj['id_product_details'][$i])->get();
            $presentations[$i] = PresentationProduct::select('name_presentation_product')->where('id_presentation_product', '=', $obj['id_presentation_details'][$i])->get();
        }
        return response()->json([
            'data' => $dataMaterialList,
            'products' => $products,
            'presentations' => $presentations,
        ]);
    }

    public function traerLista(Request $request)
    {
        $dataMaterialList = MaterialsList::select('materials_lists.*', 'materials_list_details.details')
            ->join('materials_list_details', 'materials_list_details.id_materials_list', '=', 'materials_lists.id_materials_list')
            ->where('id_product', '=', $request->id)
            ->where('enabled_materials_list', '=', 1)
            ->get();
        if (count($dataMaterialList) > 0) {
            $obj = json_decode($dataMaterialList[0]['details'], true);
            $products = [];
            $presentations = [];
            for ($i = 0; $i < count($obj['id_product_details']); $i++) {
                $products[$i] = Product::select('id_product', 'name_product')->whereIdProduct($obj['id_product_details'][$i])->get();
                $presentations[$i] = PresentationProduct::select('name_presentation_product')->where('id_presentation_product', '=', $obj['id_presentation_details'][$i])->get();
            }
            return response()->json([
                'data' => $dataMaterialList,
                'products' => $products,
                'presentations' => $presentations,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'msg' => 'El producto seleccionado no posee lista de materiales'
            ]);
        }
    }
}
