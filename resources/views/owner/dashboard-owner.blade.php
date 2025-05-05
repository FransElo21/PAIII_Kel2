@extends('layouts.owner.index-owner')
@section('content')
<div class="card w-100 overflow-hidden rounded-4">
    <div class="card-body position-relative p-4">
      <div class="row">
        <div class="col-12 col-sm-7">
          <div class="d-flex align-items-center gap-3 mb-5">
            <img src="assets/images/avatars/01.png" class="rounded-circle bg-grd-info p-1"  width="60" height="60" alt="user">
            {{-- <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('assets/images/avatars/default.png') }}" class="rounded-circle bg-grd-info p-1"  width="60" height="60" alt="user"> --}}
            <div class="">
              <p class="mb-0 fw-semibold">Welcome back</p>
              <h4 class="fw-semibold mb-0 fs-4 mb-0">{{ Auth::user()->name }}!</h4>
            </div>
          </div>
          <div class="d-flex align-items-center gap-5">
            <div class="">
              <h4 class="mb-1 fw-semibold d-flex align-content-center">$65.4K<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
              </h4>
              <p class="mb-3">Today's Sales</p>
              <div class="progress mb-0" style="height:5px;">
                <div class="progress-bar bg-grd-success" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <div class="vr"></div>
            <div class="">
              <h4 class="mb-1 fw-semibold d-flex align-content-center">78.4%<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
              </h4>
              <p class="mb-3">Growth Rate</p>
              <div class="progress mb-0" style="height:5px;">
                <div class="progress-bar bg-grd-danger" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-5">
          <div class="welcome-back-img pt-4">
             <img src="{{ asset('owner/assets/images/gallery/welcome-back-3.png') }}" height="180" alt="">
          </div>
        </div>
      </div><!--end row-->
    </div>
  </div>

  {{-- Tambahan 3 Card Count --}}
  <div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-light-primary text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-house-door-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">24</h5>
                    <small class="text-muted">Total Properties</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-light-success text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-journal-bookmark-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">128</h5>
                    <small class="text-muted">Total Bookings</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-light-warning text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">5</h5>
                    <small class="text-muted">Pending Approvals</small>
                </div>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> 
@endsection
