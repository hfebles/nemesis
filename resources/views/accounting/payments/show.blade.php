@extends('layouts.app')
@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')



<div class="row">
    <x-cards size="12">
    <table class="table table-sm table-bordered">
        <tr>
            <td colspan="2">Fecha del pago: {{ date('d/m/Y', strtotime($data->date_payment)) }}</td>
        </tr>
        <tr>
            <td>Cliente: {{$data->name_client}}</td>
            <td>Factura: {{$data->ref_name_invoicing}}</td>
        </tr>
        <tr>
            <td>Banco: {{$data->name_bank}}</td>
            <td>Monto: {{ number_format($data->amount_payment, 2, ',', '.') }}</td>
        </tr>
    </table>
    </x-cards>
    
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
                    {{-- <p>A seleccionado eliminar al usuario: {{$user->name}}</p> --}}
                    <p>Una vez eliminado no podra ser recuperado de nuevo</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    {{-- {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                        <button class="btn btn-danger btn-icon-split" type="submit">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Eliminar grupo</span>
                        </button>
                    {!! Form::close() !!} --}}
                </div>
            </div>
        </div>
    </div>
@endsection