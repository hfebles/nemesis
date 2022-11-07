@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
{!! Form::model($supplier, ['method' => 'PATCH','route' => ['supplier.update', $supplier->id_supplier]]) !!}
<div class="row">
    {{-- <x-cards>
        {{ Session::get('message')["message"][2]}}
    </x-cards> --}}
<x-cards size="12">

    <div class="row g-3">
        <div class="col-md-12">
            <label class="form-label">Nombre Apellido ó Razón Social:</label>
            {!! Form::text('name_supplier', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre y apellido ó la razón social del cliente','class' => 'form-control form-control-sm')) !!}
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-md-4">
            <label for="inputPassword4" class="form-label">DNI / RIF:</label>
            <div class="input-group mb-3">
                {!! Form::select('letra', ['J' => 'J', 'V' => 'V', 'G' => 'G'], $letra, ['id' => 'letra', 'required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                {!! Form::text('idcard_supplier', null, array( 'minlength' => '8', 'maxlength' => '9', 'onkeypress' => 'return dniRIF(event);', 'onkeyup' => 'return validoDocumento(document.getElementById("letra").value, this.value);', 'autocomplete' => 'off', 'required', 'placeholder' => 'Número de DNI ó RIF','class' => 'form-control form-control-sm')) !!}
            </div>
            
            <div id="validoCedula" class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-4">
            <label for="inputAddress" class="form-label">Teléfono:</label>
            {!! Form::text('phone_supplier', null, array('minlength' => '11', 'maxlength' => '11', 'onkeypress' => 'return validoTelefono(event)', 'autocomplete' => 'off', 'placeholder' => 'Ingrese el número de teléfono del cliente','class' => 'form-control form-control-sm')) !!}

        </div>
        <div class="col-4">
            <label for="inputAddress2" class="form-label">Correo Electrónico:</label>
            {!! Form::email('email_supplier', null, array('autocomplete' => 'off', 'placeholder' => 'Ingrese el correo electronico del cliente','class' => 'form-control form-control-sm')) !!}

        </div>
        <div class="col-md-12">
            <label for="inputCity" class="form-label">Dirección:</label>
            {!! Form::textarea('address_supplier', null, array('rows'=> 3, 'autocomplete' => 'off', 'required', 'placeholder' => 'Ingrese la dirección del cliente','class' => 'form-control form-control-sm')) !!}
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-md-3">
            <label for="inputState" class="form-label">Estado de residencia:</label>
            {!! Form::select('id_state', $estados, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-md-3">
            <label for="inputZip" class="form-label">Código postal: </label>
            {!! Form::text('zip_supplier', null, ['class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'placeholder' => 'Ingrese el código postal del cliente',]) !!}
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>
        </div>
        <div class="col-3 d-flex align-items-center mt-auto mb-2 justify-content-center">
            <div class="form-check">
                
                {!! Form::checkbox('taxpayer_supplier', '1', '', ['onchange' => 'validoCheck(this);','id' =>'taxpayer_supplier', 'class' => 'form-check-input'],) !!}
                <label class="form-check-label">
                    ¿Agente de retención?
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <label for="inputZip" class="form-label">Porcentaje de Retención:</label>
            {!! Form::select('porcentual_amount_tax_supplier', ['100' => '100%', '75' => '75%'], null, ['id' => 'porcentual_amount_tax_supplier', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
        </div>
    </div>
    </div>



</x-cards>
</div>


</div>
<div class="row text-center">
    <x-cards size="12">
        <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
        <input class="btn btn-danger" type="reset" value="Deshacer">
    </x-cards> 
    </div>
    {!! Form::close() !!}
    @endsection