@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :edit="$conf['edit']" :group="$conf['group']" :delete="$conf['delete']"/>
@endsection

@section('content')




    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Almacen</h5>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td width="30%"  class="bg-dark text-white p-1">Nombre: </td>
                            <td class="p-1">{{$getDataWarehouse->name_warehouse}}</td>
                        </tr>

                        <tr>
                            <td width="30%" class="bg-dark text-white p-1">Código: </td>
                            <td class="p-1">{{$getDataWarehouse->code_warehouse}}</td>
                        </tr>

                        <tr>
                            <td width="30%" class="bg-dark text-white p-1">Activo:</td>
                            <td class="p-1">
                                @switch($getDataWarehouse->enabled_warehouse)
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
        <div class="col-9">

        <x-cards>
        <div class="d-flex flex-row  mb-3">
                    <h5 class="card-title">Productos</h5>
                    @if(Gate::check('adm-create') || Gate::check('product-product-create'))
                        <div class="ms-auto">
                            
                            <a class="btn btn-sm btn-success btn-icon-split" href="{{$conf['create']}}" >
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Producto</span>
                            </a>
                        </div>
                    @endif
                </div>
            
        @if (Gate::check($table['group'].'-list') || Gate::check('adm-list'))
        @if (isset($table['caption']))
            <p class="text-muted fs-4 p-2 mb-2">{{$table['caption']}}</p>
        @endif
        
        <table class="{{$table['c_table']}} table-sm mb-0">        
            <thead class="{{$table['c_thead']}}">
                <tr>
                    @foreach ($table['ths'] as $k => $th)
                    <th class="text-uppercase font-weight-bolder {{$table['c_ths'][$k]}}"  width="{{$table['w_ts'][$k]}}%">{{$th}}</th>
                    @endforeach
                    @if ($table['edit_modal'] == true)
                        <th width="3%" class="{{$table['c_ths'][$k]}}" ></th>
                    @endif
                </tr>
            </thead>

            <tbody id="body-table">
            @for ($o = 0; $o < count($table['data']); $o++)
            @if ($table['show'] == true)
                <tr onclick="window.location='{{$table['url']}}/{{$table['data'][$o][$table['id']]}}';">
            
            @elseif ($table['edit'] == true)
                @if (Gate::check($table['group'].'-edit') || Gate::check('adm-edit'))
                    <tr onclick="window.location='{{$table['url']}}/{{$table['data'][$o][$table['id']]}}/edit';">
                @endif
            @else
                <tr>
            @endif    
                    <td class="{{$table['c_ths'][0]}}" >{{++$table['i']}}</td>
                    @for ($oa = 0; $oa < count($table['tds']); $oa++)
                        <td class="{{$table['c_ths'][$oa+1]}}" >
                        @if(is_numeric($table['data'][$o][$table['tds'][$oa]]) == true)
                            {{ number_format($table['data'][$o][$table['tds'][$oa]], '2', ',', '.')  }}
                        @elseif(DateTime::createFromFormat('Y-m-d', $table['data'][$o][$table['tds'][$oa]]) == true)
                            {{date('d-m-Y', strtotime($table['data'][$o][$table['tds'][$oa]]))}}
                        @else
                            {{$table['data'][$o][$table['tds'][$oa]] ?? 'N/A'}}
                        @endif
                        </td>
                    @endfor
                    @if ($table['edit_modal'] == true)
                        <td width="3%" class="{{$table['c_ths'][0]}}" ><a class="btn btn-warning mb-0" id="editCompany" onclick="editModal('{{$table['data'][$o][$table['id']]}}');"><i class="fas fa-pen"></i></a></td>
                    @endif
                </tr>
            @endfor
            </tbody>
        </table>
<div id="paginacion" class="d-flex justify-content-center mt-3 nb-0">
    {!! $table['data']->render() !!}      
</div>

 @endif

        </x-cards>

        </div>
        </div>
        </div>


        


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
                    <p>A seleccionado eliminar el almacen: </p>
                    <p>{{$getDataWarehouse->name_warehouse}}</p>
                    <p>Una vez eliminado no podra ser recuperado de nuevo</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    {!! Form::open(['method' => 'DELETE','route' => ['warehouse.destroy', $getDataWarehouse->id_warehouse],'style'=>'display:inline']) !!}
                        <button class="btn btn-danger btn-icon-split" type="submit">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">{{$conf['delete']['name']}}</span>
                        </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


        @endsection


        