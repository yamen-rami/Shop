@extends(auth()->user()->role === 'admin' ? 'admin' : 'layouts.storefront')
@section('title', 'Edit order')
@section('content')
    @if(auth()->user()->role !== 'admin')<x-home.navbar /><main class="container section-space-p">@endif
    <div class="card"><div class="card-header"><h1 class="h5">Edit order</h1></div>
        <div class="card-body">@include('partials.order-form')</div>
    </div>
    @if(auth()->user()->role !== 'admin')</main><x-footer /><x-home.menu />@endif
@endsection
@include('partials.select2-assets')
