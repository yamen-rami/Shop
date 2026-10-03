@extends('admin')
@section('title', 'Category offers')
@section('header', 'Category offers')
@section('content')
    <livewire:record-table resource="category-offers" />
@endsection
