@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection


@section('content')
{!! Form::model($data, ['method' => 'PATCH', 'route' => ['withholding-sales.update', $data->id_withholding_iva_sale]]) !!}
    <div class="row g-4">
        <x-cards>

            <table class="table table-bordered table-hover table-sm mb-3">
                <tr>
                    <td class="fw-semibold">Razon Social:</td>
                    <td>{{ $data->name_client }}</td>

                    <td class="fw-semibold">Numero de comprobante:</td>
                    <td>{!! Form::text('voucher_number_whs', null, array('minlength' => '14', 'maxlength' => '14', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el número de la retencion','class' => 'form-control form-control-sm')) !!}</td>
                </tr>
                <tr>
                    <td class="fw-semibold">Fecha:</td>
                    <td>{{ date('d-m-Y', strtotime($data->date_whs)) }}</td>
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
                    <td>{{ date('Y') }}/{{ $data->ref_name_invoicing}}</td>
                    <td> IVA (16.0%) ventas </td>
                    <td class="text-end">{{ $data->amount_base_imponible_whs }}</td>
                    <td class="text-end">{{ $data->amount_tax_invoice_whs }}</td>
                    <td class="text-end">{{ $data->porcentual_amount_tax_client }}%</td>
                    <td class="text-end">{{ $data->amount_tax_retention_whs }}</td>
                </tr>
            </table>

        </x-cards>
        
    </div>
    <x-btns-save />
    {!! Form::close() !!}


@endsection
