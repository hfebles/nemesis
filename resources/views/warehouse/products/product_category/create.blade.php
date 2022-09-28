@section('side-title', 'Crear una nueva categoría')

@section('side-body')
{!! Form::open(array('route' => 'category.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Nombre de la Categoría</label>
        {!! Form::text('name_product_category', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre del almacen','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre de la categoría
        </div>

    </div>

</div>

<x-btns-save side="true" />
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