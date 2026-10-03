@extends('layouts.storefront', ['storefrontDemoStyles' => false])

@section('title', __('Reset Password'))
@section('auth-description', __('Choose a new password for your account.'))

@section('content')
    @include('auth.page-start')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-auth.field name="email" :label="__('Email Address')" type="email" :value="old('email', $request->email)" autocomplete="username" autofocus />
        <x-auth.field name="password" :label="__('New Password')" type="password" autocomplete="new-password" />
        <x-auth.field name="password_confirmation" :label="__('Confirm Password')" type="password" autocomplete="new-password" />
        <span class="ec-login-wrap ec-login-btn">
            <button class="btn btn-primary" type="submit">{{ __('Reset Password') }}</button>
            <a href="{{ route('login') }}" class="btn btn-secondary">{{ __('Back to Login') }}</a>
        </span>
    </form>
    @include('auth.page-end')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endpush
