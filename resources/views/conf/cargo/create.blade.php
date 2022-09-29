@section('side-title', 'Crear una nuevo banco')

@section('side-body')
{!! Form::open(array('route' => 'cargo.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Precio</label>
        {!! Form::number('price_cargo', null, array('id' => 'price_cargo', 'autocomplete' => 'off', 'required', 'placeholder' => 'Precio','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Ingresa el precio
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Zona de cobertura</label>
        {!! Form::select('id_zone', $dataZones, null, ['placeholder' => 'Seleccione una zona', 'required', 'class' =>'form-select form-control-sm' ]) !!}
        <div  class="invalid-feedback">
            Para guardar debe selecciona la zona de cobertura del flete
        </div>
    </div>



<x-btns-save side="true"/>
    {!! Form::close() !!}

@endsection

