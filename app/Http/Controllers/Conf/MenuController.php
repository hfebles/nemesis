<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Conf\Menu;

class MenuController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:menu-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|menu-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|menu-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|menu-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $conf = [
            'title-section' => 'Gestion de menus',
            'group' => 'menu',
            'create' => ['route' =>'menu.create', 'name' => 'Nuevo menú'],
            'url' => '/mantenice/menu',
        ];
        $table = [
            'c_table' => 'table table-bordered table-sm table-hover mb-0 ',
            'c_thead' => 'bg-gray-900 text-white',
            'ths' => ['#', 'Nombre del elemento', 'URL', 'Posición', 'Activo'],
            'w_ts' => ['3','50', '', '5', '5'],
            'c_ths' => 
                [
                'text-center align-middle',
                'align-middle', 
                'align-middle', 
                'text-center align-middle', 
                'text-center align-middle'],
                
            'tds' => ['name', 'slug', 'order', 'enabled'],
            'switch' => false,
            'edit' => false,
            'show' => true,
            'edit_modal' => false, 
            'url' => "/mantenice/menu",
            'id' => 'id',
            'data' => Menu::where('parent', '=', '0')->orderBy('order', 'ASC')->paginate(15),
            'i' => (($request->input('page', 1) - 1) * 15),
        ];
        return view('conf.menus.index', compact('conf', 'table'));
    }

    public function create(){}

    public function store(Request $request){

        //return $request;

        $menu = new Menu();

        $menu->name = strtoupper($request->name);
        $menu->slug = $request->slug;
        $menu->order = $request->order;
        if(isset($request->href)){
            $menu->href =$request->href;
        }else{
            $menu->href = 0;
        }
        
        $menu->parent =$request->parent;
        $menu->save();

        return redirect()->route('menu.show', $request->parent);

    }

    public function show($id){
        $dataPapa = Menu::whereId($id)->whereEnabled(1)->get()[0];
        $dataHijos = Menu::whereParent($id)->get();

        $conf = [
            'atras' => [ 'color' => 'dark', 'icono' => 'fa-solid fa-circle-chevron-left', 'url' => '/mantenice/menu' ],
            'primernivel' => [ 'name'=> 'Menís', 'url' => '/mantenice/menu' ], 
            'segundonivel' => [ 'name'=> 'Ver elemento: '.$dataPapa->name ],
        ];
        return view('conf.menus.show', compact('dataPapa', 'dataHijos', 'conf'));
    }

    public function edit($id){}
    public function update(){}
    public function destroy(){}

    public function activate($id){

       if(Menu::find($id)->enabled == 1){
        Menu::find($id)->update(['enabled' => 0]);
       }else{
        Menu::find($id)->update(['enabled' => 1]);
       }
        return back();
    }
}
