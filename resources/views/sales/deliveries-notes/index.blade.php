@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        @if ($message = Session::get('message'))
            <x-cards size="12" :table="$table" :message="$message" />
        @else
            <x-cards size="12" :table="$table" />
        @endif
    </div>
    
@endsection
