@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
{!! Form::open(array('route' => 'clients.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row">
<x-cards size="12">

    <div class="row g-3">
        <div class="col-md-12">
            <label class="form-label">Nombre Apellido ó Razón Social</label>
            {!! Form::text('name_client', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre y apellido ó la razón social del cliente','class' => 'form-control form-control-sm')) !!}
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-md-4">
            <label for="inputPassword4" class="form-label">DNI / RIF</label>
            <div class="input-group mb-3">
                {!! Form::select('letra', ['J' => 'J', 'V' => 'V', 'G' => 'G'], null, ['id' => 'letra', 'required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                {!! Form::text('idcard_client', null, array( 'minlength' => '8', 'maxlength' => '9', 'onkeypress' => 'return dniRIF(event);', 'onkeyup' => 'return validoDocumento(document.getElementById("letra").value, this.value);', 'autocomplete' => 'off', 'required', 'placeholder' => 'Número de DNI ó RIF','class' => 'form-control form-control-sm')) !!}
            </div>
            
            <div id="validoCedula" class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-4">
            <label for="inputAddress" class="form-label">Teléfono</label>
            {!! Form::text('phone_client', null, array('minlength' => '11', 'maxlength' => '11', 'onkeypress' => 'return validoTelefono(event)', 'autocomplete' => 'off', 'placeholder' => 'Ingrese el número de teléfono del cliente','class' => 'form-control form-control-sm')) !!}

        </div>
        <div class="col-4">
            <label for="inputAddress2" class="form-label">Correo Electrónico</label>
            {!! Form::email('email_client', null, array('autocomplete' => 'off', 'placeholder' => 'Ingrese el correo electronico del cliente','class' => 'form-control form-control-sm')) !!}

        </div>
        <div class="col-md-12">
            <label for="inputCity" class="form-label">Dirección</label>
            {!! Form::textarea('address_client', null, array('rows'=> 3, 'autocomplete' => 'off', 'required', 'placeholder' => 'Ingrese la dirección del cliente','class' => 'form-control form-control-sm')) !!}
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-md-3">
            <label for="inputState" class="form-label">Estado de residencia</label>
            {!! Form::select('id_state', $estados, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>

        </div>
        <div class="col-md-3">
            <label for="inputZip" class="form-label">Código postal</label>
            {!! Form::text('zip_client', null, ['class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'placeholder' => 'Ingrese el código postal del cliente',]) !!}
            <div  class="invalid-feedback">
                Ingrese el Nombre y apellido ó la Razón social del cliente
            </div>
        </div>
        <div class="col-3 d-flex align-items-center mt-auto mb-2 justify-content-center">
            <div class="form-check">
                
                {!! Form::checkbox('taxpayer_client', '1', '', ['onchange' => 'validoCheck(this);','id' =>'taxpayer_client', 'class' => 'form-check-input'],) !!}
                <label class="form-check-label">
                    ¿Agente de retención?
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <label for="inputZip" class="form-label">Porcentaje de Retención</label>
            {!! Form::select('porcentual_amount_tax_client', ['100' => '100%', '75' => '75%'], null, ['id' => 'porcentual_amount_tax_client', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
        </div>
    </div>
    </div>



</x-cards>
</div>


</div>
<div class="row text-center">
    <x-cards size="12">
        <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
        <input class="btn btn-danger" type="reset" value="Deshacer">
    </x-cards> 
    </div>
    {!! Form::close() !!}
@endsection

@section('js')
<script type="text/javascript">

    
    function validoCheck(check){


        if(check.checked == true){
            document.querySelector('#porcentual_amount_tax_client').required = true
        }else{
            document.querySelector('#porcentual_amount_tax_client').required = false 
        }        
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



function validoDocumento(letra, cedula){
    var validoCedula = document.getElementById('validoCedula');
    var x = letra+cedula;


    const csrfToken = "{{ csrf_token() }}";

    
    var lineas = "";

    fetch('/sales/clients/search', {
        method: 'POST',
        body: JSON.stringify({text: x}),
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        } 
    }).then(response => {
        return response.json();
    }).then( data => {
        //console.log(data);

        if (data.res == true) {
            validoCedula.innerHTML=data.msg;
            document.querySelector('#validoCedula').style.color = "green";
            document.querySelector('#validoCedula').style.display = "block";
            document.querySelector('#btnGuardar').disabled = false;
        }else{
            validoCedula.innerHTML=data.msg;
            document.querySelector('#validoCedula').style.display = "block";
            document.querySelector('#validoCedula').theme = "danger";
            
            document.querySelector('#validoCedula').style.color = "red";
            document.querySelector('#btnGuardar').disabled = true;
        }


        
    });
}



function validoTelefono(e) {
      key = e.keyCode || e.which;
      tecla = String.fromCharCode(key).toLowerCase();
      letras = "0123456789";
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

  function dniRIF(e) {
      key = e.keyCode || e.which;
      tecla = String.fromCharCode(key).toLowerCase();
      letras = "1234567890";
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