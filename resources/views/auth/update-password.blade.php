@extends('layouts.auth')

@section('title', __('Update Password'))
@section('auth-description', __('Ensure your account uses a long, random password to stay secure.'))

@section('auth-content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')
        <x-auth.field name="current_password" :label="__('Current Password')" type="password" autocomplete="current-password" error-bag="updatePassword" autofocus />
        <x-auth.field name="password" :label="__('New Password')" type="password" autocomplete="new-password" error-bag="updatePassword" />
        <x-auth.field name="password_confirmation" :label="__('Confirm Password')" type="password" autocomplete="new-password" error-bag="updatePassword" />
        <span class="ec-login-wrap ec-login-btn">
            <button class="btn btn-primary" type="submit">{{ __('Save Password') }}</button>
            <a href="{{ route('home') }}" class="btn btn-secondary">{{ __('home.home') }}</a>
        </span>
    </form>
@endsection
