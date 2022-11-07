@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row g-3">
        @if ($message = Session::get('message'))
            <x-cards size="12" :table="$table" :message="$message" />
        @else
        <x-cards>
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Seleccione el reporte a imprimir
                </a>
              
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('moves.reports', 11) }}">CXC</a></li>
                  <li><a class="dropdown-item" href="{{ route('moves.reports', 8) }}">Banco</a></li>
                  <li><a class="dropdown-item" href="{{ route('moves.reports', 76) }}">Iva</a></li>
                </ul>
              </div>
        </x-cards>
            <x-cards size="12" :table="$table" />
        @endif
    </div>
@endsection

