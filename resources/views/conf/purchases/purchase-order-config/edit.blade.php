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
        'route' => ['order-config.update', $data->id_purchase_order_config],
    ]) !!}
    <div class="row">
        <x-cards>
            <table class="table table-bordered table-sm">
                <tr>
                    <td>Correlativo:</td>
                    <td>{!! Form::text('correlative_purchase_order_config', null, [
                        'id' => 'correlative_purchase_order_config',
                        'autocomplete' => 'off',
                        'required',
                        'class' => 'form-control form-control-sm',
                    ]) !!}</td>
                </tr>
                <tr>
                    <td>Nombre de impresión:</td>
                    <td>{!! Form::text('print_name_purchase_order_config', null, [
                        'id' => 'print_name_purchase_order_config',
                        'autocomplete' => 'off',
                        'required',
                        'class' => 'form-control form-control-sm',
                    ]) !!}</td>
                </tr>
                <tr>
                    <td>Numero de control:</td>
                    <td>{!! Form::text('control_number_purchase_order_config', null, [
                        'id' => 'control_number_purchase_order_config',
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
