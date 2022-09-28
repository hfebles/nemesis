@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection



@section('content')
{!! Form::open(array('route' => 'production-order.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-3">
    <x-cards size="12">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Nombre</label>
                {!! Form::text('name_production_order', null, array('autocomplete' => 'off','required', 'placeholder' => 'Nombre','class' => 'form-control form-control-sm')) !!}
                <div  class="invalid-feedback">
                    Ingrese la cantidad a producir
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                {!! Form::date('date_from_production_order', \Carbon\Carbon::now(), ['class' => 'form-control form-control-sm']) !!}
                <div  class="invalid-feedback">
                    Ingrese la cantidad a producir
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <label for="inputState" class="form-label">Producto</label>
                {!! Form::select('id_product', $products, null, ['onchange' => 'traerLista(this.value)', 'required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                <div  class="invalid-feedback">
                    Seleccione un producto
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                {!! Form::date('date_to_production_order', \Carbon\Carbon::now(), ['class' => 'form-control form-control-sm']) !!}
                <div  class="invalid-feedback">
                    Ingrese la cantidad a producir
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <label class="form-label">Cantidad para producir</label>
                {!! Form::text('planned_qty_production_order', null, array('id' => 'qtys1', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese la cantidad a producir','class' => 'form-control form-control-sm')) !!}
                <a onclick="calculate();">Actualizar</a>
                <div  class="invalid-feedback">
                    Ingrese la cantidad a producir
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="col-md-3">
                <label for="inputState" class="form-label">Lista de materiales</label>
                <select required id="material" name="id_material_list" class="form-select form-control-sm" ></select>
                <div  class="invalid-feedback">
                    Seleccione una lista de materiales
                </div>
            </div>

        </div>
    </x-cards>

    <x-cards>
    
          

    <div id="mytb">
    <table class="table table-sm border-dark table-bordered mb-4">`;
       <tr>
            
            <th scope="col" class="align-middle">PRODUCTO</th>
            <th scope="col" class="text-center align-middle" width="10%">CANTIDAD</th>
            <th scope="col" class="text-center align-middle" width="10%">UNIDAD</th>
        </tr>
    </table>
    </div>
    </x-cards>

    <x-cards size="12">
        <div class="text-center">
            <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
            <input class="btn btn-danger" type="reset" value="Deshacer">
        </div>
    </x-cards> 
</div>
{!! Form::close() !!}

@endsection

@section('js')
<script type="text/javascript">
 


function calculate(){
    
    var qts = document.getElementById('qtys1').value
    console.log(qts)
    var exe = document.getElementsByName('qtyss[]')
    for(let e = 0; e < exe.length; e++){
        valor = exe[e].value || 0
        
        exe[e].value = parseFloat(valor*qts)
        document.getElementById('cant_'+[e]).innerHTML = parseFloat(valor*qts)
    }
}

function traerLista(id){

    const csrfToken = "{{ csrf_token() }}";

    fetch('/production/material-list-search', {
        method: 'POST',
        body: JSON.stringify({id: id}),
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        } 
    }).then(response => {
        return response.json();
    }).then( data => {


        var a = JSON.parse(data.data[0].details);
       // console.log();

        var option = "";
        var tabla = "";
        for(let i in data.data){ 
            option +=`<option value="${data.data[i].id_materials_list}">${data.data[i].name_materials_list}</option>`;
        }

        tabla +=`<table class="table table-sm border-dark table-bordered mb-4">`;
        tabla +=`<tr>
            
            <th scope="col" class="align-middle">PRODUCTO</th>
            <th scope="col" class="text-center align-middle" width="10%">CANTIDAD</th>
            <th scope="col" class="text-center align-middle" width="10%">UNIDAD</th>
        </tr>`;
        
        //var prod = JSON.parse(data.products)

        //console.log(data)
        
        for(let o in data.products){
          //  console.log(data.products[o].name_product)
            tabla +=`<tr>`;
            tabla +=`<td>${data.products[o][0].name_product}<input type="hidden" name="id_products[]" value="${data.products[o][0].id_product}"></td>`;
            tabla +=`<td><span id="cant_${o}">${a.qtys[o]}</span><input type="hidden" name="qtyss[]" id="qtyss_${o}" value="${a.qtys[o]}"></td>`;
            tabla +=`<td>${data.presentations[o][0].name_presentation_product}</td>`;
            tabla +=`</tr>`;
        }
        tabla +=`</table>`;

        



        
        document.getElementById("mytb").innerHTML = tabla;
        document.getElementById('material').innerHTML = option;
        


        
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