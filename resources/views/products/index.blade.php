@extends('admin')
@section('title', 'Products')
@section('header', 'Products')
@section('content')
    <livewire:record-table resource="products" />
@endsection
@include('partials.select2-assets')
