@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            
            {{-- ALERT / TOASTER --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <strong>Gagal menyimpan!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- END ALERT --}}

            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('account.settings') }}">
                            <i class="ri-group-line me-2"></i>Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('account.security') }}">
                            <i class="ri-lock-line me-2"></i>Security
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card mb-6">
                <form id="formAccountSettings" method="POST" action="{{ route('account.update.details') }}">
                    @csrf
                    @method('PATCH')

                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-6">
                            
                            @php
                                $hash = md5(strtolower(trim(Auth::user()->email)));
                                $gravatarUrl = "https://www.gravatar.com/avatar/$hash?s=200&d=mp";
                            @endphp

                            <img src="{{ $gravatarUrl }}"
                                alt="user-avatar"
                                class="d-block w-px-100 h-px-100 rounded-circle object-fit-cover"
                                id="uploadedAvatar" />
                            
                            <div class="button-wrapper">
                                <a href="https://gravatar.com/" target="_blank" class="btn btn-outline-primary me-3 mb-4">
                                    <i class="ri-external-link-line me-1"></i> Change on Gravatar
                                </a>
                                
                                <div class="text-muted small">
                                    Foto profil dikelola via Gravatar.<br>
                                    Login ke Gravatar dengan email Anda untuk mengubahnya.
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-0">

                    <div class="card-body pt-4">
                        <div class="row mt-1 g-5">
                            
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="fullname" name="fullname" value="{{ old('fullname', Auth::user()->fullname) }}" autofocus />
                                    <label for="fullname">Full Name</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="position" value="{{ Auth::user()->position ?? 'Karyawan' }}" readonly disabled style="background-color: #f5f5f9;" />
                                    <label for="position">Position</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="phoneNumber" name="phone_number" class="form-control" value="{{ old('phone_number', Auth::user()->phone_number) }}" />
                                        <label for="phoneNumber">Phone Number</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', Auth::user()->address) }}" />
                                    <label for="address">Address</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state', Auth::user()->state) }}" />
                                    <label for="state">State</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', Auth::user()->city) }}" />
                                    <label for="city">City</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="zipCode" name="zip_code" value="{{ old('zip_code', Auth::user()->zip_code) }}" maxlength="6" />
                                    <label for="zipCode">Zip Code</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select id="country" name="country" class="select2 form-select">
                                        <option value="Indonesia" @selected(old('country', Auth::user()->country) == 'Indonesia')>Indonesia</option>
                                        <option value="United States" @selected(old('country', Auth::user()->country) == 'United States')>United States</option>
                                    </select>
                                    <label for="country">Country</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection