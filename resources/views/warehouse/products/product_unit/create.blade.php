@section('side-title', 'Crear una nueva unidad')

@section('side-body')
{!! Form::open(array('route' => 'unit.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Nombre de la unidad</label>
        {!! Form::text('name_unit_product', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre de la unidad','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre de la unidad
        </div>

    </div>
    <div class="col-md-12">
        <label class="form-label">Nombre de la unidad</label>
        {!! Form::text('short_unit_product', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre de la unidad','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre de la unidad
        </div>

</div>

<x-btns-save side="true"/>
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