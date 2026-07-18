<a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
   href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
  <i class="ri-notification-2-line ri-22px"></i>
  @if(isset($unreadCount) && $unreadCount > 0)
    <span class="badge bg-danger rounded-pill badge-notifications">{{ $unreadCount }}</span>
  @endif
</a>

<ul class="dropdown-menu dropdown-menu-end py-0">
  <li class="dropdown-menu-header border-bottom py-50">
    <div class="dropdown-header d-flex align-items-center py-2">
      <h6 class="mb-0 me-auto">Notification</h6>
      @if(isset($unreadCount) && $unreadCount > 0)
        <div class="d-flex align-items-center">
          <span class="badge rounded-pill bg-label-primary fs-xsmall me-2">{{ $unreadCount }} New</span>
          
          {{-- Tombol Mark All as Read (Menggunakan Form agar method PATCH aman) --}}
          <a href="javascript:void(0);" 
             class="btn btn-text-secondary rounded-pill btn-icon"
             title="Mark all as read"
             onclick="event.preventDefault(); document.getElementById('mark-all-notifications-form').submit();">
            <i class="ri-mail-open-line text-heading ri-20px"></i>
          </a>
          <form id="mark-all-notifications-form" action="{{ route('notifications.markAll') }}" method="POST" style="display: none;">
              @csrf
              @method('PATCH') {{-- Sesuai Controller update --}}
          </form>
        </div>
      @endif
    </div>
  </li>

  <li class="dropdown-notifications-list scrollable-container">
    <ul class="list-group list-group-flush">
      {{-- Cek apakah $notifications ada isinya --}}
      @if(isset($notifications) && $notifications->count() > 0)
          @foreach ($notifications as $row)
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    {{-- Handling gambar profile agar tidak error jika null --}}
                    <img src="{{ asset('assets/img/profile/'.($row->profile ?? 'default.png')) }}"
                         alt="profile"
                         class="rounded-circle" width="36" height="36"
                         style="object-fit: cover;"/>
                  </div>
                </div>
                <div class="flex-grow-1">
                  {{-- Klik judul akan mark as read --}}
                  <a href="{{ route('notifications.read', $row->id) }}" class="text-decoration-none text-body">
                      <h6 class="small mb-1">{{ $row->title }}</h6>
                      <small class="mb-1 d-block text-body">{{ Str::limit($row->body, 50) }}</small>
                  </a>
                  <small class="text-muted">{{ $row->created_at ? $row->created_at->diffForHumans() : '' }}</small>
                </div>

                {{-- Indikator Belum Dibaca (Dot Biru) --}}
                @if(is_null($row->read_at))
                  <div class="flex-shrink-0 dropdown-notifications-actions">
                    <a href="{{ route('notifications.read', $row->id) }}" class="dropdown-notifications-read">
                      <span class="badge badge-dot"></span>
                    </a>
                  </div>
                @endif
              </div>
            </li>
          @endforeach
      @else
        <li class="list-group-item text-center text-muted p-4">
            <span>No notifications available</span>
        </li>
      @endif
    </ul>
  </li>

  <li class="border-top">
    <div class="d-grid p-4">
      {{-- PERBAIKAN LINK VIEW ALL --}}
      <a class="btn btn-primary btn-sm d-flex" href="{{ route('profile.notifications', Auth::user()->name) }}">
        <small class="align-middle">View all notifications</small>
      </a>
    </div>
  </li>
</ul>