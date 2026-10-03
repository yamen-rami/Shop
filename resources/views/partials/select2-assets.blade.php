@pushOnce('styles', 'select2-styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user-selects.css') }}">
@endPushOnce

@pushOnce('scripts', 'select2-scripts')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/user-selects.js') }}"></script>
@endPushOnce
