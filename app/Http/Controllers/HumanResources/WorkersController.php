<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanResources\Workers;
use App\Models\HumanResources\GroupWorkers;

use App\Models\User;
use Illuminate\Queue\Worker;

class WorkersController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:rrhh-worker-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|rrhh-worker-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|rrhh-worker-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|rrhh-worker-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $data = Workers::select('workers.*', 'gw.name_group_worker')
                        ->join('group_workers as gw', 'gw.id_group_worker', '=', 'workers.id_group_worker')
                        ->whereEnabledWorker(1)->paginate(10);
        //return $data;
        $conf = [
            'title-section' => 'Trabajadores',
            'group' => 'rrhh-worker',
            'create' => ['route' =>'workers.create', 'name' => 'Nueva trabajador',],
            'url' => '/hhrr/workers'
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Cédula', 'Apellidos', 'Nombres', 'Telefono', 'Grupo de trabajo'],
            'w_ts' => ['3','', '', '', '', ''],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',
                'align-middle',
                'align-middle',
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['dni_worker', 'last_name_worker', 'firts_name_worker', 'phone_worker', 'name_group_worker'],
            'switch' => false,
            'edit' => false, 
            'show' => true,
            'edit_modal' => false, 
            'url' => "/hhrr/workers",
            'id' => 'id_worker',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('rrhh.workers.index', compact('table', 'conf'));

    }

    public function create(){


        $conf = [
            'title-section' => 'Cargar un nuevo trabajador',
            'group' => 'rrhh-worker',
            'back' => 'workers.index',
            'url' => '/hhrr/workers'
        ];

        $userRegister = Workers::select('id_user')->where('id_user', '<>', null)->get();
        $group =  GroupWorkers::whereEnabledGroupWorker(1)->pluck('name_group_worker', 'id_group_worker');
        $users = User::whereNotIn('id', $userRegister)->where('name', 'not like', '%admin%')->pluck('name', 'id');
        
       // return $userRegister;



        return view('rrhh.workers.create', compact('conf', 'group', 'users'));
    }


    public function store(Request $request){


        $data = $request->except('_token');

        //return $data;

        $save = new Workers();

        $save->firts_name_worker  = strtoupper($data['firts_name_worker']);
        $save->last_name_worker = strtoupper($data['last_name_worker']);
        $save->dni_worker = $data['dni_worker'];
        $save->id_group_worker = $data['id_group_worker'];

        if(isset($data['phone_worker'])){
            $save->phone_worker = $data['phone_worker'];
        }
      
        if(isset($data['mail_worker'])){
            $save->mail_worker = strtoupper($data['mail_worker']);
        }
        
        if(isset($data['id_user'])){
            $save->id_user = $data['id_user'];
        }

        $save->save();


        $message = [
            'type' => 'danger',
            'message' => 'Se registro el trabajador: '.$save->firts_name_worker.' '.$save->last_name_worker.' con éxito',
        ];
                        
        return redirect()->route('workers.index')->with('message', $message);
    }



    public function show($id){

        $data = Workers::select('dni_worker', 'last_name_worker', 'firts_name_worker', 'mail_worker', 'phone_worker', 'gw.name_group_worker', 'u.name')
            ->join('group_workers as gw', 'gw.id_group_worker', '=', 'workers.id_group_worker', 'left outer') 
            ->join('users as u', 'u.id', '=', 'workers.id_user', 'left outer')
            ->whereIdWorker($id)
            ->get()[0];

        


        
        //return $data;


        $conf = [
            'title-section' => 'Trabajador: '.$data->last_name_worker.' '.$data->firts_name_worker,
            'group' => 'rrhh-worker',
            'back' => 'workers.index',
            'edit' => ['route' => 'workers.edit', 'id' => $id,],
            'url' => '/hhrr/workers'
        ];


        

        //return $data;

        return view('rrhh.workers.show', compact('data','conf'));

    }

    public function edit($id){
        $data = Workers::select('id_worker', 'dni_worker', 'last_name_worker', 'firts_name_worker', 'mail_worker', 'phone_worker', 'id_user', 'workers.id_group_worker', 'gw.id_group_worker', 'u.id')
        ->join('group_workers as gw', 'gw.id_group_worker', '=', 'workers.id_group_worker', 'left outer') 
        ->join('users as u', 'u.id', '=', 'workers.id_user', 'left outer')
        ->whereIdWorker($id)
        ->get()[0];


        $conf = [
            'title-section' => 'Editar trabajador: '.$data->last_name_worker.' '.$data->firts_name_worker,
            'group' => 'rrhh-worker',
            'back' => 'workers.index',
            'url' => '/hhrr/workers'
        ];

        $userRegister = Workers::select('id_user')->where('id_user', '<>', $data->id_user)->where('id_user', '<>', null)->get();
        $group =  GroupWorkers::whereEnabledGroupWorker(1)->pluck('name_group_worker', 'id_group_worker');
        $users = User::whereNotIn('id', $userRegister)->where('name', 'not like', '%admin%')->pluck('name', 'id');
        

        return view('rrhh.workers.edit', compact('conf', 'data', 'group', 'users'));


    }


    public function update(Request $request, $id){

        $data = $request->except('_token', '_method');
        
        

        $data['firts_name_worker']  = strtoupper($data['firts_name_worker']);
        $data['last_name_worker'] = strtoupper($data['last_name_worker']);

        if(isset($data['phone_worker'])){
            $data['phone_worker'] = $data['phone_worker'];
        }
      
        if(isset($data['mail_worker'])){
            $data['mail_worker'] = strtoupper($data['mail_worker']);
        }
        
        if(isset($data['id_user'])){
            $data['id_user'] = $data['id_user'];
        }

        Workers::whereIdWorker($id)->update($data);

        $message = [
            'type' => 'danger',
            'message' => 'Se actualizo el trabajador con éxito',
        ];
                        
        return redirect()->route('workers.index')->with('message', $message);
    }

    /*========================================*/

    function searchCedula(Request $request){
        $data = Workers::where('dni_worker', '=', $request->text)->get();
        if(count($data) > 0 ){
            return response()->json(['res' => false, 'msg' => 'La cédula ya fué registrada']);
        }else{
            return response()->json(['res' => true, 'msg' => 'La cédula es valida']);
        }
        return $data;
    }

}

