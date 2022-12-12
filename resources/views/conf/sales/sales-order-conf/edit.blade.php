@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
    {!! Form::model($data, [
        'novalidate',
        'class' => 'needs-validation',
        'method' => 'PATCH',
        'route' => ['order-config.update', $data->id_sale_order_configuration],
    ]) !!}
    <div class="row">
        <x-cards>
            <table class="table table-bordered table-sm">
                <tr>
                    <td>Correlativo:</td>
                    <td>{!! Form::text('correlative_sale_order_configuration', null, [
                        'id' => 'correlative_sale_order_configuration',
                        'autocomplete' => 'off',
                        'required',
                        'class' => 'form-control form-control-sm',
                    ]) !!}</td>
                </tr>
                <tr>
                    <td>Nombre de impresión:</td>
                    <td>{!! Form::text('print_name_sale_order_configuration', null, [
                        'id' => 'print_name_sale_order_configuration',
                        'autocomplete' => 'off',
                        'required',
                        'class' => 'form-control form-control-sm',
                    ]) !!}</td>
                </tr>
                <tr>
                    <td>Numero de control:</td>
                    <td>{!! Form::text('control_number_sale_order_configuration', null, [
                        'id' => 'control_number_sale_order_configuration',
                        'autocomplete' => 'off',
                        'required',
                        'class' => 'form-control form-control-sm',
                    ]) !!}</td>
                </tr>

            </table>

        </x-cards>
    </div>
    <x-btns-save />
    {!! Form::close() !!}
@endsection

