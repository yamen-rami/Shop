@extends('admin')
@section('title', 'Global offers')
@section('header', 'Global offers')
@section('content')
    <livewire:record-table resource="offers" />
@endsection
