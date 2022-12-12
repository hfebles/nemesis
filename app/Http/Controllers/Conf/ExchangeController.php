<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Conf\Exchange;

class ExchangeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:exchange-list|adm-list', ['only' => ['index']]);
        $this->middleware('permission:adm-create|exchange-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adm-edit|exchange-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adm-delete|exchange-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $conf = [
            'title-section' => 'Tasa de cambio BCV',
            'group' => 'exchange',
            'create' => ['route' => 'exchange.create', 'name' => 'Nueva tasa', 'btn_type' => 2,],
        ];

        $table = [
            'c_table' => 'table table-bordered table-sm table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Fecha', 'Monto',],
            'w_ts' => ['3', '', '',],
            'c_ths' =>
            [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
            ],
            'td_number' =>[false, true],
            'tds' => ['date_exchange', 'amount_exchange',],
            'money' => true,
            'switch' => false,
            'edit' => false,
            'show' => false,
            'edit_modal' => false,
            'url' => "/mantenice/exchange",
            'id' => 'id_exchange',
            'data' => Exchange::where('enabled_exchange', '=', '1')->orderBy('date_exchange', 'DESC')->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];

        return view('conf.exchange.index', compact('conf', 'table'));
    }

    public function store(Request $request)
    {

        //return $request;


        $data = $request->except('_token');
        $save = new Exchange();
        $save->date_exchange = $data['date_exchange'];
        $save->amount_exchange = $data['amount_exchange'];
        $save->save();

        //return $route;

        if($request->ruta == 'invoicing.index'){
            return redirect()->route('invoicing.create')->with('message', 'Se registro la tasa con éxito');
        }else{
            return redirect()->route('exchange.index')->with('message', 'Se registro la tasa con éxito');
        }
        
    }
}
