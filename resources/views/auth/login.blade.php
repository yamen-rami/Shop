@extends('layouts.auth')

@section('title', __('Log In'))

@section('auth-content')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <x-auth.field name="email" :label="__('Email Address')" type="email" :value="old('email')" autocomplete="username" :placeholder="__('Enter your email address')" autofocus />
        <x-auth.field name="password" :label="__('Password')" type="password" autocomplete="current-password" :placeholder="__('Enter your password')" />
        <span class="ec-login-wrap ec-auth-remember align-items-center">
            <label for="remember">
                <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                {{ __('Remember me') }}
            </label>
        </span>
        <span class="ec-login-wrap ec-login-fp">
            <a href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
        </span>
        <div class="d-flex justify-content-start">
            <span class="">
                <button class="btn btn-primary" type="submit">{{ __('Login') }}</button>
            </span>
        </div>
            <a href="{{ route('register') }}" class="text-center pt-2"> don't have an account ? {{ __('Register') }}</a>

    </form>
@endsection
