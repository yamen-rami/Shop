@extends('admin')
@section('title', 'Edit offer')
@section('header', 'Edit offer')
@section('content')
    <div class="card"><div class="card-body">@include('partials.offer-form')</div></div>
@endsection
@include('partials.select2-assets')
