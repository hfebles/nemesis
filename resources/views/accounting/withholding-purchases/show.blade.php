@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :edit="$conf['edit']" :group="$conf['group']" />
@endsection


@section('content')

    <div class="row g-4">
        <x-cards>

            <table class="table table-bordered table-hover table-sm mb-3">
                <tr>
                    <td class="fw-semibold">Razon Social:</td>
                    <td>{{ $data->name_supplier }}</td>

                    <td class="fw-semibold">Numero de comprobante:</td>
                    <td>{{ $data->voucher_number_whp }}</td>
                </tr>
                <tr>
                    <td class="fw-semibold">Fecha:</td>
                    <td>{{ date('d-m-Y', strtotime($data->date_whp)) }}</td>
                </tr>
                <tr>

                    <td class="fw-semibold">Tipo:</td>
                    <td>{{ $data->tipo }}</td>
                </tr>
                
            </table>

            <table class="table table-bordered table-hover table-sm mb-0">
                <tr>
                    <td>Factura</td>
                    <td>Impuesto a retener</td>
                    <td>Base Imponible</td>
                    <td>IVA Facturado</td>
                    <td>% Retenetido</td>
                    <td>IVA Retenido</td>
                </tr>
                <tr>
                    <td>{{ date('Y') }}/{{ $data->ref_name_purchase}}</td>
                    <td> IVA (16.0%) compras </td>
                    <td class="text-end">{{ $data->amount_base_imponible_whp }}</td>
                    <td class="text-end">{{ $data->amount_tax_invoice_whp }}</td>
                    <td class="text-end">{{ $data->porcentual_amount_tax_supplier }}%</td>
                    <td class="text-end">{{ $data->amount_tax_retention_whp }}</td>
                </tr>
            </table>

        </x-cards>
        
    </div>




@endsection
