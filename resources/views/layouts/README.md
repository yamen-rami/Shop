# User page layouts

All user-facing pages extend `layouts.storefront` directly and define a
`content` section. This includes the storefront, authentication, profile,
orders, contacts, and offer listings available to signed-in users.
Authentication pages reuse `auth.page-start` and `auth.page-end` markup
partials and push `auth.css` themselves. There is no separate authentication
layout. The admin dashboard continues to use its existing main component.

Page-specific assets belong in the `styles` and `scripts` stacks. Select2
loads only on forms that use it, after the shared jQuery dependency.

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
