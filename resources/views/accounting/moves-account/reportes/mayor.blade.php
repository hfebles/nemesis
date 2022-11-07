@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row g-3">
<x-cards>
    <table class="table table-bordered table-sm table-hover">
        <tr>
            <td>fecha</td>
            <td>desc</td>
            <td>debe</td>
            <td>haber</td>
        </tr>
        
        @foreach ($data as $d)
        <tr>
            <td>{{ $d->date_moves_account }}</td>
            <td>{{ $d->description_accounting_entries }}</td>
            <td>
                @if ($d->type_moves_account == 1)
                {{ $d->amount_accounting_entries }}
                @endif
            </td>
            <td>
                @if ($d->type_moves_account == 3)
                {{ $d->amount_accounting_entries }}
                @endif
            </td>
        </tr>
        @endforeach

        <tr>
            <td></td>
            <td></td>
            <td>{{ $totales['debe'] }}</td>
            <td>{{ $totales['haber'] }}</td>
        </tr>
    </table>
</x-cards>
    </div>
@endsection