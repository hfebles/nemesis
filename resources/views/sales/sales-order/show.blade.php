@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')

    @if ($message = Session::get('message'))
        <div class="row">
            <x-cards size="12" :message="$message" />
        </div>
    @endif



    <div class="row">
        <x-cards>



            <div class="row g-3">


                <div class="col-sm-12 d-flex">
                    <a href="{{ route('sales.invoices-print', ['id' => $data->id_sales_order, 'type' => 1]) }}"
                        class="btn btn-sm btn-info btn-icon-split ml-auto">
                        <span class="icon text-white-50">
                            <i class="fas fa-print"></i>
                        </span>
                        <span class="text">Imprimir</span>
                    </a>



                    @if ($data->id_order_state != 2 && $data->id_order_state != 6)
                        @if (Gate::check('sales-invoices-create') || Gate::check('adm-create'))
                            <div class="dropdown  ml-3">
                                <button class="btn btn-sm btn-success dropdown-toggle " type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Acciones
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal">Facturar</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('sales.deliveries-notes-validate', $data->id_sales_order) }}">Nota
                                            de entrega</a></li>
                                </ul>
                            </div>
                        @endif
                    @endif
                    @if ($data->id_order_state != 2 && $data->id_order_state != 7)
                        @if (Gate::check($conf['group'] . '-edit') || Gate::check('adm-edit'))
                            <a href="{{ route('sales-order.edit', $data->id_sales_order) }}"
                                class="btn btn-sm btn-warning btn-icon-split ml-3">
                                <span class="icon text-white-50">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="text">Editar</span>
                            </a>
                        @endif
                    @endif
                    @if (Gate::check('sales-invoices-delete') || Gate::check('adm-delete'))
                        <a href="/sales/cancel/{{ $data->id_sales_order }}"
                            class="btn btn-sm btn-danger btn-icon-split ml-3">
                            <span class="icon text-white-50">
                                <i class="fas fa-times-circle"></i>
                            </span>
                            <span class="text">Anular</span>
                        </a>
                    @endif



                </div>



                <div class="col-sm-12">

                    <table class="table table-sm table-bordered">
                        <tr>
                            <td width="80%" class="text-end">Fecha:</td>
                            <td width="10%" class="text-start">
                                <span id="razon_social">{{ date('d-m-Y', strtotime($data->date_sales_order)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end">Nro control:</td>
                            <td class="text-start">
                                <span id="razon_social">{{ $data->ref_name_sales_order }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table table-sm table-bordered mb-4">

                        <tr>
                            <td width="25%">Razón social:</td>
                            <td>
                                <span id="razon_social">{{ $data->name_client }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">Cédula ó R.I.F.:</td>
                            <td>
                                <span id="dni">{{ $data->idcard_client }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">Teléfono: </td>
                            <td>
                                <span id="telefono">{{ $data->phone_client }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">Dirección: </td>
                            <td>
                                <span id="direccion">{{ $data->address_client }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle" width="25%">Tipo de Pago: </td>
                            <td>
                                <span id="direccion">
                                    @switch($data->type_payment)
                                        @case(1)
                                            Contado
                                        @break

                                        @default
                                            Credito
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">Vendedor: </td>
                            <td>
                                <span>{{ $data->firts_name_worker }} {{ $data->last_name_worker }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table table-sm  table-bordered border-dark mb-4" id="myTable">
                        <tr>

                            <th scope="col" colspan="2" class="align-middle">DESCRIPCIÓN</th>
                            <th scope="col" class="text-center align-middle" width="10%">CANTIDAD</th>
                            <th scope="col" class="text-center align-middle" width="10%">P/U</th>
                            <th scope="col" class="text-center align-middle " width="10%">SUB-TOTAL</th>
                        </tr>

                        @for ($i = 0; $i < count($dataProducts); $i++)


                            @foreach ($dataProducts[$i] as $k => $products)
                                <tr>

                                    <th scope="col" colspan="2" class="align-middle">{{ $products->name_product }}
                                        {{ $products->name_presentation_product }} {{ $products->short_unit_product }}
                                        @if ($products->tax_exempt_product == 1)
                                            (E)
                                        @endif
                                    </th>
                                    <th scope="col" class="text-center align-middle" width="10%">
                                        {{ number_format($obj['cantidad'][$i], 2, ',', '.') }}</th>
                                    <th scope="col" class="text-center align-middle" width="10%">
                                        {{ number_format($obj['precio_producto'][$i], 2, ',', '.') }}</th>
                                    <th scope="col" class="text-center align-middle " width="10%">
                                        {{ number_format($obj['precio_producto'][$i] * $obj['cantidad'][$i], 2, ',', '.') }}
                                    </th>
                                </tr>
                            @endforeach
                        @endfor
                    </table>

                    <table class="table table-sm table-bordered mb-0">
                        <tr>
                            <th scope="col" class="text-end align-middle">TIPO DE TASA DE CAMBIO: <span
                                    class="text-danger">{{ date('d-m-Y', strtotime($data->date_exchange)) }}</span></th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0'>$ {{ number_format($data->amount_exchange, 2, ',', '.') }}</p>
                            </th>
                            <th colspan="2" scope="col" class="text-end align-middle"></th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-end align-middle">BASE IMPONIBLE: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->no_exempt_amout_sales_order / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>

                            <th scope="col" class="text-end align-middle">BASE IMPONIBLE: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">Bs.
                                    {{ number_format($data->no_exempt_amout_sales_order, 2, ',', '.') }}</p>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-end align-middle">EXENTO: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->exempt_amout_sales_order / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>

                            <th scope="col" class="text-end align-middle">EXENTO: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="exentos"></p>Bs.
                                {{ number_format($data->exempt_amout_sales_order, 2, ',', '.') }}
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-end align-middle">IVA: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->total_amount_tax_sales_order / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>

                            <th scope="col" class="text-end align-middle">IVA:</th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="totalIVaas">Bs.
                                    {{ number_format($data->total_amount_tax_sales_order, 2, ',', '.') }}</p>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-end align-middle">TOTAL A PAGAR: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->total_amount_sales_order / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>

                            <th scope="col" class="text-end align-middle">TOTAL A PAGAR: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="totalTotals">Bs.
                                    {{ number_format($data->total_amount_sales_order, 2, ',', '.') }}</p>
                            </th>
                        </tr>
                    </table>

                </div>
            </div>

        </x-cards>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal">Validar pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 g-3">
                            <p class="text-center">Desea facturar el pedido: {{ $data->ref_name_sales_order }}</p>

                            <div class="text-center"><a href="/sales/invoicing/validate/{{ $data->id_sales_order }}"
                                    class="btn btn-success">Validar</a></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
