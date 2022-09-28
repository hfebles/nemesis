@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')

<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')

<div class="row g-3">
    <x-cards>
        <table class="table table-bordered table-sm table-hover ">
            <tr>

                <td class="text-center">Fecha</td>
                <td colspan="2" class="text-center">Descripcion</td>

                <td>Debe</td>
                <td>Haber</td>
            </tr>
            @foreach ($data as $d)
            <tr>
                <td class="text-center">{{ date('d-m-Y', strtotime($d->date_accounting_entries)) }}</td>
                @if ($d->amount_debe_accounting_entries)
                
                    <td>{{ $d->description_accounting_entries }}</td>
                    <td></td>
                    
                @endif
                @if ($d->amount_haber_accounting_entries)
                <td></td>
                    <td>{{ $d->description_accounting_entries }}</td>
                    
                    
                @endif
                
                
                
                
                
                <td>{{ number_format($d->amount_debe_accounting_entries, '2', ',', '.') ?? 0,00 }}</td>
                <td>{{ number_format($d->amount_haber_accounting_entries, '2', ',', '.') ?? 0,00 }}</td>

            </tr>
            @endforeach

            <tr>
                <td colspan="3" class="text-center">Total:</td>
                <td>{{number_format($totales['debe'], '2', ',', '.')}}</td>
                <td>{{ number_format($totales['haber'], '2', ',', '.')}}</td>
                </tr>
        </table>
    </x-cards>
</div>
  
@endsection

