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
function selectSubAccount(type, location){
    
        

        const csrfToken = "{{ csrf_token() }}";


    if(location == 'c'){
        var div = document.getElementById('subcuenta');
        div.style.display = "";

        var select = document.getElementById('subcuentas');
        var opts ="";
    }else{
            var select = document.getElementById('subcuentas_m');
        }

    
    

    fetch('/accounting/ledger-account/search-ledgers', {
        method: 'POST',
        body: JSON.stringify({type: type,}),
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        } 
    }).then(response => {
        return response.json();
    }).then( data => {
        select.innerHTML = ""

        for (i in data){
           //console.log(data[i] )
            opts += `<option value="${data[i].id_ledger_account}">${data[i].code_ledger_account} - ${data[i].name_ledger_account}</option>`
        }

        
        select.innerHTML = opts;

    });

    
    
    }






var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));

function cierromodal(){
    myModal.hide()
}

function editModal(id){

    //console.log(id)
    myModal.show() 
    $('#title-modal').html('Editar banco');
    var div = document.getElementById('divsito');
    var linea2 ="";
    const csrfToken = "{{ csrf_token() }}";
    fetch('/mantenice/edit-banks', {
        method: 'POST',
        body: JSON.stringify({id: id,}),
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        } 
    }).then(response => {
        return response.json();
    }).then( data => {
        linea2 += `<form method="POST" action="/mantenice/banks/${id}" accept-charset="UTF-8" novalidate="" class="needs-validation" id="myForm">`
            linea2 +=`<input name="_method" type="hidden" value="PATCH">`
            linea2 +=`<input name="_token" type="hidden" value="${csrfToken}">`
            linea2 +=`<div class="row g-3">`
                linea2 +=`<div class="col-12">`
                    linea2 += `<label class="form-label">Nombre del banco</label>`
                    linea2 += `<input class="form-control form-control-sm" autocomplete="off" type="text" required name="name_bank" id="name_bank" value="${data.data.name_bank}" />`
                linea2 +=`</div>`
                linea2 +=`<div class="col-12">`
                    linea2 += `<label class="form-label">Numero de cuenta</label>`
                    linea2 += `<input class="form-control autocomplete="off" form-control-sm" type="text" required onkeypress="return soloNumeros(event);" minlength="20" maxlength="20" name="account_number_bank" id="account_number_bank" value="${data.data.account_number_bank}" />`
                    linea2+= `<div  class="invalid-feedback">Para guardar debe ingresar el procentaje</div>`
                linea2 +=`</div>`
                linea2 +=`<div class="col-12">`
                    linea2 += `<label class="form-label">Descripción del banco</label>`
                    linea2 += `<textarea class="form-control form-control-sm" type="text" name="description_bank" id="description_bank">${data.data.description_bank}</textarea>`
                    linea2+= `<div  class="invalid-feedback">Para guardar debe ingresar el procentaje</div>`
                linea2 +=`</div>`
                linea2 +=`<div class="col-12">`
                    linea2 += `<label class="form-label">Tipo de cuenta contable asociada</label>`
                    linea2 +=`<select onchange="selectSubAccount(this.value, 'm')" required class="form-select form-select-sm">`
                        linea2 +=`<option value="${data.dataGroupLedgerBank.id_ledger_account}">${data.dataGroupLedgerBank.code_ledger_account} - ${data.dataGroupLedgerBank.name_ledger_account}</option>`
                        for(let i in data.groupLedgers){
                            linea2 +=`<option value="${i}">${i} - ${data.groupLedgers[i]}</option>`
                        }
                    linea2 +=`</select>`
                    linea2+= `<div  class="invalid-feedback">Para guardar debe ingresar el procentaje</div>`
                linea2 +=`</div>`
                linea2 +=`<div class="col-12">`
                    linea2 += `<label class="form-label">Tipo de cuenta contable asociada</label>`
                    linea2 +=`<select name="id_ledger_account" id="subcuentas_m" required class="subcuentas form-select form-select-sm">`
                        linea2 +=`<option value="${data.dataTypeLedgerBank.id_ledger_account}">${data.dataTypeLedgerBank.code_ledger_account} - ${data.dataTypeLedgerBank.name_ledger_account}</option>`
                        for(let i in data.typesLedgers){
                            linea2 +=`<option value="${data.typesLedgers[i].id_ledger_account}">${data.typesLedgers[i].code_ledger_account} - ${data.typesLedgers[i].name_ledger_account}</option>`
                        }
                    linea2 +=`</select>`
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
@include('conf.banks.create')



