@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection


@section('content')

{!! Form::open(array('route' => 'users.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation')) !!}
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">Nombre del usuario:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::text('name', null, array('autocomplete' => 'off', 'required', 'placeholder' => 'Nombre de usuario','class' => 'form-control')) !!}
                        <div  class="invalid-feedback">
                            Debe ingresar un nombre de usuario
                        </div>
                    </div>
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">Correo electrónico:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::email('email', null, array('autocomplete' => 'off', 'required', 'placeholder' => 'Correo Electrónico','class' => 'form-control')) !!}
                        <div  class="invalid-feedback">
                            Debe ingresar un correo electronico valido xxx@qqq.com
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">Contraseña:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::password('password', array('autocomplete' => 'off', 'required', 'placeholder' => 'Password','id' => 'p1','class' => 'form-control')) !!}
                        <div  class="invalid-feedback">
                            Debe ingresar una contraseña de minimo 8 caracteres y maximo 12
                        </div>
                    </div>
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">Confirme su contraseña:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::password('confirm-password', array('onkeyup' =>'validatePassword()', 'id'=>'pswd', 'required', 'placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                        <div id="comfirm" class="invalid-feedback">
                            <span id="text">la constraseña no coinciden</span>
                        </div>
                        
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-2 align-self-center pr-0">
                        <label class="form-label mb-0">Grupo:</label>
                    </div>
                    <div class="col-4 pl-0">
                        {!! Form::select('roles[]', $roles,[], array('required', 'placeholder' => 'Seleccione un grupo', 'class' => 'form-select')) !!}
                        <div  class="invalid-feedback">
                            Debe seleccionar un grupo
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>  


        <x-btns-save />



{!! Form::close() !!}

@endsection

@section('js')

<script>
    function validatePassword(){

        var pw = document.getElementById("pswd").value;

        var p1 = document.getElementById("p1").value;
            

        
        
        
        if(pw.length <= 7) {
              
            document.getElementById("comfirm").style.display = "block"
            document.getElementById("text").innerHTML = "La contraseña debe contener minimo 8 caracteres"+pw.length+" - "+pw
            return false;

        }else if( pw.length >= 12) {  
                document.getElementById("comfirm").style.display = "block"
                document.getElementById("text").innerHTML = "La contraseña debe contener maximo 12 caracteres"
                return false;  
        } else {  
            if( p1 != pw) {
                document.getElementById("comfirm").style.display = "block"
                document.getElementById("text").innerHTML = "La contraseña no coinciden"+p1+" - "+pw
                return false;  
                
            }else{
                document.getElementById("comfirm").style.display = "none" 
            }
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
</script>
@endsection