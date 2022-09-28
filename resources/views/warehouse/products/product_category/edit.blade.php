@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection


@section('content')
{!! Form::model($getData, ['method' => 'PATCH','route' => ['category.update', $getData->id_product_category]]) !!}
<div class="row g-3">
    <x-cards size="12">

    <div class="col-md-12">
        <label class="form-label">Nombre de la Categoría</label>
        {!! Form::text('name_product_category', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre del almacen','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre de la categoría
        </div>

    </div>
    
    </x-cards>

      

</div>

<x-btns-save/>

{!! Form::close() !!}

@endsection