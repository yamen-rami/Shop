# User page layouts

User pages extend `layouts.storefront`. Login, registration, forgot-password,
reset-password, and update-password pages extend `layouts.auth`, which shares
the Ekka storefront assets and chrome. Their forms use the `auth-content`
section. Password confirmation and email verification currently extend
`layouts.user-guest`.

```blade
@extends('layouts.storefront')

@section('title', 'My page')

@section('content')
    <h1>My page</h1>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/my-page.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/my-page.js') }}"></script>
@endpush
```

`@stack('styles')` renders pushed CSS in the head after shared styles.
`@stack('scripts')` renders pushed JavaScript before the closing body tag
after shared scripts. The `asset()` PHP helper generates public asset URLs.
For assets shared by repeated components, use `@pushOnce` / `@endPushOnce`
to avoid duplicate output.
