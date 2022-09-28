@section('side-title', 'Crear un nuevo grupo')

@section('side-body')
{!! Form::open(array('route' => 'group-workers.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Nombre del Grupo</label>
        {!! Form::text('name_group_worker', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el nombre del grupo','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el nombre del grupo
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