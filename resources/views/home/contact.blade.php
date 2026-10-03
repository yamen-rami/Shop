@extends('layouts.storefront')

@section('title')
{{ __("home.contact us") }}
@endsection

@section('content')

  {{-- <x-navbar /> --}}
  <x-home.navbar ></x-home.navbar>
  <x-loader />
  <section class="ec-page-content section-space-p ">
    <div class="container">
      <div class="row">
        <div class="ec-common-wrapper ">
          <div class="ec-contact-leftside">
            <div class="ec-contact-container">
              <div class="ec-contact-form">
                <form action="{{ route("contact.store") }}" method="post">
                  @csrf
                  <span class="ec-contact-wrap">
                    <label>{{ __("home.title") }}</label>
                    <input type="text" name="title" placeholder="{{ __("home.title") }}" required />
                  </span>
                  @error("title")
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                 
                  <span class="ec-contact-wrap">
                    <label>{{ __("home.email") }}</label>
                    <input type="email" name="email" placeholder="{{ __("home.email") }}" required />
                  </span>
                  @error("email")
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                 
                  <span class="ec-contact-wrap">
                    <label>{{  __("home.problem")}}</label>
                    <textarea name="desc" placeholder="{{ __("home.problem") }}"></textarea>
                  </span>
                  @error("desc")
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                  <span class="ec-contact-wrap ec-contact-btn">
                    <button class="btn btn-primary" type="submit">{{ __("home.submit") }}</button>
                  </span>
                </form>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </section>
  <x-category></x-category>
  <x-footer />
@endsection
