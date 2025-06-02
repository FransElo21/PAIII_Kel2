@extends('layouts.admin.index-admin')
@section('content')

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-2">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <small class="text-secondary">Today is {{ date('l, M. d, Y') }}</small><br>
                <h5 class="font-weight-bold mb-0">Welcome, admin!</h5>

                <div>
                    <h4 class="font-weight-bold text-secondary mb-0 mt-3">Dashboard</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Total Pengusaha Card -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 py-3 rounded-lg" style="border-radius: 1rem;">
                <div class="card-body d-flex justify-content-between align-items-center px-4">
                    <div>       
                        <h3 class="text-primary font-weight-bold mb-0">{{ $totalPengusaha }}</h3>
                        <div class="small font-weight-bold text-primary text-uppercase mb-1">Total Pengusaha</div>
                    </div>
                    <div>
                        <i class="fas fa-plus text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Property Card -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 py-3 rounded-lg" style="border-radius: 1rem;">
                <div class="card-body d-flex justify-content-between align-items-center px-4">
                    <div>
                        <h3 class="text-success font-weight-bold mb-0">{{ $totalProperty }}</h3>
                        <div class="small font-weight-bold text-success text-uppercase mb-1">Total Property</div>
                    </div>
                    <div>
                        <i class="fas fa-plus text-success fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Booking Card -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 py-3 rounded-lg" style="border-radius: 1rem;">
                <div class="card-body d-flex justify-content-between align-items-center px-4">
                    <div>
                        <h3 class="text-info font-weight-bold mb-0">{{ $totalBooking }}</h3>
                        <div class="small font-weight-bold text-info text-uppercase mb-1">Total Booking</div>
                    </div>
                    <div>
                        <i class="fas fa-plus text-info fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

          <div class="row">
        <div class="col-12 col-xl-4">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-start justify-content-between">
                  <div class="">
                    <h5 class="mb-0">Order Status</h5>
                  </div>
                  <div class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                      data-bs-toggle="dropdown">
                      <span class="material-icons-outlined fs-5">more_vert</span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                      <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                      <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                    </ul>
                  </div>
                </div>
                <div class="position-relative">
                  <div class="piechart-legend">
                    <h2 class="mb-1">68%</h2>
                    <h6 class="mb-0">Total Sales</h6>
                  </div>
                  <div id="chart6"></div>
                </div>
                <div class="d-flex flex-column gap-3">
                  <div class="d-flex align-items-center justify-content-between">
                    <p class="mb-0 d-flex align-items-center gap-2 w-25"><span
                        class="material-icons-outlined fs-6 text-primary">fiber_manual_record</span>Sales</p>
                    <div class="">
                      <p class="mb-0">68%</p>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                    <p class="mb-0 d-flex align-items-center gap-2 w-25"><span
                        class="material-icons-outlined fs-6 text-danger">fiber_manual_record</span>Product</p>
                    <div class="">
                      <p class="mb-0">25%</p>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                    <p class="mb-0 d-flex align-items-center gap-2 w-25"><span
                        class="material-icons-outlined fs-6 text-success">fiber_manual_record</span>Income</p>
                    <div class="">
                      <p class="mb-0">14%</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-8">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Sales & Views</h5>
                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div id="chart5"></div>
              <div
                class="d-flex flex-column flex-lg-row align-items-start justify-content-around border p-3 rounded-4 mt-3 gap-3">
                <div class="d-flex align-items-center gap-4">
                  <div class="">
                    <p class="mb-0 data-attributes">
                      <span
                        data-peity='{ "fill": ["#2196f3", "rgb(255 255 255 / 12%)"], "innerRadius": 32, "radius": 40 }'>5/7</span>
                    </p>
                  </div>
                  <div class="">
                    <p class="mb-1 fs-6 fw-bold">Monthly</p>
                    <h2 class="mb-0">65,127</h2>
                    <p class="mb-0"><span class="text-success me-2 fw-medium">16.5%</span><span>55.21 USD</span></p>
                  </div>
                </div>
                <div class="vr"></div>
                <div class="d-flex align-items-center gap-4">
                  <div class="">
                    <p class="mb-0 data-attributes">
                      <span
                        data-peity='{ "fill": ["#ffd200", "rgb(255 255 255 / 12%)"], "innerRadius": 32, "radius": 40 }'>5/7</span>
                    </p>
                  </div>
                  <div class="">
                    <p class="mb-1 fs-6 fw-bold">Yearly</p>
                    <h2 class="mb-0">984,246</h2>
                    <p class="mb-0"><span class="text-success me-2 fw-medium">24.9%</span><span>267.35 USD</span></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!--end row-->


    <!-- Booking Table -->
    <div class="card mt-4">
    <div class="card-body">
        <div class="product-table">
            <div class="table-responsive white-space-nowrap">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><input class="form-check-input" type="checkbox"></th>
                            <th>Name</th>
                            <th>NIK</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($bookings as $booking)
                        <tr style="background: #f8f9fa; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <td><input class="form-check-input" type="checkbox" name="selected_bookings[]" value="{{ $booking->id }}"></td>
                            <td>{{ $booking->guest_name }}</td>
                            <td>{{ $booking->nik }}</td>
                            <td>{{ $booking->property_type ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d') }} to {{ \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d') }}</td>
                            <td class="text-center">

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>     
        <!-- Pagination (jika diperlukan) -->
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="card mt-4">
    <div class="card-body">
        <h5>Booking Per Hari (7 Hari Terakhir)</h5>
        <canvas id="bookingChart" height="100"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('bookingChart').getContext('2d');
    const bookingChart = new Chart(ctx, {
        type: 'bar', // bisa juga 'line'
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah Booking',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0,
                    stepSize: 1
                }
            },
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: { enabled: true }
            }
        }
    });
</script>


</div>
@endsection
