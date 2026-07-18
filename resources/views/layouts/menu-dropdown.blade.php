<ul class="dropdown-menu dropdown-menu-end">
  <li>
    <a class="dropdown-item" href="{{ url('/profile/'.Auth::user()->name) }}">
      <i class="ri-user-line me-2"></i>
      <span class="align-middle">My Profile</span>
    </a>
  </li>

  <li>
    <a class="dropdown-item" href="{{ url('/account/settings') }}">
      <i class="ri-settings-3-line me-2"></i>
      <span class="align-middle">Settings</span>
    </a>
  </li>

  <li>
    <hr class="dropdown-divider" />
  </li>

  <li>
    {{-- Logout pakai form biar CSRF aman --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="dropdown-item">
        <i class="ri-logout-box-line me-2"></i>
        <span class="align-middle">Log Out</span>
      </button>
    </form>
  </li>
</ul>
