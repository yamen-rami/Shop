@extends('admin')
@section('title', 'Create offer')
@section('header', 'Create offer')
@section('content')
    <div class="card"><div class="card-body">@include('partials.offer-form')</div></div>
@endsection
@include('partials.select2-assets')
