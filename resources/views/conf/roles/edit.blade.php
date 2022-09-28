@extends('layouts.app')


@section('title-section', 'Editar grupo')
@section('btn')
    <a href="{{ route('roles.index') }}" class="btn btn-dark btn-icon-split ml-auto">
        <span class="icon text-white-50">
            <i class="fas fa-chevron-circle-left"></i>
        </span>
        <span class="text">Atras</span>
    </a>
    @can('adm-delete')
        <a class="btn btn-danger btn-icon-split ml-3" data-toggle="modal" data-target="#deleteModal">
            <span class="icon text-white-50">
                <i class="fas fa-trash"></i>
            </span>
            <span class="text">Eliminar grupo</span>
        </a>
    @endcan
@endsection


@section('content')
@if (count($errors) > 0)
<div class="row mb-3">
<x-cards size="12">
<div class="alert alert-danger mb-0">
        <strong>Whoops!</strong> a ocurrido un error.<br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
</x-cards>
</div>
@endif

{!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
<div class="row mb-3">
    <x-cards size="4">
        <strong>Nombre del grupo:</strong>
        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
    </x-cards>
    <x-cards size="8">
        <label for="name" class="form-label">Permisología:</label>
        <div class="d-flex justify-content-start flex-wrap">
            @foreach($permission as $k => $value)
                <div class="form-check w-25">
                    {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name form-check-input')) }}
                    <label class="form-check-label">{{ $value->name }}</label>
                </div>
            @endforeach  
        </div>
    </x-cards>
</div>

<div class="row text-center">
    <x-cards size="12">
        <button type="submit" class="btn btn-success">Guardar</button>
        <input class="btn btn-danger" type="reset" value="Deshacer">
        
    </x-cards> 
</div>

{!! Form::close() !!}


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
                    <p>A seleccionado eliminar el grupo: {{$role->name}}</p>
                    <p>Una vez eliminado no podra ser recuperado de nuevo</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                        <button class="btn btn-danger btn-icon-split" type="submit">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Eliminar grupo</span>
                        </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>








@endsection