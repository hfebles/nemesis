@section('side-title', 'Crear una nueva unidad')

@section('side-body')
{!! Form::open(array('route' => 'taxes.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Nombre del Impuesto</label>
        {!! Form::text('name_tax', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre del impuesto','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre del impuesto
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Porcentaje</label>
        {!! Form::text('amount_tax', null, array('minlength' => '1', 'maxlength' => '4', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el porcentaje','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el procentaje
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Cuenta contable asociada</label>
        {!! Form::select('id_sub_ledger_account', $dataSubAcc, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar la cuenta contable asociada
        </div>
    </div>

        <div class="col-md-12">
                {!! Form::checkbox('billable_tax', '1', '', ['id' =>'billable_tax', 'class' => 'form-check-input ml-3'],) !!}
                <label class="form-check-label ml-5">
                    ¿Impuesto facturable?
                </label>
    </div>

    

</div>

<x-btns-save side="true"/>
    {!! Form::close() !!}

@endsection

