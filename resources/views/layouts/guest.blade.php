<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="light-style layout-wide customizer-hide"
      dir="ltr"
      data-theme="theme-default"
      data-assets-path="{{ asset('/assets') }}/"
      data-template="vertical-menu-template"
      data-style="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Logbook') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/branding/logo-small.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Core CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    {{-- Page CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    {{-- Helpers --}}
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>
<body>
    {{-- Content --}}
    @yield('content')

    {{-- Core JS --}}
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/main.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/eye.js') }}"></script>
    <script src=".../menu.js"></script>


    {{-- Notification JS --}}
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var notyf = new Notyf({ duration: 4000, position: { x: 'right', y: 'bottom' } });

            @if(session('status'))
                notyf.success(@json(session('status')));
            @endif

            @if(session('success'))
                notyf.success(@json(session('success')));
            @endif

            @if(session('error'))
                notyf.error(@json(session('error')));
            @endif

            @foreach ($errors->all() as $error)
                notyf.error(@json($error));
            @endforeach
        });
    </script>

</body>
</html>
