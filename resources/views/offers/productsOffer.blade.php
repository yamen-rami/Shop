@extends('admin')
@section('title', 'Product offers')
@section('header', 'Product offers')
@section('content')
    <livewire:record-table resource="product-offers" />
@endsection
