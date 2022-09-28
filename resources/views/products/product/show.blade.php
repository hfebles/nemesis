@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :edit="$conf['edit']" :group="$conf['group']" :delete="$conf['delete']" />
@endsection

@section('content')

<div class="row mb-5">
    <x-cards>
        <div class="table-responsive-lg">
            <table class="table table-bordered table-hover table-sm mb-0">
                <tr class="align-middle">
                    <th scope="col" width="15%">Código:</th>
                    <td width="25%">{{$data->code_product}}</td>
                    <th scope="col" width="20%">Nombre del producto:</th>
                    <td width="40%">{{$data->name_product}}</td>
                </tr>
                <tr class="align-middle">
                    <th scope="col">Numero de parte:</th>
                    <td>{{$data->part_number_product ?? ''}}</td>
                    <th scope="col">Descripción del producto:</th>
                    <td>{{$data->description_product}}</td>
                </tr>
                <tr class="align-middle">
                    <th scope="col">Precio del producto:</th>
                    <td>
                        @switch($data->product_usd_product)
                            @case(1)
                                $ {{number_format($data->price_product, '2', ',', '.')}}
                                @break
                        
                            @default
                                {{number_format($data->price_product, '2', ',', '.')}} Bs
                        @endswitch
                    </td>
                    <th scope="col">Disponible:</th>
                    <td>{{number_format($data->qty_product, '2', ',', '.')}} {{$data->short_unit_product}}</td>
                </tr>
                <tr class="align-middle">
                    <td>
                        @switch($data->salable_product)
                            @case(1)
                                <div class="form-check ml-1">
                                    <input class="form-check-input" type="checkbox" disabled checked>
                                    <label class="form-check-label">
                                        ¿Producto Vendible?
                                    </label>
                                </div>
                                @break
                            @default
                                <div class="form-check ml-1">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">
                                        ¿Producto Vendible?
                                    </label>
                                </div>
                        @endswitch
                    </td>
                    <td>
                        @switch($data->tax_exempt_product)
                            @case(1)
                                <div class="form-check ml-1">
                                    <input class="form-check-input" type="checkbox" disabled checked>
                                    <label class="form-check-label">
                                        ¿Producto exento de IVA?
                                    </label>
                                </div>
                                @break
                            @default
                                <div class="form-check ml-1">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">
                                        ¿Producto exento de IVA?
                                    </label>
                                </div>
                        @endswitch
                    </td>
                    <td>
                        @switch($data->product_usd_product)
                            @case(1)
                                <div class="form-check ml-1">
                                    <input class="form-check-input" type="checkbox" disabled checked>
                                    <label class="form-check-label">
                                        ¿Producto en dolares?
                                    </label>
                                </div>
                                @break
                            @default
                                <div class="form-check ml-1">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">
                                        ¿Producto en dolares?
                                    </label>
                                </div>
                        @endswitch
                    </td>
                </tr>
                <tr class="align-middle">
                    <th scope="col">Inventario:</th>
                    <td>{{$data->code_warehouse ?? 'N/A' }} - {{$data->name_warehouse ?? 'N/A' }} </td>
                    <th scope="col">Cateoría del producto:</th>
                    <td>{{$data->name_product_category ?? 'N/A'}}</td>
                </tr>
                <tr class="align-middle">
                    <th scope="col">Unidad de medida:</th>
                    <td>{{$data->short_unit_product ?? 'N/A' }} - {{$data->name_unit_product ?? 'N/A' }}</td>
                    <th scope="col">Presentacion del productoo:</th>
                    <td>{{$data->name_presentation_product ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </x-cards>
</div>



<div class="row mt-3">
    <x-cards :table="$table" />
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
                    <p>A seleccionado eliminar el producto: </p>
                    <p>{{$data->name_product}}</p>
                    <p>Una vez eliminado no podra ser recuperado de nuevo</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    {!! Form::open(['method' => 'DELETE','route' => ['product.destroy', $data->id_product],'style'=>'display:inline']) !!}
                        <button class="btn btn-danger btn-icon-split" type="submit">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Eliminar producto</span>
                        </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection