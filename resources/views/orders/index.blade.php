@extends(auth()->user()->role === 'admin' ? 'admin' : 'layouts.storefront')
@section('title', 'Orders')
@section('header', 'Orders')
@section('content')
    @if(auth()->user()->role !== 'admin')<x-home.navbar /><main class="container section-space-p">@endif
    <livewire:record-table resource="orders" />
    @if(auth()->user()->role !== 'admin')</main><x-footer /><x-home.menu />@endif
@endsection
