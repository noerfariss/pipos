<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('sneat-1.0.0/assets/') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ $title_web }}</title>

    <meta name="description" content="Pipos - Pitagoras Point of sales application" />
    <meta name="author" content="NUR FARIS PRASTYO" />

    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}" />
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet"
        href="{{ asset('sneat-1.0.0/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('member.layouts.sidebar')

            <!-- Layout container -->
            <div class="layout-page">
                @include('member.layouts.header')

                <div class="content-wrapper">

                    @yield('konten')

                    @include('member.layouts.footer')

                    <div class="content-backdrop fade"></div>
                </div>

            </div>

        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    @csrf

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('sneat-1.0.0/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/assets/js/main.js') }}"></script>

    @yield('script', '')

</body>

</html>
