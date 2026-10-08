@extends('admin')
@section('title', 'Create Product')

@section('content')
    <div class="card mb-6">
        <div class="card-header">
            <h1 class="h5 mb-0">Create Product</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data" class="product-form">
                @csrf
                <x-product-form-fields />
                <x-product-image-upload />
                <div class="product-form-actions">
                    <button type="submit" class="btn btn-primary">Create product</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@include('partials.select2-assets')
