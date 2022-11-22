@extends('layouts.app')

@section('title-section', 'Menús')

@section('btn')
    <x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')

    @if ($message = Session::get('message'))
    @endif
    <div class="row">
        {{-- <x-cards size="12" :table="$table" />  --}}

        <table class="{{ $table['c_table'] }}">
            <thead class="{{ $table['c_thead'] }}">
                <tr>
                    @for ($i = 0; $i < count($table['ths']); $i++)
                        <td width="{{ $table['w_ts'][$i] }}%" class="{{ $table['c_ths'][$i] }}">{{ $table['ths'][$i] }}</td>
                    @endfor
                </tr>
            <tbody>
                @for ($o = 0; $o < count($table['data']); $o++)
                <tr style="cursor:pointer;" onclick="window.location='{{$table['url']}}/{{$table['data'][$o][$table['id']]}}';">
                        <td>{{ ++$table['i'] }}</td>
                        <td>{{ $table['data'][$o]->name }}</td>
                        <td>{{ $table['data'][$o]->slug }}</td>
                        <td>{{ $table['data'][$o]->order }}</td>
                        <td>
                            @if ($table['data'][$o]->enabled == 1)
                                <span class="text-success">Activo</span>
                            @else
                                <span class="text-danger">Inactivo</span>
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
            </thead>
        </table>
    </div>


@endsection
