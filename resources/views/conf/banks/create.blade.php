@section('side-title', 'Crear una nuevo banco')

@section('side-body')
{!! Form::open(array('route' => 'banks.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Nombre del banco</label>
        {!! Form::text('name_bank', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre del banco','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre del banco
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Descripción del banco</label>
        {!! Form::textarea('description_bank', null, array('rows'=> 3, 'autocomplete' => 'off', 'required', 'placeholder' => 'Ingrese la descripción del banco','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar la descripción del banco
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Número de cuenta</label>
        {!! Form::text('account_number_bank', null, array('minlength' => '20', 'maxlength' => '20', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el número de la cuenta','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el número de la cuenta
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Tipo de cuenta contable asociada</label>
        {!! Form::select('otro', $typeLedger, null, ['onchange' => 'selectSubAccount(this.value, "c")', 'required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el tipo de cuenta contable asociada
        </div>
    </div> 

    <div class="col-md-12" id="subcuenta" style="display: none">
        <label class="form-label">Cuenta contable asociada</label>
        <select class="form-select form-control-sm" name="id_ledger_account" required id="subcuentas"></select>
        <div  class="invalid-feedback">
            Para guardar debe ingresar la cuenta contable asociada
        </div>
    </div> 
</div>

<x-btns-save side="true"/>
    {!! Form::close() !!}

@endsection



