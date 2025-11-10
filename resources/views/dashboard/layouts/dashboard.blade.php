<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-menu-fixed layout-compact"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('dashboard.title') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    @include('dashboard.partials.styles')
    @stack('head')


</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            {{-- Sidebar --}}
            @include('dashboard.partials.sidebar')
            {{-- Layout container --}}
            <div class="layout-page">
                {{-- Navbar --}}
                @include('dashboard.partials.navbar')

                {{-- Content wrapper --}}
                <div class="content-wrapper">
                    {{-- Main content --}}
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>

                    {{-- Footer (simple) --}}
                    @include('dashboard.partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                {{-- / Content wrapper --}}
            </div>
            {{-- / Layout page --}}

        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('dashboard.partials.scripts')
    @stack('scripts')
</body>

</html>
