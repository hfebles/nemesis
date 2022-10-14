@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        @if ($message = Session::get('message'))
            <x-cards size="12" :message="$message" />
            @endif
            <x-cards>
                <div class="mb-3 d-flex">
                    <a target="_blank" href="{{ route('payments.general-print') }}" class="btn btn-sm btn-info btn-icon-split ml-auto">
                        <span class="icon text-white-50">
                            <i class="fas fa-print"></i>
                        </span>
                        <span class="text">Imprimir</span>
                    </a>
                </div>
            <table class="table table-sm table-bordered mb-0 table-hover">
                <tr class="bg-dark text-white">
                    <th class="text-center align-middle">#</th>
                    <th class="text-center align-middle">Fecha</th>
                    <th class="text-center align-middle">Cliente</th>
                    <th class="text-center align-middle">Factura</th>
                    <th class="text-center align-middle">Pedido</th>
                    <th class="text-center align-middle">Banco</th>
                    <th class="text-center align-middle">Monto</th>
                </tr>
                @foreach ($table['data'] as $tabla)
                    @switch($tabla->type_pay)
                        @case(1)
                        <tr style="cursor:pointer;" onclick="window.location='{{$table['url']}}/{{$tabla->id_payment}}';">
                                <td class="text-center align-middle">{{++$table['i']}}</td>
                                <td class="text-center align-middle">{{$tabla->date_payment}}</td>
                                <td class="text-center align-middle">{{$tabla->name_client}}</td>
                                <td class="text-center align-middle">{{$tabla->ref_name_invoicing}}</td>
                                <td class="text-center align-middle">N/A</td>
                                <td class="text-center align-middle">{{$tabla->name_bank}}</td>
                                <td class="text-center align-middle">{{$tabla->amount_payment}}</td>
                                
                            </tr>
                            
                            @break
                    
                        @default
                        <tr onclick="window.location='{{$table['url']}}/{{$tabla->id_payment}}';">
                            <td class="text-center align-middle">{{++$table['i']}}</td>
                            <td class="text-center align-middle">{{$tabla->date_payment}}</td>
                            <td class="text-center align-middle">{{$tabla->name_client}}</td>
                            <td class="text-center align-middle">N/A</td>
                            <td class="text-center align-middle">{{$tabla->ref_name_delivery_note}}</td>
                            <td class="text-center align-middle">{{$tabla->name_bank}}</td>
                            <td class="text-center align-middle">{{$tabla->amount_payment}}</td>
                            
                        </tr>
                            
                    @endswitch
                    
                @endforeach

            </table>
        </x-cards>
            
            
    </div>
@endsection





