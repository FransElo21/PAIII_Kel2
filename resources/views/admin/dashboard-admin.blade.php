@extends('layouts.admin.index-admin')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- ===========================
     Custom CSS Modern & Halus
  =========================== -->
<style>
  :root {
    --primary-color: #289A84;            /* Hijau utama */
    --primary-light: #E6F7F1;            /* Hijau sangat terang untuk latar */
    --secondary-color: #6C757D;          /* Abu-abu sekunder */
    --bg-light-gray: #F8F9FA;            /* Latar halaman */
    --bg-card: #ffffff;                  /* Latar kartu putih */
    --shadow-sm: 0 2px 6px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
    --border-radius-lg: 1rem;
    --border-radius-md: 0.5rem;
    --transition-fast: 0.2s ease-in-out;
    --transition-med: 0.4s ease-in-out;
    --font-base: 'Poppins', sans-serif;
  }

  body {
    font-family: var(--font-base);
    background-color: var(--bg-light-gray);
    color: #343A40;
  }

  /* ======= Container Utama ======= */
  /* ======= Typography Header ======= */
  .font-weight-bold {
    font-weight: 600 !important;
  }
  h5, h4, h3 {
    color: #343A40;
  }
  small {
    font-size: 0.9rem;
    color: var(--secondary-color);
  }

  /* ======= Card (Statistik & Grafik) ======= */
  .card {
    background-color: var(--bg-card);
    border: none;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    transition: transform var(--transition-fast), box-shadow var(--transition-fast);
  }
  .card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
  }
  .card.rounded-lg {
    border-radius: var(--border-radius-lg);
  }
  .card-body {
    padding: 1.25rem 1.5rem;
  }

  /* ======= Stat Cards ======= */
  .stat-card .text-primary {
    color: var(--primary-color) !important;
  }
  .stat-card .text-success {
    color: #28A745 !important;
  }
  .stat-card .text-info {
    color: #17A2B8 !important;
  }
  .stat-card h3 {
    font-size: 1.75rem;
    margin-bottom: 0;
  }
  .stat-card .small {
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .stat-card i {
    opacity: 0.7;
    transition: opacity var(--transition-fast), transform var(--transition-fast);
  }
  .stat-card i:hover {
    opacity: 1;
    transform: scale(1.1);
  }

  /* ======= Dashboard Heading ======= */
  .dashboard-header small {
    font-size: 0.9rem;
    color: var(--secondary-color);
  }
  .dashboard-header h5 {
    margin-bottom: 0.25rem;
  }
  .dashboard-header h4 {
    margin-top: 0.5rem;
  }

  /* ======= Charts Section ======= */
  #chart5, #chart6, #bookingChart {
    width: 100% !important;
    height: 300px;
  }

  /* ======= Tables ======= */
  .table-responsive {
    background-color: transparent;
    border-radius: var(--border-radius-md);
    overflow: hidden;
  }
  .table {
    margin-bottom: 0;
    background-color: var(--bg-card);
  }
  .table thead {
    background-color: var(--primary-light);
  }
  .table th,
  .table td {
    vertical-align: middle;
    padding: 0.75rem 1rem;
    border-top: none;
  }
  .table-hover tbody tr:hover {
    background-color: var(--bg-light-gray);
    transition: background-color var(--transition-fast);
  }
  .table tbody tr {
    background-color: var(--bg-card);
  }

  /* ======= Checkbox Column ======= */
  .table th:first-child, .table td:first-child {
    width: 40px;
  }

  /* ======= Status Badge ======= */
  .status-badge {
    display: inline-block;
    padding: 0.4em 0.75em;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: var(--border-radius-lg);
    transition: background-color var(--transition-fast), color var(--transition-fast);
  }
  .status-active {
    background-color: #D4EDDA;
    color: #155724;
  }
  .status-banned {
    background-color: #F8D7DA;
    color: #721C24;
  }

  /* ======= Buttons Aksi ======= */
  .btn-info, .btn-danger, .btn-success {
    border-radius: var(--border-radius-md);
    transition: transform var(--transition-fast), box-shadow var(--transition-fast);
  }
  .btn-info:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }
  .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }
  .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }
  .btn-secondary {
    border-radius: var(--border-radius-md);
    transition: transform var(--transition-fast), box-shadow var(--transition-fast);
  }
  .btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }

  /* ======= Modal ======= */
  .modal-content {
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-md);
  }
  .modal-header {
    background-color: var(--bg-light-gray);
    border-bottom: none;
  }
  .modal-body {
    padding: 1.5rem;
  }
  .modal-footer {
    border-top: none;
    padding: 1rem 1.5rem;
  }
  /* Tombol di Modal */
  #banButton, #unbanButton {
    border-radius: var(--border-radius-md);
    font-weight: 500;
    transition: transform var(--transition-fast), box-shadow var(--transition-fast);
  }
  #banButton:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }
  #unbanButton:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }

  /* ======= Spinner Centered ======= */
  .spinner-cell {
    height: 150px;
  }
  .spinner-cell .spinner-border {
    width: 3rem;
    height: 3rem;
  }
</style>

<!-- jQuery & Bootstrap & SweetAlert2 (dibutuhkan untuk AJAX & modal) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+xOHOx2K2Xb+6nQvKC+ogU6fQP6MbF5FQ5ZtGMw="
        crossorigin="anonymous"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    // Ambil dan hilangkan flash messages (success / error) dari session
    $successMsg = session()->pull('success');
    $errorMsg   = session()->pull('error');
@endphp

<script>
    @if($successMsg)
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ $successMsg }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if($errorMsg)
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '{{ $errorMsg }}',
            showConfirmButton: false,
            timer: 2500
        });
    @endif
</script>

<!-- ================================
     Konten Utama Dashboard
================================= -->
<div class="container-fluid">
  <!-- Header Section -->
  <div class="row mb-2 dashboard-header">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
      <div>
        <small>Today is {{ date('l, M. d, Y') }}</small><br>
        <h5 class="font-weight-bold mb-0">Selamat Datang, admin!</h5>
        <h4 class="font-weight-bold text-secondary mb-0 mt-2">Dashboard</h4>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row mb-4">
      <!-- Total Pengusaha Card -->
      <div class="col-md-3 mb-3">
        <div class="card stat-card h-100 py-3">
          <div class="card-body d-flex justify-content-between align-items-center px-4">
            <div>
              <h3 class="text-primary font-weight-bold mb-0">{{ $totalPengusaha }}</h3>
              <div class="small font-weight-bold text-primary text-uppercase mb-1">Total Pengusaha</div>
            </div>
            <div>
              <i class="fas fa-briefcase text-primary fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Penyewa Card (BARU) -->
      <div class="col-md-3 mb-3">
        <div class="card stat-card h-100 py-3">
          <div class="card-body d-flex justify-content-between align-items-center px-4">
            <div>
              <h3 class="text-warning font-weight-bold mb-0">{{ $totalPenyewa }}</h3>
              <div class="small font-weight-bold text-warning text-uppercase mb-1">Total Penyewa</div>
            </div>
            <div>
              <i class="fas fa-user-check text-warning fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Property Card -->
      <div class="col-md-3 mb-3">
        <div class="card stat-card h-100 py-3">
          <div class="card-body d-flex justify-content-between align-items-center px-4">
            <div>
              <h3 class="text-success font-weight-bold mb-0">{{ $totalProperty }}</h3>
              <div class="small font-weight-bold text-success text-uppercase mb-1">Total Property</div>
            </div>
            <div>
              <i class="fas fa-home text-success fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Booking Card -->
      <div class="col-md-3 mb-3">
        <div class="card stat-card h-100 py-3">
          <div class="card-body d-flex justify-content-between align-items-center px-4">
            <div>
              <h3 class="text-info font-weight-bold mb-0">{{ $totalBooking }}</h3>
              <div class="small font-weight-bold text-info text-uppercase mb-1">Total Booking</div>
            </div>
            <div>
              <i class="fas fa-calendar-check text-info fa-2x"></i>
            </div>
          </div>
        </div>
      </div>
  </div>



  <!-- Charts Section -->
  <div class="row">
    <!-- Pie Chart Card -->
    <div class="col-12 col-xl-4 mb-3">
      <div class="card rounded-lg">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <h5 class="mb-0">Distribusi Tipe Properti</h5>
            <div class="dropdown">
              <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-home fs-5 text-primary"></i>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;">Homestay</a></li>
                <li><a class="dropdown-item" href="javascript:;">Kost</a></li>
              </ul>
            </div>
          </div>
          <div class="position-relative text-center mb-3">
            <div class="piechart-legend">
              @php
                $totalProp = $propertyTypeStats['homestay'] + $propertyTypeStats['kost'];
                $homestayPercent = $totalProp ? round($propertyTypeStats['homestay'] / $totalProp * 100) : 0;
                $kostPercent = $totalProp ? round($propertyTypeStats['kost'] / $totalProp * 100) : 0;
              @endphp
              <h2 class="mb-1">{{ $homestayPercent + $kostPercent > 0 ? $homestayPercent . '% / ' . $kostPercent . '%' : '0%' }}</h2>
              <h6 class="mb-0">Homestay / Kost</h6>
            </div>
            <div id="chart6"></div>
          </div>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
              <p class="mb-0 d-flex align-items-center gap-2">
                <i class="fas fa-house-user text-primary"></i> Homestay
              </p>
              <p class="mb-0">{{ $propertyTypeStats['homestay'] }} Properti</p>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <p class="mb-0 d-flex align-items-center gap-2">
                <i class="fas fa-bed text-success"></i> Kost
              </p>
              <p class="mb-0">{{ $propertyTypeStats['kost'] }} Properti</p>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Sales & Views Chart Card -->
    <div class="col-12 col-xl-8 mb-3">
      <div class="card rounded-lg">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <h5 class="mb-0">Sales & Views</h5>
            <div class="dropdown">
              <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle" data-bs-toggle="dropdown">
                <span class="material-icons-outlined fs-5">more_vert</span>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
              </ul>
            </div>
          </div>
          <div id="chart5" style="height: 300px;"></div>
          <div class="d-flex flex-column flex-lg-row align-items-start justify-content-around border p-3 rounded-lg mt-3 gap-3">
            <div class="d-flex align-items-center gap-3">
              <p class="mb-0 data-attributes">
                <span data-peity='{ "fill": ["#2196f3", "rgba(255,255,255,0.12)"], "innerRadius": 32, "radius": 40 }'>5/7</span>
              </p>
              <div>
                <p class="mb-1 fs-6 fw-bold">Monthly</p>
                <h2 class="mb-0">65,127</h2>
                <p class="mb-0"><span class="text-success me-2 fw-medium">16.5%</span><span>55.21 USD</span></p>
              </div>
            </div>
            <div class="vr"></div>
            <div class="d-flex align-items-center gap-3">
              <p class="mb-0 data-attributes">
                <span data-peity='{ "fill": ["#ffd200", "rgba(255,255,255,0.12)"], "innerRadius": 32, "radius": 40 }'>5/7</span>
              </p>
              <div>
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

  <div class="row mb-4">
    <div class="col-12 col-lg-6 col-xxl-4 d-flex">
      <div class="card w-100 rounded-4">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="">
              <h5 class="mb-0">Popular Properties</h5>
            </div>
          </div>
          <div class="d-flex flex-column gap-4">
            @foreach($popularProperties as $property)
              <div class="d-flex align-items-center gap-3">
                <img src="{{ asset($property->images_path ?? 'assets/images/top-products/default.png') }}" width="55" class="rounded-circle" alt="">
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ $property->name }}</h6>
                  <p class="mb-0">Booking: {{ $property->total_booking }}</p>
                </div>
              </div>
            @endforeach
            @if(count($popularProperties) == 0)
              <div class="text-center text-muted">Belum ada data</div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-12 col-xxl-4 d-flex">
      <div class="card w-100 rounded-4">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="">
              <h5 class="mb-0">Top Vendors</h5>
            </div>
          </div>
          <div class="d-flex flex-column gap-4">
            @foreach($topVendors as $vendor)
              <div class="d-flex align-items-center gap-3">
                <img src="{{ asset($vendor->photo ?? 'assets/images/avatars/default.png') }}" width="55" class="rounded-circle" alt="">
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ $vendor->name }}</h6>
                  <p class="mb-0">Total Booking: {{ $vendor->total_booking }}</p>
                </div>
                <div class="ratings">
                  @for ($i = 0; $i < 5; $i++)
                    <span class="material-icons-outlined text-warning fs-5">
                      {{ $i < min(5, ceil($vendor->total_booking/10)) ? 'star' : 'star_border' }}
                    </span>
                  @endfor
                </div>
              </div>
            @endforeach
            @if(count($topVendors) == 0)
              <div class="text-center text-muted">Belum ada data</div>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>


  <!-- =================================
       Tabel Booking Terbaru
  ================================== -->
  <div class="card mt-4">
    <div class="card-body">
      <h5 class="mb-3">Pemesanan Terbaru</h5>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
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
              <tr style="border-bottom: 1px solid #e9ecef;">
                <td><input class="form-check-input" type="checkbox" name="selected_bookings[]" value="{{ $booking->id }}"></td>
                <td>{{ $booking->guest_name }}</td>
                <td>{{ $booking->nik }}</td>
                <td>{{ $booking->property_type ?? '-' }}</td>
                <td>
                  {{ \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d') }} 
                  to 
                  {{ \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d') }}
                </td>
                <td class="text-center">
                  <!-- Tombol Detail Booking -->
                  <button 
                    type="button" 
                    class="btn btn-info btn-sm rounded-circle btn-detail-booking"
                    data-booking-id="{{ $booking->id }}"
                    title="Detail Booking"
                  >
                    <i class="bi bi-eye"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- Jika Paginasi diperlukan -->
      <div class="mt-3 d-flex justify-content-center">
        {{-- {{ $bookings->links('pagination::bootstrap-5') }} --}}
      </div>
    </div>
  </div>

  <!-- =========================================
       Chart: Booking Per Hari (7 Hari Terakhir)
  ========================================== -->
  <div class="card mt-4 mb-4 rounded-lg">
    <div class="card-body">
      <h5 class="mb-3">Pemesanan Per Hari (7 Hari Terakhir)</h5>
      <canvas id="bookingChart" style="max-height: 350px;"></canvas>
    </div>
  </div>
</div>

<!-- ================================
     Modal Detail Booking
================================= -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 rounded-lg shadow-md">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold" id="bookingDetailModalLabel">&nbsp;</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" style="font-family: var(--font-base);">
        <!-- Spinner Loading -->
        <div class="d-flex justify-content-center spinner-cell" id="bookingDetailLoading">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Memuat...</span>
          </div>
        </div>

        <!-- Konten Detail Booking akan di‐inject AJAX -->
        <div id="bookingDetailContent" class="d-none">
          {{-- 1. Informasi Utama Booking --}}
          <div class="row mb-4">
            <div class="col-md-6">
              <p><strong>Nama Tamu:</strong> <span id="detailGuestName"></span></p>
              <p><strong>Email:</strong> <span id="detailEmail"></span></p>
              <p><strong>NIK:</strong> <span id="detailNik"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>Check‐In:</strong> <span id="detailCheckIn"></span></p>
              <p><strong>Check‐Out:</strong> <span id="detailCheckOut"></span></p>
              <p><strong>Status:</strong> <span id="detailStatus"></span></p>
            </div>
            <div class="col-12 mt-3">
              <p><strong>Total Harga:</strong> <span id="detailTotalPrice"></span></p>
            </div>
          </div>

          <hr>

          {{-- 2. Rincian Kamar (Booking_Details) --}}
          <div class="mb-4">
            <h5 class="fw-semibold mb-3">Rincian Kamar</h5>
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="text-center">Jenis Kamar</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Harga per Kamar</th>
                    <th class="text-center">Subtotal</th>
                  </tr>
                </thead>
                <tbody id="detailRoomsContainer">
                  <!-- Baris‐baris detail booking di‐inject AJAX -->
                </tbody>
              </table>
            </div>
          </div>

          <hr>

          {{-- 3. Informasi Tambahan (jika ada catatan, dsb.) --}}
          <div class="mb-4">
            <h5 class="fw-semibold mb-3">Catatan</h5>
            <p id="detailNotes">—</p>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ============================
     JavaScript AJAX & Chart.JS
================================ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  $(document).ready(function() {

    /* ===================================
       1) Handler tombol Detail Booking
    =================================== */
    $('.btn-detail-booking').on('click', function() {
      const bookingId = $(this).data('booking-id');

      // Reset modal: kosongkan isi dan munculkan spinner
      $('#bookingDetailModalLabel').text('');
      $('#bookingDetailLoading').removeClass('d-none');
      $('#bookingDetailContent').addClass('d-none');
      $('#bookingDetailModal').modal('show');

      $.ajax({
        url: '/admin/booking/' + bookingId + '/detail-ajax',
        method: 'GET',
        success: function(response) {
          // Set judul (misal: "Detail Booking #123")
          $('#bookingDetailModalLabel').text('Detail Booking #' + response.booking.id);

          // Isi informasi utama
          $('#detailGuestName').text(response.booking.guest_name);
          $('#detailEmail').text(response.booking.email);
          $('#detailNik').text(response.booking.nik);
          $('#detailCheckIn').text(new Date(response.booking.check_in).toLocaleDateString('id-ID'));
          $('#detailCheckOut').text(new Date(response.booking.check_out).toLocaleDateString('id-ID'));
          $('#detailStatus').text(response.booking.status);
          $('#detailTotalPrice').text('Rp ' + new Intl.NumberFormat('id-ID').format(response.booking.total_price));

          // Jika ada catatan, tampilkan; kalau tidak, "-".
          $('#detailNotes').text(response.booking.notes || '-');

          // Isi rincian kamar (booking_details)
          let roomsHtml = '';
          if (response.details.length) {
            response.details.forEach(d => {
              roomsHtml += `
                <tr>
                  <td class="text-center">${d.room_type_name}</td>
                  <td class="text-center">${d.quantity}</td>
                  <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(d.price_per_room)}</td>
                  <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(d.subtotal)}</td>
                </tr>
              `;
            });
          } else {
            roomsHtml = `<tr>
                           <td colspan="4" class="text-center text-secondary">
                             Tidak ada rincian kamar.
                           </td>
                         </tr>`;
          }
          $('#detailRoomsContainer').html(roomsHtml);

          // Sembunyikan spinner, tampilkan konten
          $('#bookingDetailLoading').addClass('d-none');
          $('#bookingDetailContent').removeClass('d-none');
        },
        error: function() {
          Swal.fire('Error!', 'Gagal memuat data detail booking.', 'error');
          $('#bookingDetailModal').modal('hide');
        }
      });
    });

    /* ======================
       2) Chart: Booking per Hari
    ====================== */
    const ctx = document.getElementById('bookingChart').getContext('2d');
    const bookingChart = new Chart(ctx, {
      type: 'bar',
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
            ticks: {
              stepSize: 1
            }
          }
        },
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          },
          tooltip: {
            enabled: true
          }
        }
      }
    });

    /* ======================
       3) Fungsi konfirmasi delete
    ====================== */
    // (Jika nanti Anda menambahkan tombol Delete khusus di tabel booking)
    window.confirmDelete = function(bookingId) {
      Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data booking ini akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        customClass: {
          confirmButton: "swal-confirm",
          cancelButton: "swal-cancel"
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Jika ada form dengan id delete-form-{bookingId}, kirim form tersebut
          document.getElementById('delete-form-' + bookingId).submit();
        }
      });
    };
  });
</script>

@endsection
