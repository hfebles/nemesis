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

function editModal(id){

    //console.log(id)
    myModal.show() 
    $('#title-modal').html('Editar grupos');
    var div = document.getElementById('divsito');
    var linea2 ="";
    const csrfToken = "{{ csrf_token() }}";
    fetch('/hhrr/edit-group', {
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
        
        linea2 += `<form method="POST" action="/hhrr/group-workers/${id}" accept-charset="UTF-8" novalidate="" class="needs-validation" id="myForm">`
            linea2 +=`<input name="_method" type="hidden" value="PATCH">`
            linea2 +=`<input name="_token" type="hidden" value="${csrfToken}">`
            linea2 +=`<div class="row g-3">`
                linea2 +=`<div class="col-12">`
                    linea2 += `<label class="form-label">Nombre del banco</label>`
                    linea2 += `<input class="form-control form-control-sm" autocomplete="off" type="text" required name="name_group_worker" id="name_group_worker" value="${data.data.name_group_worker}" />`
                linea2 +=`</div>`
                
            linea2 +=`</div>`
            linea2 +=`<div class="col-12 text-center">`
                linea2 += `<button class="btn btn-success my-3" type="submit">Actualizar</button>`
            linea2 +=`</div>`
        
        linea2 +=`</form>`


        



        div.innerHTML = linea2;
    });


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

function soloNumeros(e) {
      key = e.keyCode || e.which;
      tecla = String.fromCharCode(key).toLowerCase();
      letras = "1234567890.";
      especiales = [];
  
      tecla_especial = false
      for(var i in especiales) {
          if(key == especiales[i]) {
              tecla_especial = true;
              break;
          }
      }
  
      if(letras.indexOf(tecla) == -1 && !tecla_especial)
          return false;
  }
</script>
@endsection

@include('rrhh.group-worker.create')