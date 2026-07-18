@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-6">
  <div class="col-md-12 col-xxl-8">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-md-6 order-2 order-md-1">
          <div class="card-body">
            <h4 class="card-title mb-4">Welcome <span class="fw-bold">{{ Auth::user()->name }}</span>!</h4>
            <p class="mb-0">There are {{ $totalAll }} totals of report logbook.</p>
            <p>You can view your profile if you need to check or share to someone.</p>
            <a href="{{ url('/profile/'.Auth::user()->name) }}" class="btn btn-primary">View Profile</a>
          </div>
        </div>
        <div class="col-md-6 text-center text-md-end order-1 order-md-2">
          <div class="card-body pb-0 px-0 pt-2">
            <img src="{{ asset('assets/img/illustrations/illustration-john-light.png') }}"
                 height="186" class="scaleX-n1-rtl" alt="View Profile">
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Loop cards --}}
  @foreach ($units as $unit)
    <div class="col-md-6 col-lg-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">{{ strtoupper($unit->nama) }}</h5>
          <p class="card-text">Total {{ $totals[$unit->id] ?? 0 }} reports</p>
          <a href="{{ url('/logbook/'.$unit->id.'/dashboard') }}" class="btn btn-primary">
            Go {{ strtoupper($unit->nama) }} reports
          </a>
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection
