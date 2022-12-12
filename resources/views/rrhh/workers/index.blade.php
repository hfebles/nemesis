@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :create="$conf['create']" :group="$conf['group']" />
@endsection

@section('content')
    <div class="row">
        <x-cards size="12" :table="$table" />
    </div>

@endsection
