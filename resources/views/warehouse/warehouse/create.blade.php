@section('side-title', 'Crear un nuevo almacen')

@section('side-body')
{!! Form::open(array('route' => 'warehouse.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Nombre del almacen</label>
        {!! Form::text('name_warehouse', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre del almacen','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre del almacen
        </div>

    </div>

    <div class="col-md-12">
        <label class="form-label">Código de referencia</label>
        {!! Form::text('code_warehouse', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese código o referencia','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
        Para guardar debe ingresar el codigo de referencia del almacen
        </div>

    </div>

</div>

<div class="row g-3 text-center mt-5">
    <div>
        <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
        <input class="btn btn-danger" type="reset" value="Deshacer">
        </div>
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
</script>
@endsection