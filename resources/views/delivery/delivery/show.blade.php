@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')

<div class="row g-3">
    <x-cards size="12">
        <div class="col-12 mb-3">
            <div class="d-flex flex-row  mb-3">
                    <div class="ms-auto">
                        
                        <a class="btn btn-warning btn-sm btn-icon-split" href="{{ route('delivery.aprove', $data->id_delivery) }}">
                            <span class="icon text-white-50">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span class="text">Procesar</span>
                        </a>
                    </div>
                    <div class="ml-3">
                        
                        <a class="btn btn-danger btn-sm btn-icon-split" href="{{ route('delivery.cancel', $data->id_delivery) }}">
                            <span class="icon text-white-50">
                                <i class="fas fa-times-circle"></i>
                            </span>
                            <span class="text">Anular</span>
                        </a>
                    </div>
            </div>
        </div>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Estado:</label>
            @switch($data->state_delivery)
                @case(1)
                    <label for="">Por despachar</label>
                    @break
                @case(2)
                    <label for="">En Proceso</label>
                    @break
                @case(1)
                    <label for="">Despachado</label>
                @break
                @default
                    
            @endswitch
        </div>
        <div class="clearfix"></div>
        <div class="col-md-8">
            <label class="form-label">Guía: </label>
            <label class="form-label">{{$data->guide_delivery}}</label>
        </div>
        <div class="col-md-4">
            <label class="form-label">Fecha:</label>
            <label class="form-label">{{$data->date_delivery}}</label>         
        </div>
        <div class="col-md-4">
            <label class="form-label">Chofer:</label>
            <label class="form-label">{{$chofer->firts_name_worker}} {{ $chofer->last_name_worker }}</label>         
        </div>
        <div class="col-4">
            <label class="form-label">Celetero:</label>
            <label class="form-label">{{$caletero->firts_name_worker}} {{ $caletero->last_name_worker }}</label>         
        </div>
        <div class="clearfix"></div>
        <div class="col-4">
            <label class="form-label">Zona:</label>
            <label class="form-label">{{$data->name_zone}}</label>
        </div>
        <div class="col-md-12">
            <label class="form-label">Facturas:</label>
            <div class="row">
                @foreach ($facturas as $k => $factura)
                <div class="col-6">
                    <label class="form-label"><a href="{{ route('invoicing.show', $factura->id_invoicing) }}">{{ $factura->ref_name_invoicing }} - {{ $clientes[$k]->name_client }}</a></label>
                </div>    
                @endforeach
                
            </div>

        </div>
    </div>
    </x-cards>
</div>



@endsection