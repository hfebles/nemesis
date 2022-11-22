<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|role-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {


        



        

        $roles = Role::where('name', '<>', 'Super-Admin')->orderBy('id','ASC')->paginate(5);

        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 ',
            'c_thead' => 'bg-gray-900 text-white',
            'ths' => ['#', 'Nombre del grupo',],
            'w_ts' => ['3', ''],
            'c_ths' => ['align-middle text-center', 'align-middle'],
            'tds' => ['name'],
            'edit' => true,
            'show' => false,
            'edit_modal' => false,
            'url' => "/mantenice/roles",
            'id' => 'id',
            'data' => $roles,
            'i' => (($request->input('page', 1) - 1) * 5),
            'group' => 'roles',
        ];

        $conf = [
            'group' => 'roles',
        ];


        
        return view('conf.roles.index',compact('table', 'conf'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $permission = Permission::where('name', 'not like', '%adm%')->get();
        return view('conf.roles.create', compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
    
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
                       
        return redirect()->route('roles.index')->with('message', 'Permisologia creada con éxito');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
    
        return view('conf.roles.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::where('name', 'not like', '%adm%')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

            
    
        return view('conf.roles.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));
                        
        return redirect()->route('roles.index')->with('message', 'Permisologia editada con éxito');
    
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();

                        
        return redirect()->route('roles.index')->with('error', 'Permisologia eliminada con éxito');

    }
    
}
