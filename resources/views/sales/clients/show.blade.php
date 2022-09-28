@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :edit="$conf['edit']" :group="$conf['group']" :delete="$conf['delete']" />
@endsection

@section('content')

<div class="row">
    <x-cards size="12">
    <div class="row g-3">
        <div class="col-md-12">
            <label class="form-label">Nombre Apellido ó Razón Social: </label>
            <label class="form-label">{{$getClient->name_client}}</label>
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-md-4">
            <label class="form-label">DNI / RIF:</label>
            <label class="form-label">{{$getClient->idcard_client}}</label>         
        </div>
        <div class="col-4">
            <label class="form-label">Teléfono:</label>
            <label class="form-label">{{$getClient->phone_client}}</label>
        </div>
        <div class="col-4">
            <label class="form-label">Correo Electrónico:</label>
            <label class="form-label">{{$getClient->email_client}}</label>
        </div>
        <div class="col-md-12">
            <label class="form-label">Dirección:</label>
            <label class="form-label">{{$getClient->address_client}}</label>

        </div>
        <div class="col-md-3">
            <label class="form-label">Estado de residencia:</label>
            <label class="form-label">{{$getState}}</label>

        </div>
        <div class="col-md-3">
            <label class="form-label">Código postal:</label>
            <label class="form-label">{{$getClient->zip_client}}</label>

        </div>
        <div class="col-3 d-flex align-items-center mt-auto mb-2 justify-content-center">
            <div class="form-check">
                @if ($getClient->taxpayer_client == 1)
                    <input class="form-check-input" type="checkbox" checked disabled>             
                @else
                    <input class="form-check-input" type="checkbox" disabled>            
                @endif
                    
                <label class="form-check-label">
                    ¿Agente de retención?
                </label>

            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label">Porcentaje de Retención: </label>
            <label class="form-label">{{$getClient->porcentual_amount_tax_client}}%</label>
        </div>
    </div>
    </div>
    </x-cards>
</div>



<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea eliminar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>A seleccionado eliminar el cliente: </p>
                    <p>{{$getClient->name_client}}</p>
                    <p>Una vez eliminado no podra ser recuperado de nuevo</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    {!! Form::open(['method' => 'DELETE','route' => ['clients.destroy', $getClient->id_client],'style'=>'display:inline']) !!}
                        <button class="btn btn-danger btn-icon-split" type="submit">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Eliminar cliente</span>
                        </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


@endsection