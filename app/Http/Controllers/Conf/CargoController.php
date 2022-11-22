<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use App\Models\Conf\Cargo;
use App\Models\Conf\Zone;
use Illuminate\Http\Request;

class CargoController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:cargo-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|cargo-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|cargo-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|cargo-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $conf = [
            'title-section' => 'Fletes',
            'group' => 'cargo',
            'create' => ['route' => 'cargo.create', 'name' => 'Nuevo flete', 'btn_type' => 2,],
        ];
        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Nombre', 'Precio'],
            'w_ts' => ['3', '', '',],
            'c_ths' =>
            [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
            ],
            'tds' => ['name_zone', 'price_cargo'],
            'switch' => false,
            'edit' => false,
            'edit_modal' => true,
            'show' => false,
            'url' => "/mantenice/cargo",
            'id' => 'id_cargo',
            'group' => 'cargo',
            'data' => Cargo::select('*', 'zones.name_zone')
                ->join('zones', 'zones.id_zone', '=', 'cargos.id_zone')
                ->whereEnabledCargo(1)->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];
        $dataZones = Zone::pluck('name_zone', 'id_zone');
        return view('conf.cargo.index', compact('conf', 'table', 'dataZones'));
    }

    public function create()
    {
        $conf = [
            'title-section' => 'Crear un nuevo flete',
            'group' => 'cargo',
            'back' => 'cargo.index',
            'url' => '#'
        ];
        return view('fletes.create', compact('conf'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $cargo = new Cargo();
        $cargo->id_zone = $data['id_zone'];
        $cargo->price_cargo = $data['price_cargo'];
        $cargo->save();
        return redirect()->route('cargo.index')->with('message', 'Se registro el flete con éxito');
    }

    public function editModal(Request $request)
    {
        $response = [
            'zone' => Zone::pluck('name_zone', 'id_zone'),
            'data' => Cargo::select('*', 'zones.name_zone')
                ->join('zones', 'zones.id_zone', '=', 'cargos.id_zone')
                ->whereIdCargo($request->id)->get()[0],
        ];
        return $response;
    }

    public function update(Request $request, $id)
    {
        $dataCargo = Cargo::find($id);
        $data = $request->except('_token', '_method');
        $dataCargo->update($data);
        return back();
    }
}
