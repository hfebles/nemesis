@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection



@section('content')
{!! Form::open(array('route' => 'delivery.store','method'=>'POST', 'novalidate', 'class' => 'needs-validation', 'id' => 'myForm')) !!}
<div class="row g-3">
    <x-cards size="12">
        <div class="row g-3">
        
            <div class="col-md-6">
                <label class="form-label">Número de guía</label>
                {!! Form::text('guide_delivery', null, ['autocomplete' => 'off', 'required', 'placeholder' => 'Ingrese el Número de guía', 'class' => 'form-control form-control-sm']) !!}
                <div  class="invalid-feedback">
                    Ingrese el número de guía
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Fecha</label>
                {!! Form::date('date_delivery', \Carbon\Carbon::now(), ['class' => 'form-control form-control-sm', 'required',]) !!}
                <div  class="invalid-feedback">
                    Ingrese la fecha
                </div>
            </div>

            
            

            <div class="col-6">
                <label for="id_estado" class="form-label">Zona de despacho</label>
                {!! Form::select('id_zone', $zone, null, ['required', 'placeholder' => 'Seleccione una zona', 'class' =>'form-select form-control-sm' ]) !!}
            </div>

            <div class="col-6">
                <label for="id_estado" class="form-label">Chofer</label>
                <select required class="form-select form-control-sm" name="id_worker">
                    @foreach ($driver as  $d)
                        <option value="{{$d->id_worker}}">{{$d->firts_name_worker}} {{$d->last_name_worker}}</option>
                    @endforeach
                </select>
                
            </div>

            <div class="col-6">
                <label for="id_estado" class="form-label">Caletero</label>
                <select required class="form-select form-control-sm" name="id_caletero">
                    @foreach ($caletero as  $c)
                        <option value="{{$c->id_worker}}">{{$c->firts_name_worker}} {{$c->last_name_worker}}</option>
                    @endforeach
                </select>
                
            </div>



            <div class="col-md-12">
                <label for="inputState" class="form-label">Facturas:</label>
                <div class="row">
                    @foreach($invoices as $v => $value)
                        <div class="col-6">
                            <div class="form-check form-check-inline">
                                {{ Form::checkbox('ids_invoices[]', $value->id_invoicing, false, ['class' => 'form-check-input']) }}
                                <label class="form-check-label">{{ $value->ref_name_invoicing }} - {{ $value->name_client }}</label>
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