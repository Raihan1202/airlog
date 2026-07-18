<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="ri-menu-fill ri-22px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler fw-normal px-0" href="javascript:void(0);">
                    <i class="ri-search-line ri-22px scaleX-n1-rtl me-3"></i>
                    <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>
                </a>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-4 me-xl-1">
                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="ri-notification-2-line ri-22px"></i>
                    
                    @php
                        $unreadCount = isset($notifications) ? $notifications->where('is_read', 0)->count() : 0;
                    @endphp
                    
                    @if($unreadCount > 0)
                        <span id="notification-badge" class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">Notification</h6>
                            <span id="notification-count-text" class="badge rounded-pill bg-label-primary">{{ $unreadCount }} New</span>
                        </div>
                    </li>
                    
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush" id="notification-list">
                            @if(isset($notifications) && count($notifications) > 0)
                                @foreach($notifications as $notification)
                                    <li class="list-group-item list-group-item-action dropdown-notifications-item {{ $notification->is_read == 0 ? 'bg-label-secondary' : '' }}">
                                        <div class="d-flex align-items-center">
                                            
                                            <a href="{{ route('notifications.read', $notification->id) }}" target="_blank" class="d-flex flex-grow-1 text-decoration-none text-body align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                                            {{ strtoupper(substr($notification->author_name ?? 'SY', 0, 2)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                                    <small class="text-muted">{{ Str::limit($notification->body, 40) }}</small>
                                                    <div class="text-muted small mt-1">
                                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </a>

                                            <div class="flex-shrink-0 dropdown-notifications-actions d-flex flex-column align-items-end justify-content-center gap-2">
                                                @if($notification->is_read == 0)
                                                    <span class="badge badge-dot bg-primary"></span>
                                                @endif

                                                <button type="button" 
                                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill delete-notification-btn" 
                                                        data-url="{{ route('notifications.destroy', $notification->id) }}"
                                                        onclick="event.stopPropagation();">
                                                    <i class="ri-delete-bin-line text-danger"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex justify-content-center p-3">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ri-notification-off-line ri-48px text-muted mb-2"></i>
                                            <small class="text-muted">No notifications found</small>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </li>
                    
                    <li class="dropdown-menu-footer border-top">
                        <a href="{{ route('notifications.markAll') }}" class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 align-items-center">
                            Mark all as read
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        {{-- GRAVATAR LOGIC --}}
                        @php
                            $navbarHash = md5(strtolower(trim(Auth::user()->email)));
                            $navbarGravatar = "https://www.gravatar.com/avatar/$navbarHash?s=200&d=mp";
                        @endphp
                        
                        <img src="{{ $navbarGravatar }}" 
                             alt="profile" class="rounded-circle" style="object-fit: cover;"/>
                    </div>
                </a>
                @include('layouts.menu-dropdown')
            </li>
        </ul>
    </div>

    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search..." />
        <i class="ri-close-fill search-toggler cursor-pointer"></i>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-notification-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); 
                e.stopPropagation(); 

                const url = this.getAttribute('data-url');
                const listItem = this.closest('li.dropdown-notifications-item');
                const token = "{{ csrf_token() }}";

                Swal.fire({
                    title: 'Hapus Notifikasi?',
                    text: "Notifikasi ini akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#8592a3',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger me-3',
                        cancelButton: 'btn btn-outline-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                listItem.style.transition = 'all 0.3s ease';
                                listItem.style.opacity = '0';
                                setTimeout(() => {
                                    listItem.remove();
                                    updateNotificationCount();
                                }, 300);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'Notifikasi berhasil dihapus.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Terjadi kesalahan server.', 'error');
                        });
                    }
                });
            });
        });

        function updateNotificationCount() {
            const badge = document.getElementById('notification-badge');
            const countText = document.getElementById('notification-count-text');
            
            if(countText) {
                let currentCount = parseInt(countText.innerText);
                if(currentCount > 0) {
                    currentCount--;
                    countText.innerText = currentCount + ' New';
                    
                    if(currentCount === 0 && badge) {
                        badge.remove();
                    }
                }
            }
        }
    });
</script>