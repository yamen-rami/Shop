@extends('layouts.storefront', ['storefrontDemoStyles' => false])

@section('title', __('Confirm Password'))
@section('auth-description', __('Please confirm your password before continuing.'))

@section('content')
    @include('auth.page-start')
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <x-auth.field name="password" :label="__('Password')" type="password" autocomplete="current-password" autofocus />
        <span class="ec-login-wrap ec-login-btn">
            <button type="submit" class="btn btn-primary">{{ __('Confirm Password') }}</button>
        </span>
    </form>
    @include('auth.page-end')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endpush
