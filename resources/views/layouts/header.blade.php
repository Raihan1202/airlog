<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <img src="{{ asset('assets/img/branding/logo.png') }}" style="width:40px; height:40px;" alt="{{ config('app.name') }}">
      <span class="app-brand-text demo menu-text fw-semibold ms-2">‎ {{ config('app.name') }}</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path d="M8.47 11.71C8.12 12.07 8.12 12.65 8.47 13.01L12.07 16.61C12.46 17 12.46 17.63 12.07 18.02C11.68 18.41 11.05 18.41 10.66 18.02L5.83 13.19C5.37 12.74 5.37 11.99 5.83 11.53L10.66 6.71C11.05 6.32 11.68 6.32 12.07 6.71C12.46 7.1 12.46 7.73 12.07 8.12L8.47 11.71Z" fill-opacity="0.9" />
        <path d="M14.36 11.83C14.07 12.13 14.07 12.6 14.36 12.89L18.07 16.61C18.46 17 18.46 17.63 18.07 18.02C17.68 18.41 17.05 18.41 16.66 18.02L11.68 13.05C11.31 12.67 11.31 12.06 11.68 11.68L16.66 6.71C17.05 6.32 17.68 6.32 18.07 6.71C18.46 7.1 18.46 7.73 18.07 8.12L14.36 11.83Z" fill-opacity="0.4" />
      </svg>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>
  @include('layouts.sidebar')
</aside>
