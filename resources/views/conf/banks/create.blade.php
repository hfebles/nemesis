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
        {!! Form::textarea('description_bank', null, array('rows'=> 3, 'autocomplete' => 'off', 'required', 'placeholder' => 'Ingrese la descripcion del banco','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar la descripcion del banco
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Numero de cuenta</label>
        {!! Form::text('account_number_bank', null, array('minlength' => '20', 'maxlength' => '20', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el porcentaje','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el procentaje
        </div>
    </div>

    {{-- <div class="col-md-12">
        <label class="form-label">Cuenta contable asociada</label>
        {!! Form::select('id_sub_ledger_account', $dataSubAcc, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar la cuenta contable asociada
        </div>
    </div>  --}}
</div>

<x-btns-save side="true"/>
    {!! Form::close() !!}

@endsection

