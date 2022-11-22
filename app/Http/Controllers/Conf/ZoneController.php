<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use App\Models\Conf\Country\Estados;
use App\Models\Conf\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:zone-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|zone-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|zone-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|zone-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $conf = [
            'title-section' => 'Zonas de cobertura',
            'group' => 'zone',
            'create' => ['route' =>'zones.create', 'name' => 'Nueva Zona'],
        ];


        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Nombre',],
            'w_ts' => ['3','',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['name_zone'],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,  
            'show' => true,
            'url' => "/mantenice/zones",
            'id' => 'id_zone',
            'group' => 'zone',
            'data' => Zone::whereEnabledZone(1)->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];


        return view('conf.zones.index', compact('conf', 'table'));
    }

    public function create(){

        $conf = [
            'title-section' => 'Crear un nueva zona',
            'group' => 'zone',
            'back' => 'zones.index',
            'url' => '#'
        ];

        $dataEstado = Estados::orderBy('estado', 'ASC')->get();
        return view('conf.zones.create', compact('dataEstado', 'conf'));
    }


    public function store(Request $request){

        $data = $request->except('_token');

        $zone = new Zone();

        $zone->name_zone = strtoupper($data['name_zone']);
        $zone->ids_estados = json_encode($data['ids_estados']);
        $zone->save();
                
        return redirect()->route('zones.index')->with('message', 'Se registro la zona con éxito');
    }

    public function show($id){

        $data = Zone::find($id);
        $obj = json_decode($data->ids_estados, true);      
        $dataEstados = [];

        for ($i = 0; $i < count($obj); $i++) {
           $dataEstados[$i] =  Estados::find($obj[$i]);
        }

       $conf = [
        'title-section' => 'Zona: '.$data->name_zone,
        'group' => 'zone',
        'back' => 'zones.index',
        'url' => '#',
        'edit' => ['route' => 'zones.edit', 'id' => $data->id_zone],
    ];

        return view('conf.zones.show', compact('dataEstados', 'data', 'conf'));
    }

    public function edit($id){

        $data = Zone::find($id);
        $obj = json_decode($data->ids_estados, true);      
        $dataEstados = [];


        $conf = [
            'title-section' => 'Zona: '.$data->name_zone,
            'group' => 'zone',
            'back' => 'zones.index',
            'url' => '#',
        ];

        $dataEstado = Estados::whereNotIn('id_estado', $obj)->orderBy('estado', 'ASC')->get();

        return Estados::statesPlucks();

        for ($i = 0; $i < count($obj); $i++) {
           $dataEstados[$i] = Estados::find($obj[$i]);
        }
        
        return view('conf.zones.edit', compact('dataEstados', 'dataEstado', 'conf', 'data'));
    }

    public function update(Request $request, $id){


        

        return redirect()->route('zones.index')->with('warning', 'Se registro la zona con éxito');
    }

    public function destroy($id){

        return redirect()->route('zones.index')->with('error', 'Se registro la zona con éxito');
    }
}
