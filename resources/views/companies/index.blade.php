@extends('admin')
@section('title', 'Companies')
@section('header', 'Companies')
@section('content')
    <livewire:record-table resource="companies" />
@endsection
