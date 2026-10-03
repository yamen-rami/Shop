@extends('layouts.storefront', ['storefrontDemoStyles' => false])

@section('title', __('Verify Email'))
@section('auth-description', __('Please verify your email address using the link we sent you.'))

@section('content')
    @include('auth.page-start')
    @if (session('status') === 'verification-link-sent')
        <p class="alert alert-success" role="status">{{ __('A new verification link has been sent to your email address.') }}</p>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <span class="ec-login-wrap ec-login-btn">
            <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>
        </span>
    </form>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <span class="ec-login-wrap ec-login-btn">
            <button type="submit" class="btn btn-secondary">{{ __('Log Out') }}</button>
        </span>
    </form>
    @include('auth.page-end')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endpush
