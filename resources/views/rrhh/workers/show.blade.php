@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :edit="$conf['edit']" :group="$conf['group']" />
@endsection


@section('content')

<div class="row g-4">
<x-cards>
<div class="table-responsive-lg">
<table class="table table-bordered table-hover table-sm mb-0">
    <tr>
    <td width="15%" class="fw-semibold">Cédula:</td>
    <td colspan="3">{{number_format($data->dni_worker, 0, '', '.')}}</td>
    </tr>
<tr>
    <td class="fw-semibold">Apellidos y nombres:</td>
    <td colspan="3">{{$data->last_name_worker}} {{$data->firts_name_worker}}</td>
</tr>
<tr>
    
    <td class="fw-semibold">Teléfono:</td>
    <td>{{$data->phone_worker ?? 'N/A'}}</td>
    <td width="14%"class="fw-semibold">Correo electrónico:</td>
    <td>{{$data->mail_worker ?? 'N/A'}}</td>
</tr>
<tr>
    <td class="fw-semibold">Grupo del trabajador:</td>
    <td>{{$data->name_group_worker ?? 'N/A'}}</td>
    <td class="fw-semibold">Usuario trabajador:</td>
    <td>{{$data->name ?? 'N/A'}}</td>
</tr>
</table>
</div>
</x-cards> 
</div>




@endsection

