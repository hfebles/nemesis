@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" :edit="$conf['edit']" />
@endsection



@section('content')

<div class="row g-3">
    <x-cards size="12">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="inputState" class="form-label">Producto</label>
                {{ $data['name_product']}}
            </div>

            <div class="clearfix"></div>

            <div class="col-md-3">
                <label class="form-label">Cantidad a producir</label>
                
                {{ $data['qty_materials_list']}}
            </div>

            <div class="col-md-3">
                <label for="inputState" class="form-label">Presentación</label>
                {{ $data['name_presentation_product']}}
                
            </div>

        </div>
    </x-cards>

    <x-cards>
    <table class="table table-sm border-dark table-bordered mb-" id="myTable">
        <tr>
            
            <th scope="col" class="align-middle">PRODUCTO</th>
            <th scope="col" class="text-center align-middle" width="10%">CANTIDAD</th>
            <th scope="col" class="text-center align-middle" width="10%">UNIDAD</th>
            
        </tr>   

        @for ($i=0; $i < count($objDetails['id_product_details']); $i++)
            <tr>
                <td class="align-middle">{{ $dataProductsDetails[$i][0]['name_product'] }}</td>
                <td class="text-center align-middle">{{ $objDetails['qtys'][$i] }}</td>
                <td class="text-center align-middle">{{ $dataPresentationDetails[$i][0]['name_presentation_product'] }}</td>
            </tr>
        @endfor
    </table> 
    </x-cards>

</div>


@endsection

