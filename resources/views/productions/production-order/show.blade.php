@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection



@section('content')

<div class="row g-3">

    <x-cards size="12">
        <div class="row g-3">
        <div class="col-12">
            @switch($data->id_production_order_state)
                @case(1)
                        <a href="/production/aprove/{{$data->id_production_order}}" class="btn btn-warning btn-sm">Producir</a>
                    @break
                @case(2)
                    <a href="/production/finalice/{{$data->id_production_order}}" class="btn btn-success btn-sm">Aprobar</a>
                    @break                   
            @endswitch
        
            
        </div>
            <div class="col-md-3">
            <label class="form-label">Nombre:</label>
                <label class="form-label">{{$data->name_production_order}}</label>

            </div>
            <div class="col-md-3">
            <label class="form-label">Desde:</label>
                <label class="form-label">{{date('d-m-Y', strtotime($data->date_from_production_order))}}</label>
 
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
            <label for="inputState" class="form-label">Producto:</label>
                <label for="inputState" class="form-label">{{$data->name_product}}</label>
            </div>
            <div class="col-md-3">
            <label class="form-label">Hasta:</label>
                <label class="form-label">{{date('d-m-Y', strtotime($data->date_to_production_order))}}</label>

            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
            <label class="form-label">Cantidad para producir:</label>
                <label class="form-label">{{$data->planned_qty_production_order}}</label>
            </div>

            <div class="clearfix"></div>
            <div class="col-md-3">
            <label for="inputState" class="form-label">Lista de materiales:</label>
                <label for="inputState" class="form-label">{{$data->id_material_list}}</label>
            </div>

        </div>
    </x-cards>

    <x-cards>
             
    <div class="row g-3">
        
    <div class="col-12" id="mytb">
        


        <table class="table table-sm border-dark table-bordered mb-0" id="myTable">
        <tr>
            
            <th scope="col" class="align-middle">PRODUCTO</th>
            <th scope="col" class="text-center align-middle" width="10%">CANTIDAD</th>
            <th scope="col" class="text-center align-middle" width="10%">UNIDAD</th>
            
        </tr>   

        @for ($i=0; $i < count($objDetails['id_product_details']); $i++)
            <tr>
                <td class="align-middle">{{ $dataProductsDetails[$i][0]['name_product'] }}</td>
                <td class="text-center align-middle">{{ $objDetails['qtys'][$i]*$data->planned_qty_production_order }}</td>
                <td class="text-center align-middle">{{ $dataPresentationDetails[$i][0]['name_presentation_product'] }}</td>
            </tr>
        @endfor
    </table> 
    </div>
    </div>
    </x-cards>

</div>


@endsection