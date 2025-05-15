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
