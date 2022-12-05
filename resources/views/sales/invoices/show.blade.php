@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        <x-cards>
            <div class="row g-3">
                <div class="col-sm-12 d-flex">
                    @if ($data->id_order_state == 3)
                        <span class="text-danger h5 align-middle">{{ $data->estado }}</span>
                    @endif
                    <a target="_blank" href="{{ route('sales.invoices-print', ['id' => $data->id_invoicing, 'type' => 2]) }}"
                        class="btn btn-sm btn-info btn-icon-split ml-auto">
                        <span class="icon text-white-50">
                            <i class="fas fa-print"></i>
                        </span>
                        <span class="text">Imprimir</span>
                    </a>
                    @if ($data->id_order_state != 5 && Gate::check('payment-create') && $data->id_order_state != 3)
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal"
                            class="btn btn-sm btn-success btn-icon-split ml-3">
                            <span class="icon text-white-50">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span class="text">Pagar</span>
                        </a>
                    @endif
                    @if (Gate::check($conf['group'] . '-delete') && $data->id_order_state != 3)
                        <a href="{{ route('sales.cancel-invoices', $data->id_invoicing) }}"
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
                                <span>{{ date('d-m-Y', strtotime($data->date_invoicing)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end">Nro control:</td>
                            <td class="text-start">
                                @if ($data->id_order_state == 3)
                                    <span class="text-danger">{{ $data->ref_name_invoicing }}</span>
                                @else
                                    <span>{{ $data->ref_name_invoicing }}</span>
                                @endif

                            </td>
                        </tr>
                        @if ($data->taxpayer_client == 1)
                            <tr>
                                <td class="text-end">Retención:</td>
                                <td class="text-start">
                                    @if ($data->voucher_number_whs == null)
                                    <a onclick="abreModal()" class="btn btn-sm btn-success btn-circle" type="button"><i
                                        class="fas fa-edit fa-lg"></i></a>
                                    @else
                                        <a href="{{ route('withholding-sales.show', $data->id_withholding_iva_sale) }}">{{ $data->voucher_number_whs }}</a>
                                    @endif

                                </td>
                            </tr>
                        @endif
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

                                    <td colspan="2" class="align-middle">{{ $products->name_product }}
                                        {{ $products->name_presentation_product }} {{ $products->short_unit_product }}
                                        @if ($products->tax_exempt_product == 1)
                                            (E)
                                        @endif
                                    </td>
                                    <td class="text-center align-middle" width="10%">
                                        {{ number_format($obj['cantidad'][$i], 2, ',', '.') }}</td>
                                    <td class="text-center align-middle" width="10%">Bs.
                                        {{ number_format($obj['precio_producto'][$i], 2, ',', '.') }}</td>
                                    <td class="text-center align-middle " width="10%">Bs.
                                        {{ number_format($obj['precio_producto'][$i] * $obj['cantidad'][$i], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endfor
                    </table>

                    <table class="table table-sm table-bordered mb-0">
                        <tr>
                            <td scope="col" class="text-end align-middle">TIPO DE TASA DE CAMBIO: <span
                                    class="text-danger">{{ date('d-m-Y', strtotime($data->date_exchange)) }}</span></td>
                            <td scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0'>$ {{ number_format($data->amount_exchange, 2, ',', '.') }}</p>
                            </td>
                            <td colspan="2" scope="col" class="text-end align-middle"></td>
                        </tr>
                        <tr>
                            <td scope="col" class="text-end align-middle">BASE IMPONIBLE: </th>
                            <td scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->no_exempt_amout_invoicing / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </td>

                            <td scope="col" class="text-end align-middle">BASE IMPONIBLE: </td>
                            <td scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">Bs.
                                    {{ number_format($data->no_exempt_amout_invoicing, 2, ',', '.') }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td scope="col" class="text-end align-middle">EXENTO: </td>
                            <td scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->exempt_amout_invoicing / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </td>

                            <td scope="col" class="text-end align-middle">EXENTO: </td>
                            <td scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="exentos"></p>Bs.
                                {{ number_format($data->exempt_amout_invoicing, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td scope="col" class="text-end align-middle">IVA: </td>
                            <td scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="subFacs">$
                                    {{ number_format($data->total_amount_tax_invoicing / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </td>

                            <td scope="col" class="text-end align-middle">IVA:</td>
                            <td scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0' id="totalIVaas">Bs.
                                    {{ number_format($data->total_amount_tax_invoicing, 2, ',', '.') }}</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="text-end align-middle text-dark">TOTAL A PAGAR: </th>
                            <th scope="col" class="text-end align-middle">
                                <p class='align-middle mb-0 text-dark' id="subFacs">$
                                    {{ number_format($data->total_amount_invoicing / $data->amount_exchange, 2, ',', '.') }}
                                </p>
                            </th>

                            <th scope="col" class="text-end align-middle text-dark">TOTAL A PAGAR: </th>
                            <th scope="col" class="text-end align-middle text-dark">
                                <p class='align-middle mb-0' id="totalTotals">Bs.
                                    {{ number_format($data->total_amount_invoicing, 2, ',', '.') }}</p>
                            </th>
                        </tr>

                        @if ($data->taxpayer_client == 1)
                            @if ($data->porcentual_amount_tax_client == 75)
                                <tr>
                                    <th scope="col" class="text-end align-middle text-dark">IVA RETENIDO: </th>
                                    <th scope="col" class="text-end align-middle text-dark">
                                        <p class='align-middle mb-0' id="subFacs">$
                                            {{ number_format(($data->total_amount_tax_invoicing * 0.75) / $data->amount_exchange, 2, ',', '.') }}
                                        </p>
                                    </th>

                                    <th scope="col" class="text-end align-middle text-dark">IVA RETENIDO: </th>
                                    <th scope="col" class="text-end align-middle text-dark">
                                        <p class='align-middle mb-0' id="totalTotals">Bs.
                                            {{ number_format($data->total_amount_tax_invoicing * 0.75, 2, ',', '.') }}</p>
                                    </th>
                                </tr>
                            @else
                                <tr>
                                    <th scope="col" class="text-end align-middle text-dark">IVA RETENIDO: </th>
                                    <th scope="col" class="text-end align-middle text-dark">
                                        <p class='align-middle mb-0' id="subFacs">$
                                            {{ number_format($data->total_amount_tax_invoicing / $data->amount_exchange, 2, ',', '.') }}
                                        </p>
                                    </th>

                                    <th scope="col" class="text-end align-middle text-dark">IVA RETENIDO: </th>
                                    <th scope="col" class="text-end align-middle text-dark">
                                        <p class='align-middle mb-0' id="totalTotals">Bs.
                                            {{ number_format($data->total_amount_tax_invoicing, 2, ',', '.') }}</p>
                                    </th>
                                </tr>
                            @endif

                        @endif
                        <tfoot class="bg-gray-100">

                            <tr>
                                <td width="43%" class="text-end">Pendiente por cobrar: </td>
                                <td width="7.5%"class="text-end"><label class="text-danger">$
                                        {{ number_format($data->residual_amount_invoicing / $data->amount_exchange, '2', ',', '.') }}</label>
                                </td>
                                <td width="19%" class="text-end">Pendiente por cobrar: </td>
                                <td width="10%" class="text-end"><label class="text-danger">Bs.
                                        {{ number_format($data->residual_amount_invoicing, '2', ',', '.') }}</label>
                                </td>
                            </tr>
                            @if ($data->id_order_state != 3)
                                <tr>
                                    <td colspan="4" class="text-end">Pagos recibidos</td>

                                </tr>

                                @foreach ($payments as $k => $pago)
                                    <tr>
                                        <td colspan="4" class="text-end fst-italic text-muted"><a
                                                href="{{ route('payments.show', $pago->id_payment) }}">#{{ $pago->ref_payment }}
                                                | {{ $pago->name_bank }} |
                                                {{ date('d-m-Y', strtotime($pago->date_payment)) }}
                                                | Bs. {{ number_format($pago->amount_payment, '2', ',', '.') }}</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            @if ($data->id_order_state != 5 && $data->id_order_state != 3)
                                <tr>
                                    <td colspan="4" class="text-end">Saldo</td>
                                </tr>
                                @foreach ($surplus as $k => $plus)
                                    <tr>
                                        <td colspan="4" class="text-end fst-italic text-muted text-end"><a
                                                href="{{ route('payments.register-pay-sur', [$plus->id_payment, $data->id_invoicing, 1]) }}">#{{ $plus->ref_payment }}
                                                |
                                                {{ date('d-m-Y', strtotime($plus->date_payment)) }} | Bs.
                                                {{ number_format($plus->amount_surplus, '2', ',', '.') }}</a></td>
                                    </tr>
                                @endforeach

                            @endif

                        </tfoot>

                    </table>




                </div>
            </div>

        </x-cards>
    </div>
    @if ($data->taxpayer_client == 1)
    <div class="modal fade" id="exampleModal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        
                        {!! Form::model($data, ['method' => 'PATCH', 'route' => ['withholding-sales.update', $data->id_withholding_iva_sale]]) !!}
                        <div class="col-12">
                            {{ $data->ref_name_invoicing }}
                        </div>
                        <div class="col-12">
                            {!! Form::text('voucher_number_whs', null, array('minlength' => '14', 'maxlength' => '14', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el número de la retencion','class' => 'form-control form-control-sm')) !!}
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" >Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @endif

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal">Cargar pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'payments.store',
                        'method' => 'POST',
                        'novalidate',
                        'placeholder' => 'Fecha del pago',
                        'class' => 'needs-validation row g-3',
                        'id' => 'myForm',
                    ]) !!}
                    <div class="col-sm-6">
                        <div class="form-floating">
                            {!! Form::date('date_payment', \Carbon\Carbon::now(), ['class' => 'form-control form-control-sm', 'required']) !!}
                            <label>Fecha del pago</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating">
                            {!! Form::text('ref_payment', null, [
                                'placeholder' => 'Número de referencia',
                                'autocomplete' => 'off',
                                'required',
                                'class' => 'form-control form-control-sm',
                            ]) !!}
                            <label>Número de referencia</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating">
                            {!! Form::text('residual_amount_invoicing', number_format($data->residual_amount_invoicing, 2, ',', '.'), [
                                'disabled',
                                'placeholder' => 'Monto residual',
                                'autocomplete' => 'off',
                                'required',
                                'class' => 'form-control form-control-sm',
                            ]) !!}
                            <label>Monto residual:</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating">
                            {!! Form::select('id_bank', $dataBanks, null, ['class' => 'form-select form-control-sm', 'required']) !!}
                            <label>Banco:</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating">
                            {!! Form::text('amount_payment', $data->residual_amount_invoicing, [
                                'placeholder' => 'Monto a pagar',
                                'autocomplete' => 'off',
                                'required',
                                'class' => 'form-control form-control-sm',
                            ]) !!}
                            <label>Monto a pagar:</label>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <x-btns-save side="false" />
                    {!! Form::hidden('id_invoice', $data->id_invoicing) !!}
                    {!! Form::hidden('id_client', $data->id_client) !!}
                    {!! Form::hidden('type_pay', 1) !!}
                    {!! Form::close() !!}

                    <div class="modal-footer mt-3">
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('js')
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal2'));

            function abreModal() {
                myModal.show()
            }



        </script>
    @endsection
