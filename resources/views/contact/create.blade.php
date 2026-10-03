@extends(auth()->user()->role === 'admin' ? 'admin' : 'layouts.storefront')
@section('title', 'Create contact')
@section('content')
    @if(auth()->user()->role !== 'admin')<x-home.navbar /><main class="container section-space-p">@endif
    <div class="card"><div class="card-header"><h1 class="h5">Create contact</h1></div><div class="card-body">
        <form method="POST" action="{{ route('contact.store') }}">
            @csrf
            <x-form.input type="text" value="Title" feild="title" />
            <x-form.input type="email" value="Email" feild="email" :edit="auth()->user()->email" />
            <x-form.textarea value="Message" feild="desc" />
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div></div>
    @if(auth()->user()->role !== 'admin')</main><x-footer /><x-home.menu />@endif
@endsection
