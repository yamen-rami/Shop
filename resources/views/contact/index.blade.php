@extends(auth()->user()->role === 'admin' ? 'admin' : 'layouts.storefront')
@section('title', 'Contacts')
@section('header', 'Contacts')
@section('content')
    @if(auth()->user()->role !== 'admin')<x-home.navbar /><main class="container section-space-p">@endif
    <livewire:record-table resource="contacts" />
    @if(auth()->user()->role !== 'admin')</main><x-footer /><x-home.menu />@endif
@endsection
