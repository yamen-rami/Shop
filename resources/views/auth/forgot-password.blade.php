@extends('layouts.auth')

@section('title', __('Forgot Password'))
@section('auth-description', __('Enter your email address and we will send you a password reset link.'))

@section('auth-content')
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <x-auth.field name="email" :label="__('Email Address')" type="email" :value="old('email')" autocomplete="username" :placeholder="__('Enter your email address')" autofocus />
        <span class="ec-login-wrap ec-login-btn">
            <button class="btn btn-primary" type="submit">{{ __('Send Reset Link') }}</button>
            <a href="{{ route('login') }}" class="btn btn-secondary">{{ __('Back to Login') }}</a>
        </span>
    </form>
@endsection
