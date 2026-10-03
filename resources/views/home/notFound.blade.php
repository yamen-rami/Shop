@extends('layouts.storefront')

@section('content')
  <x-home.navbar />
  <section class="ec-under-maintenance">

    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="under-maintenance">
            <h1>{{ __("home.error") }}</h1>
            <h4>{{ __("home.pageDesc") }}</h4>
            <a href="{{ route("home") }}" class="btn btn-lg btn-primary" tabindex="0">{{ __("home.go_back") }}</a>
          </div>
        </div>
        <div class="col-md-6 disp-768">
          <div class="under-maintenance">
            <img class="maintenance-img" src="assets/images/common/404.png" alt="maintenance">
          </div>
        </div>
      </div>
    </div>
  </section>
  <x-footer></x-footer>
@endsection