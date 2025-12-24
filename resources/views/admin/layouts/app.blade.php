<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
    dir="{{ app()->getLocale() == 'en' ? 'ltr' : 'rtl' }}" data-theme="theme-default"
    data-assets-path="/dashboard/assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="is-admin" content="{{ auth()->user()->is_admin ?? false }}">

    <title>@yield('title') - {{ setting('site_name') }}</title>

    <meta name="description" content="@yield('description', 'TOTC Platform - Learning Management System')" />
    <meta name="theme-color" content="#696cff" />

    {{-- Preconnect to external domains for performance --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">

    @include('admin.partials.links')
    @yield('css')
</head>

<body>
    {{-- Loading overlay for better UX during page transitions --}}
    <div id="global-loading-overlay" class="d-none" role="status" aria-live="polite" aria-label="Loading">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar" role="main">
        <div class="layout-container">

            @include('admin.partials.sidebar')

            <!-- Layout container -->
            <div class="layout-page">

                @include('admin.partials.nav')


                <!-- Content wrapper -->
                <div class="content-wrapper">

                    @include('admin.partials.toaster')
                    @include('admin.partials.__confirm_modal')


                    @yield('content')

                    @include('admin.partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    @include('admin.partials.scripts')

    @yield('js')
</body>

</html>
