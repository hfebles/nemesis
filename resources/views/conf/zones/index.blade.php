@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        @if ($message = Session::get('message'))
            <x-cards size="12" :table="$table" :message="$message" />
        @else
            <x-cards size="12" :table="$table" />
        @endif
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




