@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :edit="$conf['edit']" :group="$conf['group']" />
@endsection

@section('content')

<div class="row">
    <x-cards>
        <table class="table table-bordered table-sm">
            <tr>
                <td>Correlativo:</td>
                <td>{{ $data->correlative_sale_order_configuration }}</td>
            </tr>
            <tr>
                <td>Nombre de impresión:</td>
                <td>{{ $data->print_name_sale_order_configuration }}</td>
            </tr>
            <tr>
                <td>Numero de control:</td>
                <td>{{ $data->control_number_sale_order_configuration }}</td>
            </tr>
            <tr>
                <td>Cuenta contable:</td>
                <td>{{ $data->name_ledger_account }}</td>
            </tr>

        </table>

    </x-cards>
</div>
    
@endsection
