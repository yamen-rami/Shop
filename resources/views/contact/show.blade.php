@extends('layouts.storefront')

@section('header')
Showing {{ $contact->title }}
@endsection
@section("title")
  Showing Contact {{ $contact->title }}
@endsection
@section('content')
  <x-home.navbar />
  <main class="container section-space-p">
    @hasSection('header')
      <h1>@yield('header')</h1>
    @endif

  <div class="row mb-12 g-6">
    <div class="col-md">
      <div class="card">
        <div class="row">

          <div class="col-md-8">
            <div class="card-body">
              <h1 class="card-title fs-4">Title : {{ $contact->title }}</h1>
              <p class="card-text"><strong>Description : </strong>{{ $contact->desc }}</p>
              <p class="card-text"><strong>Email : </strong>{{ $contact->email }}</p>

              <h1>
                <strong>Coming Form :</strong>
               {{ $contact->user->name }}
              </h1>
              <p class="card-text"><small class="text-body-secondary"> <strong> Sending At : </strong>{{ $contact->created_at }}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </main>
  <x-footer />
  <x-home.menu />
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">
@endpush
