@extends('layouts.admin.index-admin')

@section('content')
<!-- ================================
     Inklusi Library: jQuery, Bootstrap, dll.
================================ -->
<!-- jQuery (harus diletakkan sebelum skrip AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+xOHOx2K2Xb+6nQvKC+ogU6fQP6MbF5FQ5ZtGMw="
        crossorigin="anonymous"></script>

<!-- Bootstrap 5 JS (bundled dengan Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Google Fonts: Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">

@php
    // Ambil flash message (jika ada) dan sekaligus menghapusnya dari session
    $successMsg = session()->pull('success');
    $errorMsg   = session()->pull('error');
@endphp

<script>
    // Tampilkan notifikasi SweetAlert jika ada success/error
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
     Konten Utama
================================ -->
<div class="container mt-4">
    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <!-- Form Pencarian Properti -->
            <form method="GET" action="{{ url()->current() }}">
                <div class="position-relative">
                    <input
                        class="form-control px-5 rounded-pill"
                        type="search"
                        name="search"
                        placeholder="Cari Properti"
                        value="{{ $search ?? '' }}"
                    >
                    <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">
                        search
                    </span>
                </div>
            </form>
        </div>
        <div class="col-auto flex-grow-1"></div>
        <div class="col-auto">
            <a href="{{ url('admin/homestay/create') }}" class="btn btn-primary px-4 rounded-pill">
                <i class="bi bi-plus-lg me-2"></i> Tambah Properti
            </a>
        </div>
    </div>

    <!-- Tabel Daftar Properti -->
    <div class="card mt-4 shadow-sm rounded-4">
        <div class="card-body p-3">
            <div class="table-responsive white-space-nowrap">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><input class="form-check-input" type="checkbox"></th>
                            <th>No.</th>
                            <th>Nama Properti</th>
                            <th>Kota</th>
                            <th>Harga Termurah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($properties as $idx => $property)
                        <tr>
                            <td><input class="form-check-input" type="checkbox"></td>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $property->name }}</td>
                            <td>{{ $property->city ?? 'Kota tidak ditemukan' }}</td>
                            <td>Rp {{ number_format($property->min_price, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Tombol Detail Properti -->
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-info rounded-circle btn-detail"
                                        data-property-id="{{ $property->id }}"
                                        title="Detail"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <!-- Tombol Booking: Munculkan Modal Booking -->
                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm rounded-circle btn-bookings"
                                        data-property-id="{{ $property->id }}"
                                        title="Booking"
                                    >
                                        <i class="bi bi-calendar-check"></i>
                                    </button>

                                    <!-- Tombol Delete Properti -->
                                    <form
                                        id="delete-form-{{ $property->id }}"
                                        action="{{ url('admin/homestay/' . $property->id) }}"
                                        method="POST"
                                        style="display:inline;"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm rounded-circle"
                                            onclick="confirmDelete({{ $property->id }})"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Tidak ada properti ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $properties->links() }} 
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================================
     Modal Detail Properti
================================ -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold" id="detailModalLabel">&nbsp;</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body p-4" style="font-family: 'Inter', sans-serif;">
        <!-- Spinner Loading -->
        <div class="d-flex justify-content-center py-5" id="detailLoading">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Memuat...</span>
          </div>
        </div>

        <!-- Konten Detail akan di‐inject via AJAX -->
        <div id="detailContent" class="d-none">
          {{-- 1. Informasi Utama --}}
          <div class="row mb-4">
            <div class="col-md-8">
              <p class="text-secondary mb-1">
                <i class="bi bi-geo-alt-fill me-1"></i>
                <span id="locationText"></span>
              </p>
              <div class="mb-2">
                <span class="badge bg-warning text-dark">
                  <span id="avgRatingText"></span> <i class="bi bi-star-fill"></i>
                  (<span id="totalReviewsText"></span> ulasan)
                </span>
              </div>
              <p id="descriptionText"></p>
            </div>
            <div class="col-md-4 text-end">
              <p class="text-secondary mb-1">Harga Termurah</p>
              <h4 class="text-danger fw-bold mb-1" id="minPriceText"></h4>
              <small class="text-secondary">/kamar/malam</small>
            </div>
          </div>

          <hr>

          {{-- 2. Galeri Foto --}}
          <div class="mb-4">
            <h5 class="fw-semibold mb-3">Galeri Foto</h5>
            <div class="row g-2" id="galleryContainer">
              <!-- Gambar di‐inject AJAX -->
            </div>
          </div>

          <hr>

          {{-- 3. Daftar Kamar --}}
          <div class="mb-4">
            <h5 class="fw-semibold mb-3">Daftar Kamar</h5>
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="text-center">Jenis Kamar</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Stok</th>
                  </tr>
                </thead>
                <tbody id="roomsContainer">
                  <!-- Baris‐baris di‐inject AJAX -->
                </tbody>
              </table>
            </div>
          </div>

          <hr>

          {{-- 4. Fasilitas --}}
          <div class="mb-4">
            <h5 class="fw-semibold mb-3">Fasilitas</h5>
            <div class="row g-2" id="facilitiesContainer">
              <!-- Item fasilitas di‐inject AJAX -->
            </div>
          </div>

          <hr>

          {{-- 5. Ulasan --}}
          <div class="mb-4">
            <h5 class="fw-semibold mb-3">Ulasan Pengguna</h5>
            <div class="row" id="reviewsContainer">
              <!-- Ulasan di‐inject AJAX -->
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top-0 px-4 pb-3 pt-0">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ================================
     Modal Booking Properti
================================ -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold" id="bookingModalLabel">&nbsp;</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body p-4" style="font-family: 'Inter', sans-serif;">
        <!-- Spinner Loading Booking -->
        <div class="d-flex justify-content-center py-5" id="bookingLoading">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Memuat...</span>
          </div>
        </div>

        <!-- Konten Booking akan di‐inject AJAX -->
        <div id="bookingContent" class="d-none">
          <h5 class="fw-semibold mb-3">Daftar Booking untuk Properti Ini</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>No.</th>
                  <th>Nama Tamu</th>
                  <th>Check‐In</th>
                  <th>Check‐Out</th>
                  <th>Total Harga</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="bookingsContainer">
                <!-- Baris‐baris booking di‐inject AJAX -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top-0 px-4 pb-3 pt-0">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ================================
     Skrip AJAX untuk Detail & Booking
================================ -->
<script>
  $(document).ready(function() {
    // ============================
    // 1) Handler untuk tombol Detail
    // ============================
    $('.btn-detail').on('click', function() {
      const propertyId = $(this).data('property-id');

      // Reset modal
      $('#detailModalLabel').text('');
      $('#detailLoading').removeClass('d-none');
      $('#detailContent').addClass('d-none');
      $('#detailModal').modal('show');

      $.ajax({
        url: '/admin/homestay/' + propertyId + '/detail-ajax',
        method: 'GET',
        success: function(response) {
          // Set judul nama properti
          $('#detailModalLabel').text(response.property.property_name);

          // Informasi utama
          $('#locationText').text(
            response.locationData.subdis_name + ', ' + response.locationData.city_name
          );
          $('#avgRatingText').text(parseFloat(response.avgRating).toFixed(1));
          $('#totalReviewsText').text(response.totalReviews);
          $('#descriptionText').text(response.property.description);
          $('#minPriceText').text(
            'Rp ' + new Intl.NumberFormat('id-ID').format(response.property_roomPrice[0].min_price)
          );

          // Galeri Foto
          let galleryHtml = '';
          if (response.images.length) {
            response.images.forEach(img => {
              galleryHtml += `
                <div class="col-6 col-md-3">
                  <div class="card border-0" style="border-radius: 0.75rem; overflow:hidden;">
                    <img src="/storage/${img.images_path}"
                         class="img-fluid object-fit-cover"
                         style="height: 180px; width: 100%; transition: transform 0.3s;"
                         onmouseover="this.style.transform='scale(1.05)';"
                         onmouseleave="this.style.transform='scale(1)';"
                         alt="Foto Properti"
                    >
                  </div>
                </div>
              `;
            });
          } else {
            galleryHtml = `<div class="col-12 text-center text-secondary">
                             Tidak ada gambar tersedia.
                           </div>`;
          }
          $('#galleryContainer').html(galleryHtml);

          // Daftar Kamar
          let roomsHtml = '';
          if (response.rooms.length) {
            response.rooms.forEach(room => {
              roomsHtml += `
                <tr>
                  <td class="text-center">${room.room_type}</td>
                  <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(room.latest_price)}</td>
                  <td class="text-center">${room.available_room}</td>
                </tr>
              `;
            });
          } else {
            roomsHtml = `<tr>
                           <td colspan="3" class="text-center text-secondary">
                             Tidak ada kamar tersedia.
                           </td>
                         </tr>`;
          }
          $('#roomsContainer').html(roomsHtml);

          // Fasilitas
          let facilitiesHtml = '';
          if (response.fasilitas.length) {
            response.fasilitas.forEach(f => {
              facilitiesHtml += `
                <div class="col-6 col-sm-4 col-md-3">
                  <div class="d-flex align-items-center gap-2 p-2"
                       style="background-color: #FAFAFA; border-radius: 0.5rem;">
                    <i class="bi ${f.icon} fs-4 text-primary"></i>
                    <span>${f.facility_name}</span>
                  </div>
                </div>
              `;
            });
          } else {
            facilitiesHtml = `<div class="col-12 text-secondary">
                                Belum ada fasilitas.
                              </div>`;
          }
          $('#facilitiesContainer').html(facilitiesHtml);

          // Ulasan
          let reviewsHtml = '';
          if (response.reviews.length) {
            response.reviews.forEach(r => {
              let starsHtml = '';
              const fullStars = Math.floor(r.rating);
              const halfStar = (r.rating - fullStars) >= 0.5;
              for (let i = 0; i < fullStars; i++) {
                starsHtml += `<i class="bi bi-star-fill text-warning"></i>`;
              }
              if (halfStar) {
                starsHtml += `<i class="bi bi-star-half text-warning"></i>`;
              }
              for (let i = fullStars + (halfStar ? 1 : 0); i < 5; i++) {
                starsHtml += `<i class="bi bi-star text-warning"></i>`;
              }

              reviewsHtml += `
                <div class="col-md-6 mb-3">
                  <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                          <strong>${r.user_name}</strong><br>
                          <small class="text-secondary">
                            ${new Date(r.created_at).toLocaleDateString('id-ID', {
                              day: '2-digit',
                              month: 'short',
                              year: 'numeric',
                              hour: '2-digit',
                              minute: '2-digit'
                            })}
                          </small>
                        </div>
                        <div>${starsHtml}</div>
                      </div>
                      <p class="mb-0">${r.comment}</p>
                    </div>
                  </div>
                </div>
              `;
            });
          } else {
            reviewsHtml = `<div class="col-12 text-secondary">
                             Belum ada ulasan untuk properti ini.
                           </div>`;
          }
          $('#reviewsContainer').html(reviewsHtml);

          // Sembunyikan spinner, tampilkan konten
          $('#detailLoading').addClass('d-none');
          $('#detailContent').removeClass('d-none');
        },
        error: function() {
          Swal.fire('Error!', 'Gagal memuat data detail.', 'error');
          $('#detailModal').modal('hide');
        }
      });
    });

    // ============================
    // 2) Handler untuk tombol Booking
    // ============================
    $('.btn-bookings').on('click', function() {
      const propertyId = $(this).data('property-id');

      // Reset modal
      $('#bookingModalLabel').text('');
      $('#bookingLoading').removeClass('d-none');
      $('#bookingContent').addClass('d-none');
      $('#bookingModal').modal('show');

      $.ajax({
        url: '/admin/homestay/' + propertyId + '/bookings-ajax',
        method: 'GET',
        success: function(response) {
          // Set judul nama properti
          $('#bookingModalLabel').text('Booking untuk: ' + response.property_name);

          // Render daftar booking
          let bookingsHtml = '';
          if (response.bookings.length) {
            response.bookings.forEach((b, index) => {
              bookingsHtml += `
                <tr>
                  <td>${index + 1}</td>
                  <td>${b.guest_name}</td>
                  <td>${new Date(b.check_in).toLocaleDateString('id-ID')}</td>
                  <td>${new Date(b.check_out).toLocaleDateString('id-ID')}</td>
                  <td>Rp ${new Intl.NumberFormat('id-ID').format(b.total_price)}</td>
                  <td>${b.status}</td>
                </tr>
              `;
            });
          } else {
            bookingsHtml = `<tr>
                              <td colspan="6" class="text-center text-secondary py-3">
                                Belum ada booking untuk properti ini.
                              </td>
                            </tr>`;
          }
          $('#bookingsContainer').html(bookingsHtml);

          // Tampilkan konten booking dan sembunyikan spinner
          $('#bookingLoading').addClass('d-none');
          $('#bookingContent').removeClass('d-none');
        },
        error: function() {
          Swal.fire('Error!', 'Gagal memuat data booking.', 'error');
          $('#bookingModal').modal('hide');
        }
      });
    });
  });

  // Fungsi konfirmasi delete
  function confirmDelete(propertyId) {
    Swal.fire({
      title: "Yakin ingin menghapus?",
      text: "Data ini akan dihapus secara permanen!",
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
        document.getElementById('delete-form-' + propertyId).submit();
      }
    });
  }
</script>

<!-- ================================
     Style (Custom CSS)
================================ -->
<style>
  .swal-confirm, .swal-cancel {
      border-radius: 12px !important;
      padding: 8px 16px;
  }

  .form-control {
      border-radius: 25px;
  }

  .btn-primary {
      background-color: #007bff;
      border-radius: 30px;
  }

  .btn-danger {
      background-color: #e74a3b;
  }

  .btn-success {
      background-color: #1cc88a;
  }

  .table thead th {
      background-color: #f8f9fa;
      color: #495057;
  }

  .table tbody tr {
      background-color: white;
      border-radius: 0.5rem;
      overflow: hidden;
      transition: transform 0.2s, box-shadow 0.2s;
  }

  .table tbody tr:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .table td {
      padding: 1.25rem;
  }

  /* Root variables untuk modal */
  :root {
    --border-radius-lg: 1rem;
    --shadow-lg: 0 8px 24px rgba(0,0,0,0.1);
    --transition-fast: 0.2s ease-in-out;
    --bg-light-gray: #F8F9FA;
    --card-bg: #FFFFFF;
    --font-base: 'Inter', sans-serif;
  }

  /* ===== Detail Modal Styling ===== */
  #detailModal .modal-content {
    background-color: var(--card-bg);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transition: transform var(--transition-fast);
  }
  #detailModal .modal-content:hover {
    transform: translateY(-2px);
  }
  #detailModal .modal-header {
    background-color: var(--bg-light-gray);
    border-bottom: none;
  }
  #detailModal .modal-title {
    font-family: var(--font-base);
    font-size: 1.25rem;
    font-weight: 600;
  }
  #detailModal .btn-close {
    filter: grayscale(50%);
    transition: filter var(--transition-fast);
  }
  #detailModal .btn-close:hover {
    filter: none;
  }
  #detailModal .modal-body {
    padding: 1.5rem 2rem;
    font-family: var(--font-base);
  }
  #detailLoading {
    min-height: 200px;
  }
  #detailModal .modal-footer {
    background-color: var(--bg-light-gray);
    border-top: none;
    padding-top: 0;
  }
  #detailModal .modal-footer .btn-secondary {
    border-radius: 50px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: background-color var(--transition-fast), transform var(--transition-fast);
  }
  #detailModal .modal-footer .btn-secondary:hover {
    background-color: #6c757d;
    transform: translateY(-1px);
  }

  /* ===== Booking Modal Styling ===== */
  #bookingModal .modal-content {
    background-color: var(--card-bg);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transition: transform var(--transition-fast);
  }
  #bookingModal .modal-content:hover {
    transform: translateY(-2px);
  }
  #bookingModal .modal-header {
    background-color: var(--bg-light-gray);
    border-bottom: none;
  }
  #bookingModal .modal-title {
    font-family: var(--font-base);
    font-size: 1.25rem;
    font-weight: 600;
  }
  #bookingModal .btn-close {
    filter: grayscale(50%);
    transition: filter var(--transition-fast);
  }
  #bookingModal .btn-close:hover {
    filter: none;
  }
  #bookingModal .modal-body {
    padding: 1.5rem 2rem;
    font-family: var(--font-base);
  }
  #bookingLoading {
    min-height: 200px;
  }
  #bookingModal .modal-footer {
    background-color: var(--bg-light-gray);
    border-top: none;
    padding-top: 0;
  }
  #bookingModal .modal-footer .btn-secondary {
    border-radius: 50px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: background-color var(--transition-fast), transform var(--transition-fast);
  }
  #bookingModal .modal-footer .btn-secondary:hover {
    background-color: #6c757d;
    transform: translateY(-1px);
  }
</style>
@endsection
