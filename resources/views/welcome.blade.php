<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AirNav Indonesia</title>

  <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">

</head>
<body>

<div class="hero">

    <div class="overlay"></div>

    <div class="content">

        <img
            src="{{ asset('assets/img/branding/logo-old.png') }}"
            class="logo"
            alt="Logo">

        <h1>AirNav Indonesia</h1>

        <h3>Serving Flight Navigation for Safety</h3>

       @if (Auth::check())
    <a href="{{ route('dashboard') }}" class="btn-login">
        Dashboard
    </a>
@else
    <a href="{{ route('login') }}" class="btn-login">
        Login
    </a>
@endif

    </div>

</div>

</body>
</html>
