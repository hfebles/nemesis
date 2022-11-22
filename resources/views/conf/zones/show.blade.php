@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')

    <x-btns :back="$conf['back']" :group="$conf['group']" :edit="$conf['edit']" />
@endsection



@section('content')
    <div class="row g-3">
        <x-cards size="12">
            <div class="row g-3">
                <div class="col-md-12">
                    <p class="h4">Nombre de la zona: <span class="text-info">{{ $data->name_zone }}</span></p>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-12">
                    <p class="h3">Estados:</p>
                    <div class="row">
                        @foreach ($dataEstados as $value)
                            <div class="col-3">
                                <p class="h4">{{ $value->estado }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
        </x-cards>


    </div>


@endsection
