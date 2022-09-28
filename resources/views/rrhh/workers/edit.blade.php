@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection


@section('content')

{!! Form::model($data, ['novalidate', 'class' => 'needs-validation', 'method' => 'PATCH', 'route' => ['workers.update', $data->id_worker]]) !!}
<div class="row g-4">
    <x-cards>  
        <div class="row g-4">
        <div class="col-md-6">
                <label class="form-label">Apellidos:</label>
                {!! Form::text('last_name_worker', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese los apellidos del trabajador','class' => 'form-control form-control-sm')) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar los apellidos del trabajador
                </div>  
            </div>
            <div class="col-md-6">
                <label class="form-label">Nombres:</label>
                {!! Form::text('firts_name_worker', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese los nombres del trabajador','class' => 'form-control form-control-sm')) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar los nombres del trabajador
                </div>  
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Cédula</label>
                {!! Form::text('dni_worker', null, array('minlength' => '7', 'maxlength' => '8','onkeypress' => 'return dniRIF(event);', 'onkeyup'=>'searchCedula(this.value)','id' => 'dni_worker', 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese la cedula del trabajador','class' => 'form-control form-control-sm')) !!}
                <div id="valido_code_product" class="invalid-feedback">
                    Para guardar debe ingresar la cedula del trabajador
                </div>  
            </div>

            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                {!! Form::number('phone_worker', null, array( 'autocomplete' => 'off', 'placeholder' => 'Ingrese el numero de telefono del trabajador','class' => 'form-control form-control-sm', 'min'=>'0', 'step' => '0.01')) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar el numero de teléfono del trabajador
                </div>  
            </div>
            <div class="col-md-4">
                <label class="form-label">Correo electrónico</label>
                {!! Form::email('mail_worker', null, array( 'autocomplete' => 'off', 'placeholder' => 'Ingrese el correo electrónico del trabajador', 'class' => 'form-control form-control-sm', 'min'=>'0', 'step' => '0.01')) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar el correo electrónico del trabajador
                </div>  
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <label class="form-label">Grupo del trabajador</label>
                {!! Form::select('id_group_worker', $group, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                <div  class="invalid-feedback">
                    Para guardar debe asociar un grupo de trabajo
                </div>  
            </div>

            <div class="col-md-3">
                <label class="form-label">Usuario trabajador</label>
                {!! Form::select('id_user', $users, null, ['class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar un almacen para el producto
                </div>  
            </div>

            
            
            
            
                   
        </div>
        
    </x-cards>


<x-btns-save />
</div>
{!! Form::close() !!}

@endsection


@section('js')
    <script>

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


function searchCedula(value){



    const csrfToken = "{{ csrf_token() }}";
    
    
    var lineas = "";

    fetch('/hhrr/workers/search-dni', {
        method: 'POST',
        body: JSON.stringify({text: value}),
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        } 
    }).then(response => {
        return response.json();
    }).then( data => {
        //console.log(data);

        if (data.res == true) {
            document.querySelector('#valido_code_product').innerHTML=data.msg;
            document.querySelector('#valido_code_product').style.color = "green";
            document.querySelector('#valido_code_product').style.display = "block";
            document.querySelector('#btnGuardar').disabled = false;
        }else{
            document.querySelector('#valido_code_product').innerHTML=data.msg;
            document.querySelector('#valido_code_product').style.display = "block";
            document.querySelector('#valido_code_product').style.color = "red";
            document.querySelector('#btnGuardar').disabled = true;
        }


        
    });
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