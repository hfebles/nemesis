



@extends('layouts.app')

@section('title-section', 'Ver elementos de menú')

@section('btn')
    <a href="{{ route('menu.index') }}" class="btn btn-dark btn-icon-split">
        <span class="icon text-white-50">
        <i class="fas fa-chevron-circle-left"></i>
        </span>
        <span class="text">Atras</span>
    </a>
@endsection


@section('content')




    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Elemento principal</h5>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td width="30%"  class="bg-dark text-white p-1">Item: </td>
                            <td class="p-1">{{$dataPapa->name}}</td>
                        </tr>

                        <tr>
                            <td width="30%" class="bg-dark text-white p-1">URL: </td>
                            <td class="p-1">{{$dataPapa->slug}}</td>
                        </tr>

                        <tr>
                            <td width="30%" class="bg-dark text-white p-1">Padre: </td>
                            <td class="p-1">
                                @if ($dataPapa->parent == 0)
                                    Principal
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td width="30%" class="bg-dark text-white p-1">Posicion: </td>
                            <td class="p-1">{{$dataPapa->order}}</td>
                        </tr>

                        <tr>
                            <td width="30%" class="bg-dark text-white p-1">Activo:</td>
                            <td class="p-1">
                                @switch($dataPapa->enabled)
                                    @case(1)
                                        <div class="form-check form-switch p-0">
                                            <input class="form-check-input ml-0" type="checkbox" checked disabled>
                                        </div>
                                        @break
                                    @default
                                        <div class="form-check form-switch p-0">
                                            <input class="form-check-input ml-0" type="checkbox" disabled>
                                        </div>
                                @endswitch
                            </td>
                        </tr>
                        
                        
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-9 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Subelementos</h5>
                        @if(Gate::check('adm-create') || Gate::check('menu-create'))
                            <div ><a class="btn btn-success" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i class="fa fa-plus fa-lg"></i></a></div>
                        @endif
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th col="scope" class="p-1 py-2" width="40%">Subitem</th>
                                <th col="scope" class="p-1 py-2">URL</th>
                                <th col="scope" class="text-center p-1 py-2" width="4%">Posición</th>
                                <th col="scope" class="text-center p-1 py-2" width="4%">Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($dataHijos as $hijos)
                            <tr>
                                <td class="p-1">{{$hijos->name}}</td>
                                <td class="p-1">{{$hijos->slug}}</td>
                                <td class="text-center p-1">{{$hijos->order}}</td>
                                <td class="text-center p-1">
                                    @switch($hijos->enabled)
                                        @case(1)
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input mr-0 ml-1" type="checkbox" checked disabled>
                                            </div>
                                            @break
                                        @default
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input ml-0" type="checkbox" disabled>
                                            </div>
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                    </div>
                </div> 
            </div>
        </div> 
    </div>


</div>

@endsection





@section('side-title', 'Crear nuevo sub elemento del menú')

@section('side-body')
{!! Form::open(array('route' => 'menu.store','method'=>'POST')) !!}
<div class="row">
<div class="mb-3 col-12">
                                <label for="name" class="form-label">Nombre del elemento:</label>
                                {!! Form::text('name', null, array('id' => 'name', 'placeholder' => 'Nombre del elemento','class' => 'form-control')) !!}
                            </div>

                            <div class="mb-3 col-12">
                                <label for="slug" class="form-label">URL / RUTA:</label>
                                {!! Form::text('slug', null, array('id' => 'slug', 'placeholder' => 'URL / RUTA','class' => 'form-control')) !!}
                            </div>

                            <div class="mb-3 col-12">
                                <label for="order" class="form-label">Posición</label>
                                {!! Form::number('order', null, array('id' => 'order', 'placeholder' => 'Posición','class' => 'form-control')) !!}
                            </div>
                            <div class="mb-3 col-12">
                                <div class="form-check form-switch p-0">
                                    <input class="form-check-input ml-0" type="checkbox" name="href" id="href" value="1">
                                    <label for="href" class="form-label ml-5">¿URL PLANO?</label>
                                </div>
                            </div>
                            {!! Form::hidden('parent', $dataPapa->id ) !!}
</div>
    
<div class="offcanvas-footer">
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-success">Guardar</button>
        <input class="btn btn-danger" type="reset" value="Limpiar">
    </div>
</div>
{!! Form::close() !!}

@endsection