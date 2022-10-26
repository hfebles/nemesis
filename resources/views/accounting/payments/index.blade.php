@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        @if ($message = Session::get('message'))
            <x-cards :message="$message" />
        @endif
        <x-cards>
            <div class="mb-3 d-flex">
                <div class="dropdown  ml-auto">
                    <button class="btn btn-sm btn-success dropdown-toggle " type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Acciones
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" onclick="abreModal('cliente')">Pagos por cliente</a></li>
                        <li><a class="dropdown-item" onclick="abreModal('fechas')">Pagos por fechas</a></li>
                        <li><a class="dropdown-item" href="{{ route('payments.general-print') }}">Todos los pagos</a></li>
                    </ul>
                </div>
            </div>



            <table class="table table-sm table-bordered mb-0 table-hover">
                <tr class="bg-dark text-white">
                    <th class="text-center align-middle">#</th>
                    <th class="text-center align-middle">Fecha</th>
                    <th class="text-center align-middle">Cliente</th>
                    <th class="text-center align-middle">Factura/Pedido</th>
                    <th class="text-center align-middle">Banco</th>
                    <th class="text-center align-middle">Referencia</th>
                    <th class="text-center align-middle">Monto</th>
                </tr>
                @foreach ($table['data'] as $tabla)
                    <tr style="cursor:pointer;" onclick="window.location='{{ $table['url'] }}/{{ $tabla->id_payment }}';">
                        <td class="text-center align-middle">{{ ++$table['i'] }}</td>
                        <td class="text-center align-middle">{{ date('d-m-Y', strtotime($tabla->date_payment)) }}</td>
                        <td class="text-center align-middle">{{ $tabla->name_client }}</td>
                        <td class="text-center align-middle">{{ $tabla->ref_name_invoicing ?? $tabla->ref_name_delivery_note }}</td>
                        <td class="text-center align-middle">{{ $tabla->name_bank }}</td>
                        <td class="text-end align-middle">#{{  $tabla->ref_payment }}</td>
                        <td class="text-center align-middle">{{ number_format($tabla->amount_payment, '2', ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
             {!! $table['data']->render() !!}      
        </x-cards>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div id="buscar"></div>
                        </div>
                        <div class="col-12">
                            <div id="divsito"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    @section('js')

        <script>
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));

            function abreModal(tipo) {
                var col = document.getElementById('divsito');
                linea2 = ""
                const csrfToken = "{{ csrf_token() }}";

                if (tipo == 'cliente') {
                    document.getElementById('title-modal').innerHTML = "Pagos por cliente"

                    col.innerHTML = "";
                    myModal.show()
                    creaBusqueda('clientes', '');

                    
                    fetch('/sales/search', {
                        method: 'POST',
                        body: JSON.stringify({
                            texto: 'clientes',
                            param: ""
                        }),
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }).then(response => {
                        return response.json();
                    }).then(data => {
                        document.getElementById('title-modal').innerHTML = data.title;
                        var a = "";
                        var b = "";
                        var c = "";
                        linea2 += '<div class="col-12">'
                        linea2 += '<table class="table table-sm table-bordered table-hover">'
                        linea2 += '<thead class="bg-dark text-white">'
                        linea2 += '<tr>'
                        for (let ths in data.th) {
                            linea2 += '<th col="scope" class="text-center">'
                            linea2 += data.th[ths]
                            linea2 += '</th>'
                        }
                        linea2 += '</thead>'
                        linea2 += '</tr>'
                        for (let t in data.lista) {
                            c = data.lista[t]
                            var a = JSON.stringify(c);
                            linea2 += '<tr onclick=\'seleccionarCliente(' + c.id_client + ')\'>'
                            linea2 += '<td class="text-center">' + c.name_client + '</td>'
                            linea2 += '<td class="text-center">' + c.idcard_client + '</td>'
                            linea2 += '</tr>'

                        }


                        linea2 += '</table>'
                        col.innerHTML = linea2
                    });

                } else {
                    col.innerHTML = "";
                    document.getElementById('title-modal').innerHTML = "Pagos por rango de fechas"
                    myModal.show()



                    linea2 += `<div class="container px-5 my-5">`;
                    linea2 += `<form method="POST" action="{{ route('payments.payment-print-date') }}" accept-charset="UTF-8" novalidate class="needs-validation" id="myForm">`;
                    linea2 += `<input name="_token" type="hidden" value="${csrfToken}">`
                    linea2 += `<div class="form-floating mb-3">`;
                    linea2 += `<input class="form-control form-control-sm" autocomplete='off' name="fechaDesde" type="date" placeholder="Fecha desde" required />`;
                    linea2 += `<label for="fechaDesde">Fecha desde</label>`;
                    linea2 += `<div class="invalid-feedback">Fecha desde is requerido.</div>`;
                    linea2 += `</div>`;
                    linea2 += `<div class="form-floating mb-3">`;
                    linea2 += `<input class="form-control form-control-sm"  autocomplete='off' name="fechaHasta" type="date" placeholder="Fecha hasta" required />`;
                    linea2 += `<label for="fechaHasta">Fecha hasta</label>`;
                    linea2 += `<div class="invalid-feedback">Fecha hasta is required.</div>`;
                    linea2 += `</div>`;
                    linea2 += `<div class="d-grid">`;
                    linea2 += `<button class="btn btn-primary btn-lg " id="submitButton" type="submit">Submit</button>`;
                    linea2 += `</div>`;
                    linea2 += `</form>`;
                    linea2 += `</div>`;

                    col.innerHTML = linea2

                }

            }

            function seleccionarCliente(x) {
                window.location.href = '/accounting/general-prints/'+x ;
                myModal.hide()
            }


            function creaBusqueda(tipo, valorActual = "") {
                document.getElementById('buscar').innerHTML =
                    '<input class="form-control" placeholder="Buscar" type="text" autocomplete="off" id="searchModal" onkeyup="seleccionar(\'' + tipo + '\', this.value);" />'
                const input = document.getElementById('searchModal')
                if (valorActual != "") {
                    input.value = valorActual
                } else if (isNaN(valorActual)) {
                    input.value = ""
                } else {
                    input.value = ""
                }
                const end = input.value.length;
                input.setSelectionRange(end, end);
                input.focus();


            }




            (function() {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        </script>


    @endsection
