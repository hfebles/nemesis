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
                    <a href="" class="btn btn-sm btn-info btn-icon-split ml-auto">
                        <span class="icon text-white-50">
                            <i class="fas fa-print"></i>
                        </span>
                        <span class="text">Imprimir</span>
                    </a>



                    @if ($data->id_order_state != 12 && $data->id_order_state != 10)
                        @if (Gate::check('sales-invoices-create') || Gate::check('adm-create'))
                            <a href="{{ route('purchase.edit', $data->id_purchase) }}"
                                class="btn btn-sm btn-warning btn-icon-split ml-3">
                                <span class="icon text-white-50">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="text">Editar</span>
                            </a>
                        @endif
                    @endif
                    @if ($data->id_order_state == 11 || $data->id_order_state == 12)
                        @if (Gate::check($conf['group'] . '-edit') || Gate::check('adm-edit'))
                            <a class="btn btn-sm @if ($data->id_order_state == 11) btn-warning @else btn-success disabled @endif btn-icon-split ml-3"
                                data-bs-toggle="modal" data-bs-target="#exampleModal"><span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">
                                    @if ($data->id_order_state == 11)
                                        Recepcion
                                    @else
                                        Recibido
                                    @endif
                                </span></a>
                        @endif
                    @endif
                    @if ($data->id_order_state != 10 && $data->id_order_state != 9 && $data->id_order_state != 12)
                        @if (Gate::check('sales-invoices-delete') || Gate::check('adm-delete'))
                            <a href="{{ route('purchase.cancel-purchase', $data->id_purchase) }}"
                                class="btn btn-sm btn-danger btn-icon-split ml-3">
                                <span class="icon text-white-50">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                                <span class="text">Anular</span>
                            </a>
                        @endif
                    @endif
                </div>
                <div class="col-sm-12">
                    <table class="table table-sm table-bordered">
                        <tr>
                            <td width="80%" class="text-end">Fecha:</td>
                            <td width="10%" class="text-start">
                                <span id="razon_social">{{ date('d-m-Y', strtotime($data->date_purchase)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end">Nro control:</td>
                            <td class="text-start">
                                <span id="razon_social">{{ $data->ref_name_purchase }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table table-sm table-bordered mb-4">
                        <tr>
                            <td width="25%">Razón social:</td>
                            <td><span id="razon_social">{{ $data->name_supplier }}</span></td>
                        </tr>
                        <tr>
                            <td width="25%">Cédula ó R.I.F.:</td>
                            <td><span id="dni">{{ $data->idcard_supplier }}</span></td>
                        </tr>
                        <tr>
                            <td width="25%">Teléfono: </td>
                            <td><span id="telefono">{{ $data->phone_supplier }}</span></td>
                        </tr>
                        <tr>
                            <td width="25%">Dirección: </td>
                            <td><span id="direccion">{{ $data->address_supplier }}</span></td>
                        </tr>
                        <tr>
                            <td width="25%">Responsable: </td>
                            <td><span>{{ $data->firts_name_worker }} {{ $data->last_name_worker }}</span></td>
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
                                    {{ number_format($data->no_exempt_amout_purchase / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>
                            <th scope="col" class="text-end align-middle">BASE IMPONIBLE: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">Bs.
                                    {{ number_format($data->no_exempt_amout_purchase, 2, ',', '.') }}</p>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-end align-middle">EXENTO: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->exempt_amout_purchase / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>
                            <th scope="col" class="text-end align-middle">EXENTO: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="exentos"></p>Bs.
                                {{ number_format($data->exempt_amout_purchase, 2, ',', '.') }}
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-end align-middle">IVA: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->total_amount_tax_purchase / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>
                            <th scope="col" class="text-end align-middle">IVA:</th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="totalIVaas">Bs.
                                    {{ number_format($data->total_amount_tax_purchase, 2, ',', '.') }}</p>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-end align-middle">TOTAL A PAGAR: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->total_amount_purchase / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>
                            <th scope="col" class="text-end align-middle">TOTAL A PAGAR: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="totalTotals">Bs.
                                    {{ number_format($data->total_amount_purchase, 2, ',', '.') }}</p>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal">Validar pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 g-3">
                            <p class="text-center">Desea recibir el pedido:</p>
                            <table class="table table-sm table-hover table-borderes">
                                <tr>
                                    <th>Producto:</th>
                                    <th>Solicitado:</th>
                                    <th>Recibiendo:</th>
                                </tr>
                                {!! Form::open([
                                    'route' => 'purchase.receptions',
                                    'method' => 'POST',
                                    'novalidate',
                                    'class' => 'needs-validation',
                                    'id' => 'myForm',
                                ]) !!}
                                @for ($i = 0; $i < count($dataProducts); $i++)
                                    @foreach ($dataProducts[$i] as $k => $products)
                                    @if ($dataProdcs['pendiente'][$i] > 0)

                                    <tr>
                                        <td class="align-middle">{{ $products->name_product }}
                                            {{ $products->name_presentation_product }}
                                            {{ $products->short_unit_product }}
                                        </td>
                                        <td class="text-center align-middle" width="10%">
                                            {{ number_format(json_decode($dataProdcs['pendiente'][$i]), 2, ',', '.') ?? '' }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="id_product[]"
                                                value="{{ $products->id_product }}">
                                            <input type="number" name="cantidad[]"
                                                class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                    @else
                                    <input type="hidden" name="id_product[]"
                                    value="{{ $products->id_product }}">
                                <input type="number" name="cantidad[]"
                                    class="form-control form-control-sm">
                                    @endif
                                        
                                    @endforeach
                                @endfor
                            </table>
                        </div>
                        <input type="hidden" name="id_purchase" value="{{ $data->id_purchase }}">
                        <x-btns-save></x-btns-save>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endsection
