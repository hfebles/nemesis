@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')

<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
<div class="row">
        <x-cards size="12" :table="$table" />
</div>
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