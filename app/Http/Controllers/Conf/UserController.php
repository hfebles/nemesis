<?php

namespace App\Http\Controllers\Conf;

use DB;
use Hash;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;



class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|adm-list|user-profile-list', ['only' => ['index', 'profile']]);
         $this->middleware('permission:adm-create|user-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|user-edit|user-profile-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $conf = [
            'title-section' => 'Usuarios',
            'group' => 'user',
            'create' => ['route' =>'users.create', 'name' => 'Nueva usuario', ],
        ];

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Usuario', 'Email', 'Grupo', ],
            'w_ts' => ['3','','','',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['name', 'email', ''],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,  
            'show' => true,
            'url' => "/mantenice/users",
            'id' => 'id',
            'group' => 'user',
            'data' => User::select()
                          
                            ->where('name', '<>', 'Admin')->orderBy('id','ASC')->paginate(15),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('conf.users.index',compact('table', 'conf'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {

        $conf = [
            'title-section' => 'Crear nuevo usuario',
            'group' => 'user',
            'back' => 'users.index',
            'url' => '#'
        ];


        $roles = Role::where('name', '<>', 'Super-Admin')->pluck('name','name')->all();


        return view('conf.users.create', compact('roles', 'conf'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));


        $message = [
            'type' => 'success',
            'message' => 'Usuario creado con éxito',
        ];
                        
        return redirect()->route('users.index')->with('message', $message);

    }

    public function profile($id){

        $user = User::find($id);

        $conf = [
            'title-section' => 'Usuario: '.$user->name,
            'group' => 'user',
            'back' => 'home',
            'url' => '#',
            
            'delete' => ['name' => 'Eliminar usuario'],

        ];

        return view('conf.users.profile',compact('user', 'conf'));
    }

    public function show($id)
    {
        $user = User::find($id);

        $conf = [
            'title-section' => 'Usuario: '.$user->name,
            'group' => 'user',
            'back' => 'users.index',
            'url' => '#',
            'edit' => ['route' => 'users.edit', 'id' => $user->id],
            'delete' => ['name' => 'Eliminar usuario'],

        ];
        
        return view('conf.users.show',compact('user', 'conf'));
    }

    public function edit($id)
    {

        


        $user = User::find($id);
        $roles = Role::where('name', '<>', 'Super-Admin')->pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();


        $conf = [
            'title-section' => 'Usuario: '.$user->name,
            'group' => 'user',
            'back_show' => ['route' => 'users.show', 'id' => $user->id],
            'url' => '#',
            'delete' => ['name' => 'Eliminar usuario'],
        ];
    
        return view('conf.users.edit',compact('user','roles','userRole', 'conf'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));


        $message = [
            'type' => 'warning',
            'message' => 'Usuario actualizado con éxito',
        ];
                        
        return redirect()->route('users.index')->with('message', $message);
    

    }

    public function destroy($id)
    {
        User::find($id)->delete();

        $message = [
            'type' => 'danger',
            'message' => 'Usuario eliminado con éxito',
        ];
                        
        return redirect()->route('users.index')->with('message', $message);

    }


}
