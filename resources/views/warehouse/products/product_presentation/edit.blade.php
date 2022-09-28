@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection


@section('content')
{!! Form::model($getData, ['method' => 'PATCH','route' => ['presentation.update', $getData->id_presentation_product]]) !!}
<div class="row g-3">
    <x-cards size="12">

    <div class="col-md-12">
        <label class="form-label">Nombre de la presentación</label>
        {!! Form::text('name_presentation_product', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre de la presentación','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre de la presentaión
        </div>

    </div>
    
    </x-cards>

</div>

<x-btns-save/>

{!! Form::close() !!}

@endsection