<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('sneat-1.0.0assets/') }}" data-template="vertical-menu-template-free">

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

    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0') }}/assets/vendor/css/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0') }}/assets/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0') }}/assets/vendor/css/pages/page-auth.css" />

</head>

<body style="background:#181869;">
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ route('auth.login') }}" class="app-brand-link gap-2">
                                <h2 class="">{!! $logo !!}</h2>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2"><b>Selamat Datang! 👋</b></h4>
                        <p class="mb-4">Pastikan email Anda sudah sesuai.</p>

                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <form class="mb-3" action="{{ route('auth.login.post') }}" method="POST"
                            enctype="multipart/form-data" id="my-form">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Masukkan email" autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <div id="password-error"></div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
{!! JsValidator::formRequest('App\Http\Requests\Auth\LoginRequest', '#my-form') !!}
