@section('side-title', 'Crear una nueva unidad')

@section('side-body')
{!! Form::open(array('route' => 'presentation.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Nombre de la presentación</label>
        {!! Form::text('name_presentation_product', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre de la presentación','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre de la presentaión
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