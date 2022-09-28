@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
{!! Form::model($data[0], ['novalidate', 'class' => 'needs-validation', 'method' => 'PATCH', 'route' => ['invoices-config.update', $data[0]->id_invoicing_configutarion]]) !!}
<div class="row">
    <x-cards>
        <table class="table table-bordered table-sm">
            <tr>
                <td>Correlativo:</td>
                <td>{!! Form::text('correlative_invoicing_configutarion', null, array('id' => 'correlative_sale_order_configuration', 'autocomplete' => 'off','required', 'class' => 'form-control form-control-sm')) !!}</td>
            </tr>
            <tr>
                <td>Nombre de impresión:</td>
                <td>{!! Form::text('print_name_invoicing_configutarion', null, array('id' => 'print_name_sale_order_configuration', 'autocomplete' => 'off','required', 'class' => 'form-control form-control-sm')) !!}</td>
            </tr>
            <tr>
                <td>Numero de control:</td>
                <td>{!! Form::text('control_number_invoicing_configutarion', null, array('id' => 'control_number_sale_order_configuration', 'autocomplete' => 'off','required', 'class' => 'form-control form-control-sm')) !!}</td>
            </tr>
            {{-- <tr>
                <td>Cuenta contable:</td>
                <td>
                {!! Form::select('id_sub_ledger_account', $dataSubAcc, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                </td>
            </tr> --}}

        </table>

    </x-cards>
</div>
<x-btns-save />
{!! Form::close() !!}
@endsection
