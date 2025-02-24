<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard - Yayasan Scriptura Indonesia')</title>

    <meta name="description" content="Admin CMS | Yayasan Scriptura Indonesia" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" 
    />

    <!-- Icons -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" /> --}}
    <link rel="stylesheet" href="{{ asset('css/tabler-icons.min.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/theme-semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/typeahead.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('admin/css/apex-charts.css') }}" /> --}}

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('admin/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    {{-- <script src="{{ asset('admin/js/template-customizer.js') }}"></script> --}}
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('admin/js/config.js') }}"></script>

    <style>
        .fancybox__container {
            z-index: 9999 !important;
        }
    </style>

    @yield('styles')

    @stack('style')

</head>