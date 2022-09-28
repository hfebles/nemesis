@extends('layouts.app')

@section('title-section', 'Plan contable')



@section('btn')
<x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')
<div class="row">
    @if ($message = Session::get('message'))
        <x-cards :message="$message" />
    @endif
</div>

<div class="row">
<x-cards>

<table class="{{$table['c_table']}} ">
    <thead  class="{{$table['c_thead']}}">
        @foreach ($table['ths'] as $key_th =>  $th)
            <th width="{{$table['w_ts'][$key_th]}}%" class="{{$table['c_ths'][$key_th]}}">{{$th}}</th>
        @endforeach
    </thead>
@for ($g = 0; $g < count($table['data']); $g++)
    <tr onclick="window.location='{{ $table['url']}}/{{$table['data'][$g]->id_group }}';">
        <td class="{{$table['c_ths'][0]}}">{{++$table['i']}}</td>
        @foreach ($table['tds1'] as $kk => $td)
            <td class="{{$table['c_ths'][$kk]}}">{{$table['data'][$g]->$td}}</td>
        @endforeach
    </tr>
@endfor
</table>
<div id="paginacion" class="d-flex justify-content-center">
    {!! $table['data']->render() !!}      
</div>

</x-cards>
</div>
@endsection

