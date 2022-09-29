@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        @if ($message = Session::get('message'))
            <x-cards size="12" :table="$table" :message="$message" />
        @else
            <x-cards size="12" :table="$table" />
        @endif
    </div>

    
    <!-- Modal -->
<div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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

function cierromodal(){
    myModal.hide()
}

(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()



function editModal(id){

//console.log(id)
myModal.show() 
$('#title-modal').html('Editar Flete');
var div = document.getElementById('divsito');
var linea2 ="";
const csrfToken = "{{ csrf_token() }}";
fetch('/mantenice/edit-cargo', {
    method: 'POST',
    body: JSON.stringify({id: id,}),
    headers: {
        'content-type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    } 
}).then(response => {
    return response.json();
}).then( data => {
    console.log(data)
    var keys = Object.keys(data.zone)
    
    linea2 += `<form method="POST" action="/mantenice/cargo/${id}" accept-charset="UTF-8" novalidate="" class="needs-validation" id="myForm">`
        linea2 +=`<input name="_method" type="hidden" value="PATCH">`
        linea2 +=`<input name="_token" type="hidden" value="${csrfToken}">`
        linea2 +=`<div class="row g-3">`
            linea2 +=`<div class="col-12">`
                linea2 += `<label class="form-label">Precio</label>`
                linea2 += `<input class="form-control form-control-sm" autocomplete="off" type="number" required name="price_cargo" id="price_cargo" value="${data.data.price_cargo}" />`
            linea2 +=`</div>`
            linea2 +=`<div class="col-12">`
                linea2 += `<label class="form-label">Zona de cobertura</label>`
                linea2 +=`<select name="id_zone" class="form-select form-control-sm">`
                    for(z in data.zone){
                        linea2 +=`<option value="${z}">${data.zone[z]}</option>`
                    }
                linea2 +=`</select>`
            linea2 +=`</div>`
            linea2+= `<div  class="invalid-feedback">Para guardar debe ingresar el procentaje</div>`
            linea2 +=`</div>`
        linea2 +=`</div>`
        linea2 +=`<div class="col-12 text-center">`
            linea2 += `<button class="btn btn-success my-3" type="submit">Actualizar</button>`
        linea2 +=`</div>`
    linea2 +=`</form>`
    div.innerHTML = linea2;
});


}

</script>
@endsection
@include('conf.cargo.create')



