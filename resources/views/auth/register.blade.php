<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>

<body>

    @if (session()->has('pesan'))
        {!! session('pesan') !!}
    @endif

    <form action="{{ route('auth.register.post') }}" method="POST" enctype="multipart/form-data" id="my-form">
        @csrf
        <div>
            <label for="">email</label>
            <input type="text" name="email">
        </div>
        <div>
            <label for="">Whatsapp</label>
            <input type="text" name="whatsapp">
        </div>
        <div>
            <label for="">sekolah</label>
            <input type="text" name="sekolah">
        </div>
        <div>
            <label for="">npsn</label>
            <input type="text" name="npsn">
        </div>
        <div>
            <label for="">Telpon Sekolah</label>
            <input type="text" name="telpon">
        </div>


        <hr>

        <div>
            <label for="">password</label>
            <input type="password" name="password">
        </div>
        <div>
            <label for="">Konfirmasi password</label>
            <input type="password" name="password_confirmation">
        </div>
        <div>
            <button type="submit">Register</button>
            <a href="{{ route('auth.login')}}">Login</a>
        </div>
    </form>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
{!! JsValidator::formRequest('App\Http\Requests\Auth\RegisterRequest', '#my-form') !!}

</html>
