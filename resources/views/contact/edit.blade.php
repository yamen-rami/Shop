@extends('layouts.storefront')

@section('header')
Editing {{ $contact->name }}
@endsection
@section("title")
  Edit contact {{ $contact->name }}
@endsection
@section('content')
  <x-home.navbar />
  <main class="container section-space-p">
    @hasSection('header')
      <h1>@yield('header')</h1>
    @endif

  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Edit Contact</h5>
          <small class="text-body-secondary float-end">contact</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("contact.update", $contact) }}">
            @csrf
            @method("PATCH")

            {{-- ? Name --}}
            <x-form.input type="text" edit="{{ $contact->title }}" value="Title" feild="title"></x-form.input>
            {{-- ? Desc --}}
            <x-form.textarea type="text" edit="{{ $contact->desc }}" value="Description" feild="desc"></x-form.textarea>
            {{-- ? Price --}}
            <x-form.textarea type="email" edit="{{ $contact->email }}" value="Email" feild="email"></x-form.textarea>

            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Update </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Basic with Icons -->

  </div>
  </main>
  <x-footer />
  <x-home.menu />
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">
@endpush
