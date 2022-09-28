@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')

<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Grupo</h5>
                    <table class="table table-sm table-bordered table-hover mb-0">
                        <tr>
                            <td width="50%"  class="bg-dark text-white p-1 align-middle">Nombre del grupo: </td>
                            <td class="p-1">{{$dataG->name_group}}</td>
                        </tr>

                        <tr>
                            <td width="30%" class="bg-dark text-white p-1">Codigo: </td>
                            <td class="p-1">{{$dataG->code_group}}</td>
                        </tr>

                        <tr>
                            <td width="30%" class="bg-dark text-white p-1">Activo:</td>
                            <td class="p-1">
                                @switch($dataG->enabled_group)
                                    @case(1)
                                        <div class="form-check form-switch p-0">
                                            <input class="form-check-input ml-0" type="checkbox" checked disabled>
                                        </div>
                                        @break
                                    @default
                                        <div class="form-check form-switch p-0">
                                            <input class="form-check-input ml-0" type="checkbox" disabled>
                                        </div>
                                @endswitch
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
<pre>

</pre>
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row  mb-3">
                    <h5 class="card-title">Subelementos</h5>
                    @if(Gate::check('adm-create') || Gate::check('accounting-ledger-create'))
                        <div class="ms-auto">
                            
                            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#SubGrupo">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Subgrupo</span>
                            </a>
                        </div>
                        <div class="ml-3">
                            <a class="btn btn-success " href="#" data-toggle="modal" data-target="#Cuenta">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Cuentas</span>
                            </a>
                        </div>
                        <div class="ml-3">
                            <a class="btn btn-success " href="#" data-toggle="modal" data-target="#SubCuenta">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Sub cuentas</span>
                            </a>
                        </div>
                        <div class="ml-3">
                            <a class="btn btn-success " href="#" data-toggle="modal" data-target="#SubCuenta2">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Sub cuentas2</span>
                            </a>
                        </div>
                        <div class="ml-3">
                            <a class="btn btn-success " href="#" data-toggle="modal" data-target="#SubCuenta3">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Sub cuentas3</span>
                            </a>
                        </div>
                        <div class="ml-3">
                            <a class="btn btn-success " href="#" data-toggle="modal" data-target="#SubCuenta4">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Sub cuentas4</span>
                            </a>
                        </div>
                    @endif
                </div>
                <table class="table table-sm table-hover table-bordered ">
                    <thead class="bg-dark text-white text-uppercase align-middle">
                        <tr class="">

                            <td width="14%">Codigo - Grupo</td>

                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr>
                            <td class="p-1">{{$dataG->code_group}} - {{$dataG->name_group}}</td>
                        </tr>
                        @for ($i = 0; $i < count($dataSG); $i++)
                            <tr>
                                <td class="p-1" >{{$dataG->code_group}}.{{$dataSG[$i]->code_sub_group}} - {{$dataSG[$i]->name_sub_group}}</td>
                            </tr>
                            @for ($o = 0; $o < count($dataLA[$i]); $o++)
                                <tr>
                                    <td class="p-1" >
                                        {{$dataG->code_group}}.{{$dataSG[$i]->code_sub_group}}.{{$dataLA[$i][$o]->code_ledger_account}} 
                                        - {{$dataLA[$i][$o]->name_ledger_account}}
                                    </td>
                                </tr>
                                @for ($d = 0; $d < count($dataSLA[$i][$o]); $d++)
                                    <tr>
                                        
                                        <td class="p-1" >
                                            {{$dataG->code_group}}.{{$dataSG[$i]->code_sub_group}}.{{$dataLA[$i][$o]->code_ledger_account}}.{{$dataSLA[$i][$o][$d]->code_sub_ledger_account}} 
                                            - {{$dataSLA[$i][$o][$d]->name_sub_ledger_account}}
                                        </td>
                                    </tr>
                                    @for ($c = 0; $c < count($s2[$i][$o][$d]); $c++)
                                        <tr>
                                            <td class="p-1">
                                                {{$dataG->code_group}}.{{$dataSG[$i]->code_sub_group}}.{{$dataLA[$i][$o]->code_ledger_account}}.{{$dataSLA[$i][$o][$d]->code_sub_ledger_account}}.{{$s2[$i][$o][$d][$c]->code_sub_ledger_account2}} 
                                            - {{$s2[$i][$o][$d][$c]->name_sub_ledger_account2}}</td>
                                        </tr>
                                        @for ($g = 0; $g < count($s3[$i][$o][$d][$c]); $g++)
                                            <tr>
                                                <td class="p-1">
                                                    {{$dataG->code_group}}.{{$dataSG[$i]->code_sub_group}}.{{$dataLA[$i][$o]->code_ledger_account}}.{{$dataSLA[$i][$o][$d]->code_sub_ledger_account}}.{{$s2[$i][$o][$d][$c]->code_sub_ledger_account2}}.{{$s3[$i][$o][$d][$c][$g]->code_sub_ledger_account3}} 
                                                 - {{$s3[$i][$o][$d][$c][$g]->name_sub_ledger_account3}}</td>
                                            </tr>
                                            @for ($j = 0; $j < count($s4[$i][$o][$d][$c][$g]); $j++)
                                                <tr>
                                                    <td class="p-1">
                                                        {{$dataG->code_group}}.{{$dataSG[$i]->code_sub_group}}.{{$dataLA[$i][$o]->code_ledger_account}}.{{$dataSLA[$i][$o][$d]->code_sub_ledger_account}}.{{$s2[$i][$o][$d][$c]->code_sub_ledger_account2}}.{{$s3[$i][$o][$d][$c][$g]->code_sub_ledger_account3}}.{{$s4[$i][$o][$d][$c][$g][$j]->code_sub_ledger_account4}} 
                                                    - {{$s4[$i][$o][$d][$c][$g][$j]->name_sub_ledger_account4}}</td>
                                                </tr>
                                            @endfor
                                        @endfor
                                    @endfor
                                @endfor
                            @endfor
                        @endfor
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
</div>



<div class="modal fade" id="SubGrupo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar un subgrupo</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    {!! Form::open(array('route' => 'sub-group-accounting.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label">Código: </label>
                                {!! Form::text('code_sub_group', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el código del nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nombre del subgrupo: </label>
                                {!! Form::text('name_sub_group', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre deñ nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tipo: </label>
                                {!! Form::select('id_type_ledger_account', $dataType, null, ['class' => 'form-select form-control-sm', 'required', ]) !!}
                            </div>
                        </div>
                        <div class="row text-center mt-4">
                            <div>
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <input class="btn btn-danger" type="reset" value="Deshacer">
                            </div>
                        </div>
                    {!! Form::hidden('id_group', $dataG->id_group) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    
<div class="modal fade" id="Cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar una cuenta</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    {!! Form::open(array('route' => 'ledger-account.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label">subGrupo: </label>
                                {!! Form::select('id_sub_group', $dataSGPluck, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Código: </label>
                                {!! Form::text('code_ledger_account', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el código del nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nombre de la cuenta: </label>
                                {!! Form::text('name_ledger_account', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre deñ nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tipo: </label>
                                {!! Form::select('id_type_ledger_account', $dataType, null, ['class' => 'form-select form-control-sm', 'required', ]) !!}
                            </div>
                        </div>
                        <div class="row text-center mt-4">
                            <div>
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <input class="btn btn-danger" type="reset" value="Deshacer">
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SubCuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar una cuenta</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                {!! Form::open(array('route' => 'sub-ledger-account.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label">Cuenta: </label>
                                {!! Form::select('id_ledger_account', $dataLAPluck, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Código: </label>
                                {!! Form::text('code_sub_ledger_account', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el código del nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nombre de la subcuenta: </label>
                                {!! Form::text('name_sub_ledger_account', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre deñ nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tipo: </label>
                                {!! Form::select('id_type_ledger_account', $dataType, null, ['class' => 'form-select form-control-sm', 'required', ]) !!}
                            </div>
                        </div>
                        <div class="row text-center mt-4">
                            <div>
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <input class="btn btn-danger" type="reset" value="Deshacer">
                            </div>
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="SubCuenta2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar una cuenta 2</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                {!! Form::open(array('route' => 'sub-ledger-account2.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label">Cuenta: </label>
                                {!! Form::select('id_sub_ledger_account', $s, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Código: </label>
                                {!! Form::text('code_sub_ledger_account2', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el código del nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nombre de la subcuenta: </label>
                                {!! Form::text('name_sub_ledger_account2', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre deñ nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tipo: </label>
                                {!! Form::select('id_type_ledger_account', $dataType, null, ['class' => 'form-select form-control-sm', 'required', ]) !!}
                            </div>
                        </div>
                        <div class="row text-center mt-4">
                            <div>
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <input class="btn btn-danger" type="reset" value="Deshacer">
                            </div>
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="SubCuenta3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar una cuenta3</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                {!! Form::open(array('route' => 'sub-ledger-account3.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label">Cuenta: </label>
                                {!! Form::select('id_sub_ledger_account2', $s3p, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Código: </label>
                                {!! Form::text('code_sub_ledger_account3', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el código del nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nombre de la subcuenta: </label>
                                {!! Form::text('name_sub_ledger_account3', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre deñ nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tipo: </label>
                                {!! Form::select('id_type_ledger_account', $dataType, null, ['class' => 'form-select form-control-sm', 'required', ]) !!}
                            </div>
                        </div>
                        <div class="row text-center mt-4">
                            <div>
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <input class="btn btn-danger" type="reset" value="Deshacer">
                            </div>
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="SubCuenta4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar una cuenta</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                {!! Form::open(array('route' => 'sub-ledger-account4.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label">Cuenta: </label>
                                {!! Form::select('id_sub_ledger_account3', $s4p, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Código: </label>
                                {!! Form::text('code_sub_ledger_account4', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el código del nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nombre de la subcuenta: </label>
                                {!! Form::text('name_sub_ledger_account4', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre deñ nuevo subgrupo','class' => 'form-control form-control-sm')) !!}
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tipo: </label>
                                {!! Form::select('id_type_ledger_account', $dataType, null, ['class' => 'form-select form-control-sm', 'required', ]) !!}
                            </div>
                        </div>
                        <div class="row text-center mt-4">
                            <div>
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <input class="btn btn-danger" type="reset" value="Deshacer">
                            </div>
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
        
@endsection


@section('js')

<script>
    (function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()

</script>
    
@endsection