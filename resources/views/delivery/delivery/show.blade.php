@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')

    <div class="row g-3">
        <x-cards>
            <div class="d-flex flex-row mb-3">
                <div class="ms-auto">
                    @if ($data->state_delivery == 1)
                        @if (Gate::check('delivery-edit') || Gate::check('adm-edit'))
                            <a class="btn btn-warning btn-sm btn-icon-split"
                                href="{{ route('delivery.aprove', $data->id_delivery) }}">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <span class="text">Procesar</span>
                            </a>
                        @endif
                    @endif
                </div>
                <div class="ml-3">
                    @if ($data->state_delivery == 2)
                        @if (Gate::check('delivery-edit') || Gate::check('adm-edit'))
                            <a class="btn btn-success btn-sm btn-icon-split"
                                href="{{ route('delivery.finalice', $data->id_delivery) }}">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <span class="text">Finalizar</span>
                            </a>
                        @endif
                    @endif
                </div>
                @if ($data->state_delivery != 3 && $data->state_delivery != 4)
                    <div class="ml-3">
                        <a class="btn btn-danger btn-sm btn-icon-split"
                            href="{{ route('delivery.cancel', $data->id_delivery) }}">
                            <span class="icon text-white-50">
                                <i class="fas fa-times-circle"></i>
                            </span>
                            <span class="text">Anular</span>
                        </a>
                    </div>
                @endif
            </div>
                <table class="table table-sm table-bordered mb-3">
                    <tr>
                        <td class="align-middle">Estado de la orden: </td>
                        <td class="align-middle">
                            @switch($data->state_delivery)
                                @case(1)
                                    <span class="text-warning">Por despachar</span>
                                @break

                                @case(2)
                                    <span class="text-warning">En Proceso</span>
                                @break

                                @case(3)
                                    <span class="text-success">Despachado</span>
                                @break

                                @default
                                    <span class="text-danger">Canelado</span>
                            @endswitch
                        </td>
                    </tr>

                    <tr>
                        <td class="align-middle"width="25%">Nro de Guía:</td>
                        <td class="align-middle">{{ $data->guide_delivery }}</td>
                    </tr>

                    <tr>
                        <td class="align-middle">Fecha:</td>
                        <td class="align-middle">{{ $data->date_delivery }}</td>
                    </tr>

                    <tr>
                        <td class="align-middle">Chofer:</td>
                        <td class="align-middle">{{ $chofer->firts_name_worker }} {{ $chofer->last_name_worker }}</td>
                    </tr>

                    <tr>
                        <td class="align-middle">Celetero:</td>
                        <td class="align-middle">{{ $caletero->firts_name_worker }} {{ $caletero->last_name_worker }}</td>
                    </tr>

                    <tr>
                        <td class="align-middle">Zona:</td>
                        <td class="align-middle">{{ $data->name_zone }}</td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered mb-0">
                    <tr>
                        <td colspan="2" class="text-center align-middle">Facturas</td>
                    </tr>
                    @foreach ($facturas as $k => $factura)
                        @if ($k % 2 == 0)
                            <tr>
                                <td class="align-middle"><a
                                        href="{{ route('invoicing.show', $factura->id_invoicing) }}">{{ $factura->ref_name_invoicing }}
                                        - {{ $clientes[$k]->name_client }}</a></td>
                            @else
                                <td class="align-middle"><a
                                        href="{{ route('invoicing.show', $factura->id_invoicing) }}">{{ $factura->ref_name_invoicing }}
                                        - {{ $clientes[$k]->name_client }}</a></td>
                            </tr>
                        @endif
                    @endforeach
                </table>
        </x-cards>
    </div>



@endsection
