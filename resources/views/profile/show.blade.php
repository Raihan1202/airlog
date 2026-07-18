@extends('layouts.app') {{-- Menggunakan layout utama --}}

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="user-profile-header-banner">
                    {{-- Gunakan helper asset() untuk path gambar statis --}}
                    <img src="{{ asset('assets/img/pages/default-banner.png') }}" alt="Banner image" class="rounded-top" style="width: 100%; height: 250px;">
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-5">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        {{-- GRAVATAR LOGIC --}}
                        @php
                            $hash = md5(strtolower(trim($user->email)));
                            $gravatarUrl = "https://www.gravatar.com/avatar/$hash?s=250&d=mp";
                        @endphp
                        
                        {{-- Menampilkan Gravatar --}}
                        <img src="{{ $gravatarUrl }}" alt="user image" class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img" width="120" height="125" style="border: 5px solid #fff;">
                    </div>
                    <div class="flex-grow-1 mt-4 mt-sm-12">
                        <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-6">
                            <div class="user-profile-info">
                                {{-- Menampilkan data dari objek $user --}}
                                <h4 class="mb-2">{{ $user->fullname ?? $user->name }}</h4>
                                <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4">
                                    <li class="list-inline-item">
                                        <i class="ri-user-2-line me-2 ri-24px"></i>
                                        <span class="fw-medium">{{ $user->position ?? 'Karyawan' }}</span>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="ri-map-pin-line me-2 ri-24px"></i>
                                        <span class="fw-medium">{{ $user->city ?? 'Unknown' }}</span>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="ri-calendar-line me-2 ri-24px"></i>
                                        {{-- Pastikan 'joined' adalah instance Carbon di model User --}}
                                        <span class="fw-medium">Joined {{ $user->created_at ? $user->created_at->format('F Y') : 'N/A' }}</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="javascript:void(0)" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_barcode">
                                <i class="ri-barcode-fill ri-16px me-2"></i>Show QR Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-sm-row mb-6 row-gap-2">
                    <li class="nav-item"><a class="nav-link active waves-effect waves-light" href="javascript:void(0);"><i class="ri-user-3-line me-2"></i>Profile</a></li>
                    {{-- Gunakan helper route() untuk membuat URL yang dinamis dan aman --}}
                    <li class="nav-item"><a class="nav-link waves-effect waves-light" href="{{ route('profile.notifications', $user->name) }}"><i class="ri-notification-line me-2"></i>Notifications</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <div class="card mb-6">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted small">About</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4"><i class="ri-user-3-line ri-24px"></i><span class="fw-medium mx-2">Username:</span> <span>{{ $user->name }}</span></li>
                        <li class="d-flex align-items-center mb-4"><i class="ri-tools-line ri-24px"></i><span class="fw-medium mx-2">Technician:</span> <span>{{ $user->technician ? 'True' : 'False' }}</span></li>
						<li class="d-flex align-items-center mb-4"><i class="ri-star-smile-line ri-24px"></i><span class="fw-medium mx-2">Role:</span>
                            <span>
                                @if($user->access_level == 2)
                                    Admin
                                @elseif($user->access_level == 1)
                                    Support
                                @else
                                    User
                                @endif
                            </span>
                        </li>
                        <li class="d-flex align-items-center mb-4"><i class="ri-flag-2-line ri-24px"></i><span class="fw-medium mx-2">Country:</span> <span>{{ $user->country ?? 'Indonesia' }}</span></li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">Contacts</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4"><i class="ri-phone-line ri-24px"></i><span class="fw-medium mx-2">Contact:</span> <span>{{ $user->phone_number }}</span></li>
                        <li class="d-flex align-items-center mb-2"><i class="ri-mail-open-line ri-24px"></i><span class="fw-medium mx-2">Email:</span> <span>{{ $user->email }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card mb-6">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted small">Overview</small>
                    <ul class="list-unstyled mb-0 mt-3 pt-1">
                        {{-- Logika count bisa dilakukan di controller untuk performa lebih baik --}}
                        <li class="d-flex align-items-center mb-4"><i class="ri-git-repository-line ri-24px"></i><span class="fw-medium mx-2">Logbook Created:</span> <span>{{ $user->notifications->count() }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8 col-lg-7 col-md-7">
            <div class="card card-action mb-6">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0"><i class="ri-bar-chart-2-line ri-24px text-body me-4"></i>Activity Timeline</h5>
                </div>
                <div class="card-body pt-5">
                    <ul class="timeline mb-0">
                        {{-- Gunakan @forelse untuk loop dan penanganan jika data kosong --}}
                        @forelse ($user->notifications->take(5) as $notification)
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-{{ ['primary', 'success', 'info'][array_rand(['primary', 'success', 'info'])] }}"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">{{ $notification->title }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-2">{{ $notification->body }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="timeline-item">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">No timeline yet on this user</h6>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal_barcode" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="modalCenterTitle">QR Code</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            {{-- Menggunakan helper route() untuk link QR Code --}}
            <img src="{{ route('profile.qr', $user->name) }}" alt="QR Code" style="width: 80%; height: auto;" />
          </div>
        </div>
      </div>
    </div>
</div>
@endsection