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
            'c_table' => 'table table-bordered table-hover mb-0 ',
            'c_thead' => 'bg-gray-900 text-white',
            'ths' => ['#', 'Nombre del elemento', 'URL', 'Posición', 'Activo'],
            'w_ts' => ['3','50', '', '4', '4'],
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
            'data' => Menu::where('parent', '=', '0')->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];
        
        return view('conf.menus.index', compact('conf', 'table'));
    }

    public function create(){}

    public function store(){}

    public function show($id){
        $dataPapa = Menu::whereId($id)->whereEnabled(1)->get()[0];
        $dataHijos = Menu::whereParent($id)->get();

        $conf = [
            'atras' => [ 'color' => 'dark', 'icono' => 'fa-solid fa-circle-chevron-left', 'url' => '/mantenice/menu' ],
            'primernivel' => [ 'name'=> 'Menís', 'url' => '/mantenice/menu' ], 
            'segundonivel' => [ 'name'=> 'Ver elemento: '.$dataPapa->name ],
        ];

        

        
        //return $dataPapa;
        return view('conf.menus.show', compact('dataPapa', 'dataHijos', 'conf'));
    }

    public function edit($id){}
    public function update(){}

    public function destroy(){}
}
