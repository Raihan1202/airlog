@extends('layouts.app')

@section('title', 'Security')

@section('content')
<div class="row">
    <div class="col-md-12">
        
        {{-- Navigation Pills --}}
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('account.settings') }}">
                        <i class="ri-group-line me-2"></i>Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('account.security') }}">
                        <i class="ri-lock-line me-2"></i>Security
                    </a>
                </li>
            </ul>
        </div>

        <div class="card mb-6">
            <h5 class="card-header">Change Password</h5>
            <div class="card-body pt-1">
                
                {{-- 1. TAMPILKAN ALERT SUKSES/ERROR --}}
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible mb-3" role="alert">
                        {{ session('successMessage') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('errorMessage'))
                    <div class="alert alert-danger alert-dismissible mb-3" role="alert">
                        {{ session('errorMessage') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible mb-3" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 2. GANTI ID FORM AGAR TIDAK BENTROK DENGAN JS BAWAAN TEMPLATE --}}
                <form id="formChangePassword" method="POST" action="{{ route('account.update.password') }}">
                    @csrf
                    @method('PUT')

                    {{-- Current Password --}}
                    <div class="row">
                        <div class="mb-5 col-md-6 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    {{-- Tambah class is-invalid jika error --}}
                                    <input class="form-control @error('current_password') is-invalid @enderror" type="password" name="current_password" id="currentPassword" placeholder="············" required>
                                    <label for="currentPassword">Current Password</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                            </div>
                            {{-- Tampilkan error spesifik per field --}}
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div class="row g-5 mb-6">
                        <div class="col-md-6 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="············" required>
                                    <label for="password">New Password</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm New Password --}}
                        <div class="col-md-6 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="············" required>
                                    <label for="password_confirmation">Confirm New Password</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                            </div>
                        </div>
                    </div>

                    {{-- Password Requirements --}}
                    <h6 class="text-body">Password Requirements:</h6>
                    <ul class="ps-4 mb-0">
                        <li class="mb-4">Minimum 8 characters long - the more, the better</li>
                        <li class="mb-4">At least one uppercase & lowercase character</li>
                        <li>At least one number and symbol</li>
                    </ul>

                    {{-- Submit and Reset Buttons --}}
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Recent Devices Section (Tidak ada perubahan) --}}
        <div class="card">
            <h6 class="card-header">Recent Devices</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-truncate">Browser</th>
                            <th class="text-truncate">Device</th>
                            <th class="text-truncate">Location</th>
                            <th class="text-truncate">Recent Activities</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentDevices as $device)
                        <tr>
                            <td class="text-truncate text-heading">
                                @php
                                    $os = strtolower($device->os);
                                    $icon = 'ri-computer-line'; 
                                    $color = 'text-warning';
                                    if (str_contains($os, 'windows')) { $icon = 'ri-computer-line'; $color = 'text-info'; }
                                    elseif (str_contains($os, 'android')) { $icon = 'ri-android-line'; $color = 'text-success'; }
                                    elseif (str_contains($os, 'ios')) { $icon = 'ri-apple-fill'; $color = 'text-secondary'; }
                                    elseif (str_contains($os, 'mac')) { $icon = 'ri-macbook-line'; $color = 'text-dark'; }
                                @endphp
                                <i class="{{ $icon }} ri-20px {{ $color }} me-3"></i>
                                {{ $device->browser }} on {{ $device->os }}
                            </td>
                            <td class="text-truncate">{{ $device->device_type }}</td>
                            <td class="text-truncate">{{ $device->country ?? 'Unknown' }}</td>
                            <td class="text-truncate">{{ \Carbon\Carbon::parse($device->last_login)->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-truncate text-heading">
                                <i class="ri-macbook-line ri-20px text-warning me-3"></i>No recent devices found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection