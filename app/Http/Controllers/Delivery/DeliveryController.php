<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Conf\Zone;
use App\Models\Delivery\Delivery;
use App\Models\HumanResources\Workers;
use App\Models\Sales\Invoicing;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:delivery-list|adm-list', ['only' => ['index']]);
         $this->middleware('permission:adm-create|delivery-create', ['only' => ['create','store']]);
         $this->middleware('permission:adm-edit|delivery-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:adm-delete|delivery-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        
        $conf = [
            'title-section' => 'Despachos',
            'group' => 'delivery',
            'create' => ['route' =>'delivery.create', 'name' => 'Nueva despacho'],
        ];


        $table = [
            'c_table' => 'table table-bordered table-hover mb-0 text-uppercase',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Guía', 'Fecha', 'Estado'],
            'w_ts' => ['3','', '15', '15',],
            'c_ths' => 
                [
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',
                'text-center align-middle',],
            'tds' => ['guide_delivery', 'date_delivery', 'estado_delivery'],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,  
            'show' => false,
            'url' => "/delivery/delivery",
            'id' => 'id_delivery',
            'group' => 'delivery',
            'data' => Delivery::whereEnabledDelivery(1)->paginate(10),
            'i' => (($request->input('page', 1) - 1) * 5),
        ];


        return view('delivery.delivery.index', compact('conf', 'table'));
    }

    public function create(){

        $conf = [
            'title-section' => 'Crear un nueva guía',
            'group' => 'delivery',
            'back' => 'delivery.index',
            'url' => '#'
        ];

        $invoices = Invoicing::select('invoicings.*', 'clients.name_client')
                                ->join('clients', 'clients.id_client', '=', 'invoicings.id_client')
                                ->where('id_order_state', '=', 4)
                                ->where('state_delivery', '=', 0)->get();
        $zone = Zone::whereEnabledZone(1)->pluck('name_zone', 'id_zone');

        $driver = Workers::where('name_group_worker', '=', 'CHOFER')
                        ->join('group_workers', 'group_workers.id_group_worker', '=', 'workers.id_group_worker')->get();
        
        $caletero = Workers::where('name_group_worker', '=', 'CALETERO')
        ->join('group_workers', 'group_workers.id_group_worker', '=', 'workers.id_group_worker')->get();


            

        return view('delivery.delivery.create', compact('invoices', 'conf', 'zone', 'driver', 'caletero'));
    }


    public function store(Request $request){


      //  return $request;

        $data = $request->except('_token');

        $delivery = new Delivery();

        $delivery->ids_invoices = json_encode($data['ids_invoices']);
        $delivery->id_worker = $data['id_worker'];
        $delivery->id_caletero = $data['id_caletero'];
        $delivery->guide_delivery = $data['guide_delivery'];
        $delivery->date_delivery = $data['date_delivery'];

        $delivery->state_delivery = 1;
        $delivery->id_zone = $data['id_zone'];

        $delivery->save();


        for($i = 0; $i<count($data['ids_invoices']); $i++){            
           Invoicing::whereIdInvoicing($data['ids_invoices'][$i])->update([
            'id_delivery' =>  $delivery->id_delivery,
            'state_delivery' => 1
        ]);
        }

        
        return redirect()->route('delivery.index');
    }
}
