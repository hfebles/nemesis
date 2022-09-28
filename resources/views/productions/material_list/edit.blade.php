@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" :delete="$conf['delete']" />
@endsection



@section('content')
{!! Form::model($data, ['method' => 'PATCH','route' => ['material-list.update', $data->id_materials_list]]) !!}
<div class="row g-3">
    <x-cards size="12">
        <div class="row g-3">
        <div class="col-md-3">
                <label for="inputState" class="form-label">Producto: {{ $data['name_product']}}</label>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-3">
                <label class="form-label">Cantidad a producir: {{ $data['qty_materials_list']}}</label>
            </div>

            <div class="col-md-3">
                <label for="inputState" class="form-label">Presentación: {{ $data['name_presentation_product']}}</label>
            </div>

        </div>
    </x-cards>

    <x-cards>
    <table class="table table-sm border-dark table-bordered mb-0" id="myTable">
        <tr>
            
            <th scope="col" class="align-middle">PRODUCTO</th>
            <th scope="col" class="text-center align-middle" width="10%">CANTIDAD</th>
            <th scope="col" class="text-center align-middle" width="10%">UNIDAD</th>
            <th scope="col" class="text-center align-middle bg-success" width="4%"><a onclick="addRow()" class="btn btn-success btn-sm mb-0 btn-block"><i class="fas fa-plus-circle fa-lg"></i></a></th> 
            
        </tr>   

        @for ($i=0; $i < count($objDetails['id_product_details']); $i++)
            <tr>
                <td class="align-middle">{{ $dataProductsDetails[$i][0]['name_product'] }}<input type="hidden" value="{{$objDetails['id_product_details'][$i]}}" name="id_product_details[]" /></td>
                <td class="text-center align-middle">{{ $objDetails['qtys'][$i] }}<input type="hidden" value="{{$objDetails['qtys'][$i]}}" name="qtys[]" /></td>
                <td class="text-center align-middle">{{ $dataPresentationDetails[$i][0]['name_presentation_product'] }}<input type="hidden" value="{{$objDetails['id_presentation_details'][$i]}}" name="id_presentation_details[]" /></td>
                <td class="bg-danger"><a onclick="borrarRow(this)" class="btn btn-block mb-0 btn-danger mb-0"><i class="fas fa-minus-circle"></i></a></td>
            </tr>
        @endfor
    </table> 
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
                    <p>A seleccionado eliminar la lista del producto: </p>
                    <p>{{ $data['name_product']}}</p>
                    <p>Una vez eliminado no podra ser recuperado de nuevo</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    {!! Form::open(['method' => 'DELETE','route' => ['material-list.destroy', $data->id_materials_list],'style'=>'display:inline']) !!}
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

@section('js')
<script type="text/javascript">
  var i = 0;


  function borrarRow(x){
 
 var i = x.parentNode.parentNode.rowIndex;
 document.getElementById("myTable").deleteRow(i);
}

function addRow(){

var table = document.getElementById("myTable");
var row = table.insertRow(-1);
row.id = 'tr_'+i

var cell2 = row.insertCell(-1);
var cell3 = row.insertCell(-1);
var cell4 = row.insertCell(-1);
var cell5 = row.insertCell(-1);


select = `{!! Form::select('id_product_details[]', $productos, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}`;





cell2.innerHTML = select;
cell3.innerHTML = "<input type='text' name='qtys[]' required class='form-control form-control-sm' onkeypress='return soloNumeros(event);' autocomplete='off'>";




cell4.innerHTML = `{!! Form::select('id_presentation_details[]', $presentaciones, null, ['required', 'class' => 'form-select form-control-sm', 'placeholder' => 'Seleccione']) !!}`;




cell5.innerHTML = '<a onclick="borrarRow(this)" class="btn btn-block mb-0 btn-danger mb-0"><i class="fas fa-minus-circle"></i></a>'; 


i++


}
</script>
@endsection