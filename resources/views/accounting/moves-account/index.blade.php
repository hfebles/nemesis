@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row g-3">

        <x-cards>
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Seleccione el reporte a imprimir
                </a>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('moves.reports', 11) }}">CXC</a></li>
                    <li><a class="dropdown-item" href="{{ route('moves.reports', 8) }}">Banco</a></li>
                    <li><a class="dropdown-item" href="{{ route('moves.reports', 76) }}">Iva</a></li>
                </ul>
            </div>
        </x-cards>
        <x-cards>
            <table class="{{ $table['c_table'] }}">
                <tr class="{{ $table['c_thead'] }}">
                    @foreach ($table['ths'] as $key_th => $th)
                        <th class="{{ $table['c_ths'][$key_th] }}" width="{{ $table['w_ts'][$key_th] }}%">{{ $th }}</th>
                    @endforeach
                </tr>
                @foreach ($table['data'] as $data)
                <tr style="cursor:pointer;" onclick="window.location='{{ $table['url'] }}/{{ $data->id_moves_account }}';">
                    <td>{{ ++$table['i'] }}</td>
                    <td>{{ $data->date_moves_account }}</td>
                    <td>{{ $data->ref_name_invoicing ?? $data->ref_name_purchase }}</td>
                    <td>{{ $data->id_moves_account }}</td>
                @endforeach
                    

                
            </table>
             {!! $table['data']->render() !!}      
        </x-cards>

    </div>
@endsection
