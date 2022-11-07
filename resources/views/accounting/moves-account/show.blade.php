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
        <td  width="8%" class="text-center">Fecha</td>
        <td colspan="2" width="50%" class="text-center">Descripcion</td>
        <td class="text-center">Debe</td>
        <td class="text-center">Haber</td>
    </tr>
    @foreach ($data as $d)
    <tr>
        <td class="text-center">{{ date('d-m-Y', strtotime($d->date_accounting_entries)) }}</td>
            <td colspan="2">{{ $d->description_accounting_entries }}</td>
            <td class="text-end">{{ number_format($d->monto_debe, '2', ',', '.') ?? 0,00 }}</td>          
            <td class="text-end">{{ number_format($d->monto_haber, '2', ',', '.') ?? 0,00 }}</td>
    </tr>

@endforeach
<tr>
    <td colspan="3"></td>
    <td class="text-end">{{ number_format($totales['debe'], '2', ',', '.') ?? 0,00 }}</td>
    <td class="text-end">{{ number_format($totales['haber'], '2', ',', '.') ?? 0,00 }}</td>
</tr>

</table>
    </x-cards>
</div>
  
@endsection

