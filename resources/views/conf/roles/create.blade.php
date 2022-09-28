@extends('layouts.app')


@section('title-section', 'Crear nuevo grupo')

@section('btn')
    <a href="{{ route('roles.index') }}" class="btn btn-dark btn-icon-split">
        <span class="icon text-white-50">
        <i class="fas fa-chevron-circle-left"></i>
        </span>
        <span class="text">Atras</span>
    </a>
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

{!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
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
                    {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
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



@endsection