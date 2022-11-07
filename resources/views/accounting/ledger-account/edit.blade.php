@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']"  />
@endsection



@section('content')
{!! Form::model($data, ['method' => 'PATCH', 'route' => ['ledger-account.update', $data->id_ledger_account]]) !!}
<div class="row g-3">
    <x-cards>
        <div class="row g-3">
            <div class="col-2 align-self-center pr-0">
                <label class="form-label mb-0">Grupo:</label>
            </div>
            <div class="col-md-3">
                {!! Form::select('id_type_ledger_account', $typeLedger, null, array('required', 'class' => 'form-select')) !!}
            </div>

            <div class="clearfix"></div>

            <div class="col-2 align-self-center pr-0">
                <label class="form-label mb-0">Nombre de la cuenta:</label>
            </div>
            <div class="col-md-3">
               {!! Form::text('name_ledger_account', null, array('autocomplete' => 'off', 'required', 'placeholder' => 'Nombre de usuario','class' => 'form-control')) !!}
            </div>
            
            <div class="clearfix"></div>

            <div class="col-2 align-self-center pr-0">
                <label class="form-label mb-0">Codigo de la cuenta:</label>
            </div>
            <div class="col-md-3">
                {!! Form::text('code_ledger_account', null, array('autocomplete' => 'off', 'required', 'placeholder' => 'Nombre de usuario','class' => 'form-control')) !!}
            </div>
        </div>

  

  
    </x-cards>
</div>
<x-btns-save />

    {!! Form::close() !!}


@endsection

