@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row g-3">
        <x-cards>
            <table class="table table-bordered table-sm table-hover">
                <tr>
                    <td>Fecha</td>
                    <td>Descripcion</td>
                    <td>DEBE</td>
                    <td>HABER</td>
                </tr>

                @foreach ($data as $d)
                    <tr>
                        <td>{{ $d->date_moves_account }}</td>
                        <td>{{ $d->description_accounting_entries }}</td>
                        <td class="text-end">{{ number_format($d->monto_debe, '2', ',', '.') ?? 0, 00 }}</td>
                        <td class="text-end">{{ number_format($d->monto_haber, '2', ',', '.') ?? 0, 00 }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td></td>
                    <td></td>
                    <td>{{ number_format($totales['debe'], '2', ',', '.') }}</td>
                    <td>{{ number_format($totales['haber'], '2', ',', '.') }}</td>
                </tr>
            </table>
        </x-cards>
    </div>
@endsection
