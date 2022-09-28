<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanResources\GroupWorkers;


class GroupWorkersController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:rrhh-group-worker-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|rrhh-group-worker-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|rrhh-group-worker-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|rrhh-group-worker-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){


        $conf = [
            'title-section' => 'Grupos de trabajo',
            'group' => 'rrhh-group-worker',
            'create' => ['route' =>'group-workers.create', 'name' => 'Nueva grupo', 'btn_type' => 2,],
            'url' => '/hhrr/group-workers'
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Grupo',],
            'w_ts' => ['3','',],
            'c_ths' => 
                [
                'text-center align-middle',
                'align-middle',],
            'tds' => ['name_group_worker',],
            'switch' => false,
            'edit' => false, 
            'show' => false,
            'edit_modal' => true, 
            'url' => "/hhrr/group-workers",
            'id' => 'id_group_worker',
            'data' => GroupWorkers::whereEnabledGroupWorker(1)->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('rrhh.group-worker.index', compact('table', 'conf'));

    }


    public function store(Request $request){

        $data = $request->except('_token');


        $save = new GroupWorkers();

        $save->name_group_worker = strtoupper($data['name_group_worker']);
        $save->save();

        $message = [
            'type' => 'danger',
            'message' => 'Se registro el grupo: '.$save->name_group_worker.' con exito',
        ];
                        
        return redirect()->route('group-workers.index')->with('message', $message);
        


    }

    public function editModal(Request $request){
        
        $response =[
            'data' => GroupWorkers::whereIdGroupWorker($request->id)->get()[0],
        ];
        return $response;
    }

    public function update(Request $request, $id){

        $data = $request->except('_method', '_token');

        GroupWorkers::whereIdGroupWorker($id)->update($data);


        $message = [
            'type' => 'danger',
            'message' => 'Grupo editado con exito: '.$data['name_group_worker'],
        ];
                        
        return redirect()->route('group-workers.index')->with('message', $message);
    }
}
