<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-wide customizer-hide"
    data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', '')" />

    {{-- Styles --}}
    @include('dashboard.partials.styles')

    {{-- Page specific CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">

    {{-- Config --}}
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    {{-- Main auth content --}}
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6 mx-4">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @include('dashboard.partials.scripts')
</body>

</html>
