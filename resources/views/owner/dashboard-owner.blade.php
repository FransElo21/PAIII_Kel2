@extends('layouts.owner.index-owner')
@section('content')

{{-- Welcome Card --}}
<div class="card w-100 overflow-hidden rounded-4 mb-4">
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
                <div class="progress-bar bg-grd-success" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <div class="vr"></div>
            <div class="">
              <h4 class="mb-1 fw-semibold d-flex align-content-center">78.4%<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
              </h4>
              <p class="mb-3">Growth Rate</p>
              <div class="progress mb-0" style="height:5px;">
                <div class="progress-bar bg-grd-danger" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-5">
          <div class="welcome-back-img pt-4">
             <img src="{{ asset('owner/assets/images/gallery/welcome-back-3.png') }}" height="180" alt="Welcome Image">
          </div>
        </div>
      </div><!--end row-->
    </div>
</div>

{{-- 3 Card Summary --}}
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-light-primary text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-house-door-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $propertyCount ?? 0 }}</h5>
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
                    <h5 class="mb-0 fw-bold">{{ $bookingCount ?? 128 }}</h5>
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
                    <h5 class="mb-0 fw-bold">{{ $pendingApprovalCount ?? 5 }}</h5>
                    <small class="text-muted">Pending Approvals</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart: Monthly Sales --}}
<div class="card shadow-sm rounded-4 p-3 mb-4">
    <h5>Monthly Sales Trend</h5>
    <canvas id="salesChart" height="100"></canvas>
</div>

{{-- Recent Bookings Table --}}
<div class="card shadow-sm rounded-4 p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Recent Bookings</h5>
        <a href="{{ route('pemilik.riwayat-transaksi') }}" class="btn btn-sm btn-primary">
            View All Bookings
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#Booking ID</th>
                    <th>Property</th>
                    <th>Guest</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentBookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->property_name ?? 'N/A' }}</td>
                    <td>{{ $booking->guest_name ?? $booking->user_name ?? 'N/A' }}</td>
                    <td>${{ number_format($booking->total_price, 2) }}</td>
                    <td>
                        @if ($booking->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif ($booking->status == 'confirmed')
                            <span class="badge bg-success">Confirmed</span>
                        @elseif ($booking->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @elseif ($booking->status == 'Belum Dibayar')
                            <span class="badge bg-secondary">Unpaid</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                        @endif
                    </td>
                    <td>{{ date('d M Y', strtotime($booking->created_at)) }}</td>
                    <td>
                      <a href="{{ route('booking-owner.detail', $booking->id) }}" class="btn btn-warning btn-sm rounded-circle" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">No recent bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySalesLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Sales ($)',
                data: {!! json_encode($monthlySalesData ?? [1200, 1900, 3000, 2500, 2700, 3200]) !!},
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
        }
    });
</script>

@endsection