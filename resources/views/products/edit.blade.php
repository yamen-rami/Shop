@extends('admin')
@section('title', 'Edit Product '.$product->name)
@section('header', 'Editing '.$product->name)

@section('content')
    <div class="card mb-6">
        <div class="card-header">
            <h1 class="h5 mb-0">Edit Product</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('product.update', $product) }}" enctype="multipart/form-data" class="product-form">
                @csrf
                @method('PATCH')
                <x-product-form-fields :product="$product" :selected-tags="$selectedTags ?? []" />
                <x-product-image-editor :product="$product" />
                <div class="product-form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@include('partials.select2-assets')
