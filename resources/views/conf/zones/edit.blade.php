@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" :delete="$conf['delete']" />
@endsection



@section('content')
    {!! Form::model($data, ['method' => 'PATCH', 'route' => ['zones.update', $data->id_zone]]) !!}
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
                    <label for="inputState" class="form-label">Estados Actuales:</label>
                    <div class="row">
                        @foreach ($dataEstados as $value)
                            <div class="col-3">
                                <div class="form-check form-check-inline">
                                    {{ Form::checkbox('ids_estados[]', $value->id_estado, true, ['class' => 'form-check-input']) }}
                                    <label class="form-check-label">{{ $value->estado }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-12">
                    <label for="inputState" class="form-label">Estados:</label>
                    <div class="row">
                        @foreach ($dataEstado as $values)
                            <div class="col-3">
                                <div class="form-check form-check-inline">
                                    {{ Form::checkbox('ids_estados[]', $values->id_estado, false, ['class' => 'form-check-input']) }}
                                    <label class="form-check-label">{{ $values->estado }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
        </x-cards>



        <x-btns-save />
    </div>
    {!! Form::close() !!}


    
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea eliminar?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p>A seleccionado eliminar al usuario: {{$data->name_zone}}</p>
            <p>Una vez eliminado no podra ser recuperado de nuevo</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            {!! Form::open(['method' => 'DELETE','route' => ['zones.destroy', $data->id_zone],'style'=>'display:inline']) !!}
                <button class="btn btn-danger btn-icon-split" type="submit">
                    <span class="icon text-white-50">
                        <i class="fas fa-trash"></i>
                    </span>
                    <span class="text">Eliminar zona</span>
                </button>
            {!! Form::close() !!}
        </div>
    </div>
</div>
</div>

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
