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
                <td>{{ $data[0]->correlative_invoicing_configutarion }}</td>
            </tr>
            <tr>
                <td>Nombre de impresión:</td>
                <td>{{ $data[0]->print_name_invoicing_configutarion }}</td>
            </tr>
            <tr>
                <td>Numero de control:</td>
                <td>{{ $data[0]->control_number_invoicing_configutarion }}</td>
            </tr>
            {{-- <tr>
                <td>Cuenta contable:</td>
                <td>{{ $data[0]->id_ledger_account }}</td>
            </tr> --}}

        </table>

    </x-cards>
</div>
    
@endsection
