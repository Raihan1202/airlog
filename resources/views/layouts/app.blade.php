<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
      data-theme="theme-default"
      data-assets-path="{{ asset('/assets') }}/"
      data-template="vertical-menu-template"
      data-style="light">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

  <title>{{ config('app.name', 'Logbook') }} - @yield('title', 'Dashboard')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/img/branding/logo-small.png') }}">

  {{-- Fonts & CSS --}}
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"/>
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-bordered.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/typeahead.css') }}">
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

  {{-- Helpers & Config --}}
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>

  @stack('styles')
</head>
<body>
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">

    {{-- Sidebar --}}
    @include('layouts.header')

    <div class="layout-page">

      {{-- Navbar --}}
      @include('layouts.navbar')

      <div class="content-wrapper">
        {{-- Content --}}
        <div class="container-xxl flex-grow-1 container-p-y">
          @yield('content')
        </div>

        {{-- Footer --}}
        @include('layouts.footer')
      </div>
    </div>
  </div>
</div>

{{-- Scripts --}}
<!-- Core JS -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

<script>
$(document).ready(function () {
    // 1. Definisikan array untuk mengumpulkan semua pesan.
    const successMessages = [];
    const errorMessages = [];

    const notyf = new Notyf({
        duration: 5000,
        position: { x: 'right', y: 'bottom' },
    });

    // 2. Modifikasi fungsi flash agar HANYA MENGUMPULKAN pesan ke array.
    function flash(type, message) {
        if (!message) return;
        if (!['success', 'error'].includes(type)) return;

        const msgs = Array.isArray(message) ? message : [message];

        msgs.forEach(msg => {
            if (type === 'success') successMessages.push(msg); // Kumpulkan
            else errorMessages.push(msg); // Kumpulkan
        });
    }

    // --- Ambil Pesan dari PHP Session (Backend) ---
    @if (session('successMessage'))
        @php
            $msg = session('successMessage');
            // Menghapus penggunaan variabel $encoded yang tidak perlu di sini
            $msgs_array = is_array($msg) ? $msg : [$msg];
        @endphp
        // Menggunakan flash untuk mengumpulkan pesan dari session PHP
        flash('success', {!! json_encode($msgs_array) !!}); 
    @endif

    @if (session('errorMessage'))
        @php
            $msg = session('errorMessage');
            $msgs_array = is_array($msg) ? $msg : [$msg];
        @endphp
        flash('error', {!! json_encode($msgs_array) !!});
    @endif

    // --- Ambil Pesan dari sessionStorage (Frontend) ---
    const storageSuccess = sessionStorage.getItem('successMessage');
    if(storageSuccess){
        try {
            const parsed = JSON.parse(storageSuccess);
            // Menggunakan flash untuk mengumpulkan pesan dari sessionStorage
            flash('success', Array.isArray(parsed) ? parsed : [parsed]); 
        } catch(e) {
            flash('success', storageSuccess);
        }
        sessionStorage.removeItem('successMessage');
    }

    const storageError = sessionStorage.getItem('errorMessage');
    if(storageError){
        try {
            const parsed = JSON.parse(storageError);
            flash('error', Array.isArray(parsed) ? parsed : [parsed]);
        } catch(e) {
            flash('error', storageError);
        }
        sessionStorage.removeItem('errorMessage');
    }
	
    // 3. BARIS INI KINI BERFUNGSI SEBAGAI DISPLAY TUNGGAL UNTUK SEMUA PESAN
    // Karena successMessages dan errorMessages sudah didefinisikan dan diisi.
    successMessages.forEach(msg => notyf.success(msg));
    errorMessages.forEach(msg => notyf.error(msg));
});
</script>


@stack('scripts')
</body>
</html>
