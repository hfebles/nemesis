@section('side-title', 'Crear un nuevo grupo')

@section('side-body')
{!! Form::open(array('route' => 'exchange.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-4">
    <div class="col-md-12">
        <label class="form-label">Fecha:</label>
        <p>{{ date('d-m-Y') }}</p>
        {{ Form::hidden('date_exchange', date('Y-m-d')) }}

    </div>
    <div class="col-md-12">
        <label class="form-label">Monto de la tasa</label>
        {!! Form::text('amount_exchange', null, array( 'autocomplete' => 'off','required', 'placeholder' => 'Ingrese el monto del día','class' => 'form-control form-control-sm')) !!}
        <div  class="invalid-feedback">
            Para guardar debe ingresar el monto del día
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