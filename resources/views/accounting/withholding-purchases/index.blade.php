@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        <x-cards :table="$table" />
    </div>
@endsection
