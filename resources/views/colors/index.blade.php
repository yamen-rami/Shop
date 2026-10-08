@extends('admin')
@section('title', 'Colors')

@section('content')
    <h1 class="h4 mb-4">Colors</h1>
    <p class="text-body-secondary">Manage the colors available when you upload product images.</p>
    @error('color')
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @enderror
    <livewire:record-table resource="colors" />
@endsection
