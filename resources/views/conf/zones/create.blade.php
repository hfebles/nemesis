@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection



@section('content')
    {!! Form::open([
        'route' => 'zones.store',
        'method' => 'POST',
        'novalidate',
        'class' => 'needs-validation',
        'id' => 'myForm',
    ]) !!}
    <div class="row g-3">
        <x-cards size="12">
            <div class="row g-3">

                <div class="col-md-12">
                    <label class="form-label">Nombre de la zona</label>
                    {!! Form::text('name_zone', null, [
                        'autocomplete' => 'off',
                        'required',
                        'placeholder' => 'Ingrese el nombre de la zona',
                        'class' => 'form-control form-control-sm',
                    ]) !!}
                    <div class="invalid-feedback">
                        Ingrese el nombre de la zona
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-12">
                    <label for="inputState" class="form-label">Estados</label>
                    <div class="row">
                        @foreach ($dataEstado as $v => $value)
                            <div class="col-3">
                                <div class="form-check form-check-inline">
                                    {{ Form::checkbox('ids_estados[]', $value->id_estado, false, ['class' => 'form-check-input']) }}
                                    <label class="form-check-label">{{ $value->estado }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
        </x-cards>



        <x-btns-save />
    </div>
    {!! Form::close() !!}

@endsection

@section('js')
    <script type="text/javascript">
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
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
