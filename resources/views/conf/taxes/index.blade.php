@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        <x-cards size="12" :table="$table" />
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal"></h5>
                    <button type="button" class="btn-close" onclick="cierromodal();" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="divsito"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('js')
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));

            function cierromodal() {
                myModal.hide()
            }

            function editModal(id) {

                //console.log(id)
                myModal.show()
                $('#title-modal').html('Editar impuestos');
                var div = document.getElementById('divsito');
                var linea2 = "";
                const csrfToken = "{{ csrf_token() }}";
                fetch('/mantenice/edit-taxes', {
                    method: 'POST',
                    body: JSON.stringify({
                        id: id,
                    }),
                    headers: {
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {

                    linea2 +=
                        `<form method="POST" action="/mantenice/taxes/${id}" accept-charset="UTF-8" novalidate="" class="needs-validation" id="myForm">`
                    linea2 += `<input name="_method" type="hidden" value="PATCH">`
                    linea2 += `<input name="_token" type="hidden" value="${csrfToken}">`
                    linea2 += `<div class="row g-3">`
                    linea2 += `<div class="col-12">`
                    linea2 += `<label class="form-label">Nombre del impuesto</label>`
                    linea2 +=
                        `<input class="form-control form-control-sm" type="text" required name="name_tax" id="name_tax" value="${data.data.name_tax}" />`
                    linea2 += `</div>`
                    linea2 += `<div class="col-12">`
                    linea2 += `<label class="form-label">Porcentaje del impuesto</label>`
                    linea2 +=
                        `<input class="form-control form-control-sm" type="text" required onkeypress="return soloNumeros(event);" minlength='1' 'maxlength="4" name="amount_tax" id="amount_tax" value="${data.data.amount_tax}" />`
                    linea2 += `<div  class="invalid-feedback">Para guardar debe ingresar el procentaje</div>`
                    linea2 += `</div>`
                    linea2 += `<div class="col-12">`
                    linea2 += `<label class="form-label">Cuenta contable asociada</label>`
                    linea2 +=
                        `<select name="id_sub_ledger_account" id="id_sub_ledger_account" required class="form-select form-select-sm">`
                    linea2 += `<option >Seleccione</option>`
                    for (let i in data.accs) {
                        linea2 +=
                            `<option value="${data.accs[i].id_sub_ledger_account}">${data.accs[i].name_sub_ledger_account}</option>`
                    }
                    linea2 += `</select>`
                    linea2 += `<div  class="invalid-feedback">Para guardar debe ingresar el procentaje</div>`
                    linea2 += `</div>`
                    linea2 += `<div class="col-12">`
                    linea2 += `<div class="form-check">`
                    linea2 +=
                        `<input class="form-check-input" name="billable_tax" type="checkbox"  ${data.data.billable_tax == 1 ? 'checked' : ''}   id="flexCheckDefault">`
                    linea2 += `<label class="form-check-label" for="flexCheckDefault">¿Impuesto Facturable?</label>`
                    linea2 += `</div>`
                    linea2 += `</div>`
                    linea2 += `</div>`
                    linea2 += `<div class="col-12 text-center">`
                    linea2 += `<button class="btn btn-success my-3" type="submit">Actualizar</button>`
                    linea2 += `</div>`
                    linea2 += `</form>`
                    div.innerHTML = linea2;
                });
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

            function soloNumeros(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = "1234567890.";
                especiales = [];

                tecla_especial = false
                for (var i in especiales) {
                    if (key == especiales[i]) {
                        tecla_especial = true;
                        break;
                    }
                }

                if (letras.indexOf(tecla) == -1 && !tecla_especial)
                    return false;
            }
        </script>
    @endsection
    @include('conf.taxes.create')
