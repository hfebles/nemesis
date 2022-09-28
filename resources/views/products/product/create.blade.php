@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection


@section('content')

{!! Form::open(array('route' => 'product.store', 'method'=>'POST', 'novalidate', 'class' => 'needs-validation')) !!}
<div class="row g-4">
    <x-cards>  
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Nombre del producto</label>
                {!! Form::text('name_product', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre del producto','class' => 'form-control form-control-sm')) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar el nombre del producto
                </div>  
            </div>
            <div class="col-md-6">
                <label class="form-label">Descripción del producto</label>
                {!! Form::textarea('description_product', null, array('rows'=> 3, 'autocomplete' => 'off', 'placeholder' => 'Ingrese la descripción del producto','class' => 'form-control form-control-sm')) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar las descripción del producto
                </div>  
            </div>
            <div class="col-md-3">
                <label class="form-label">Código</label>
                {!! Form::text('code_product', null, array('onkeyup'=>'searchCode(this.value)','id' => 'code_product', 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el código del producto','class' => 'form-control form-control-sm')) !!}
                <div id="valido_code_product" class="invalid-feedback">
                    Para guardar debe ingresar el código del producto
                </div>  
            </div>

            <div class="col-md-3">
                <label class="form-label">Precio del producto</label>
                {!! Form::number('price_product', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el el precio del producto','class' => 'form-control form-control-sm', 'min'=>'0', 'step' => '0.01')) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar el precio del producto
                </div>  
            </div>
            
            <div class="clearfix"></div>
            <div class="col-3 d-flex align-items-start justify-content-start ml-2">
                <div class="form-check">
                    {!! Form::checkbox('salable_product', '1', '', ['class' => 'form-check-input'],) !!}
                    <label class="form-check-label">
                        ¿Producto Vendible?
                    </label>
                </div> 
            </div>
            <div class="col-3 d-flex align-items-start justify-content-start">
                <div class="form-check">
                    {!! Form::checkbox('tax_exempt_product', '1', '', ['class' => 'form-check-input'],) !!}
                    <label class="form-check-label">
                        ¿Producto exento de IVA?
                    </label>
                </div>  
            </div>
            <div class="col-3 d-flex align-items-start justify-content-start">
                <div class="form-check">
                    {!! Form::checkbox('product_usd_product', '1', '', ['class' => 'form-check-input'],) !!}
                    <label class="form-check-label">
                        ¿Producto en dolares?
                    </label>
                </div>  
            </div>          
            <div class="clearfix"></div>
            <div class="col-md-3">
                <label class="form-label">Almacen</label>
                {!! Form::select('id_warehouse', $getWarehouses, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar un almacen para el producto
                </div>  
            </div>
            <div class="col-md-3">
                <label class="form-label">Cateoría del producto</label>
                {!! Form::select('id_product_category', $getCategories, null, ['class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
            </div>

            <div class="col-md-3">
                <label class="form-label">Unidad de medida</label>
                {!! Form::select('id_unit_product', $getUnits, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar el nombre de la unidad de medida producto
                </div>  
            </div>

            <div class="col-md-3">
                <label class="form-label">Presentacion del producto</label>
                {!! Form::select('id_presentation_product', $getPresentations, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}
                <div  class="invalid-feedback">
                    Para guardar debe ingresar el nombre del producto
                </div>  
            </div>

            
            
            
            
                   
        </div>
        
    </x-cards>



</div>
<x-btns-save />
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


function searchCode(value){



    const csrfToken = "{{ csrf_token() }}";
    
    
    var lineas = "";

    fetch('/products/product/search-code', {
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

    </script>
@endsection