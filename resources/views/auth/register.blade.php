@extends('layouts.auth')

@section('title', __('Register'))

@section('auth-content')
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <x-auth.field name="name" :label="__('Name')" :value="old('name')" autocomplete="name" :placeholder="__('Enter your name')" autofocus />
        <x-auth.field name="email" :label="__('Email Address')" type="email" :value="old('email')" autocomplete="username" :placeholder="__('Enter your email address')" />
        <x-auth.field name="password" :label="__('Password')" type="password" autocomplete="new-password" :placeholder="__('Enter your password')" />
        <x-auth.field name="password_confirmation" :label="__('Confirm Password')" type="password" autocomplete="new-password" :placeholder="__('Confirm your password')" />
        <span class="ec-login-wrap ec-login-btn">
            <button class="btn btn-primary" type="submit">{{ __('Register') }}</button>
            <a href="{{ route('login') }}" class="btn btn-secondary">{{ __('Login') }}</a>
        </span>
    </form>
@endsection
