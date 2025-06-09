@extends('layouts.admin.index-admin')
@section('content')

<!-- Bootstrap Icons & SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css"> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<!-- ===========================
     Custom CSS Modern & Halus
  =========================== -->
<style>
  :root {
    --primary-color: #289A84;        /* Hijau utama */
    --primary-light: #E6F7F1;        /* Hijau terang sebagai background ringan */
    --secondary-color: #6C757D;      /* Abu-abu sekunder */
    --bg-light-gray: #F8F9FA;        /* Latar yang sangat terang untuk keseluruhan body */
    --border-radius-lg: 0.75rem;
    --border-radius-md: 0.5rem;
    --shadow-sm: 0 2px 6px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
    --transition-fast: 0.2s ease-in-out;
    --transition-med: 0.4s ease-in-out;
    --font-base: 'Poppins', sans-serif;
  }

  /* ======= Breadcrumb Modern ======= */
  .breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 1.5rem;
  }
  .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: var(--secondary-color);
    padding: 0 0.5rem;
  }
  .breadcrumb-item a {
    color: var(--secondary-color);
    font-weight: 500;
    transition: color var(--transition-fast);
  }
  .breadcrumb-item a:hover {
    color: var(--primary-color);
  }
  .breadcrumb-item.active {
    color: var(--primary-color);
    font-weight: 600;
  }

  /* ======= Judul Halaman ======= */
  h4 {
    font-weight: 600;
    margin-bottom: 1rem;
    color: #343A40;
  }

  /* ======= Form Pencarian ======= */
  .input-group {
    box-shadow: var(--shadow-sm);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
  }
  .input-group .input-group-text {
    background-color: #ffffff;
    border: none;
    padding: 0.5rem 0.75rem;
  }
  .input-group .form-control {
    border: none;
    padding: 0.5rem 0.75rem;
    background-color: #ffffff;
  }
  .input-group .form-control:focus {
    box-shadow: none;
    outline: none;
  }

  /* ======= Kartu Tabel ======= */
  .card {
    border: none;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    background-color: #ffffff;
  }
  .card-body {
    padding: 1rem 1.5rem;
  }
  .table-responsive {
    border-radius: var(--border-radius-md);
    overflow: hidden;
  }
  .table {
    margin-bottom: 0;
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
  .table th:first-child,
  .table td:first-child {
    width: 40px;
  }
  .table th:nth-child(2),
  .table td:nth-child(2) {
    width: 60px;
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

  /* ======= Pagination Center ======= */
  .pagination {
    justify-content: center;
  }

  /* ======= Tombol Aksi pada Tabel ======= */
  .btn-outline-primary {
    border-radius: var(--border-radius-md);
    transition: background-color var(--transition-fast), transform var(--transition-fast);
  }
  .btn-outline-primary:hover {
    background-color: var(--primary-color);
    color: #fff;
    transform: translateY(-1px);
  }

  /* ======= Modal Modern ======= */
  .modal-content {
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-md);
  }
  .modal-header {
    background-color: var(--bg-light-gray);
    border-bottom: none;
  }
  .modal-title {
    font-weight: 600;
  }
  .modal-body {
    padding: 1.5rem;
  }
  .modal-footer {
    border-top: none;
    padding: 1rem 1.5rem;
  }
  /* Tombol di dalam modal */
  #banButton, #unbanButton {
    border-radius: var(--border-radius-md);
    font-weight: 500;
    transition: background-color var(--transition-fast), transform var(--transition-fast);
  }
  #banButton {
    background-color: #DC3545;
    border-color: #DC3545;
  }
  #banButton:hover {
    background-color: #B02A37;
    transform: translateY(-1px);
  }
  #unbanButton {
    background-color: #28A745;
    border-color: #28A745;
  }
  #unbanButton:hover {
    background-color: #218838;
    transform: translateY(-1px);
  }

  /* ======= Spinner Centered Cell ======= */
  .spinner-cell {
    height: 150px;
  }
  .spinner-cell .spinner-border {
    width: 3rem;
    height: 3rem;
  }
</style>

<div class="container">
  <!-- Breadcrumb -->
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Pengguna</a></li>
    <li class="breadcrumb-item active" aria-current="page">Penyewa</li>
  </ol>

  <!-- Judul Halaman -->
  <h4 class="mb-4">Daftar Penyewa (Customer)</h4>

  <!-- Form Pencarian -->
  <form action="{{ route('users.rolePenyewa') }}" method="GET" class="mb-3" style="max-width: 350px;">
    <div class="input-group">
      <span class="input-group-text">
        <i class="bi bi-search"></i>
      </span>
      <input
        name="search"
        type="text"
        class="form-control"
        placeholder="Cari nama atau email..."
        value="{{ request('search') }}"
        autocomplete="off"
      >
    </div>
  </form>

  <!-- Kartu Tabel Penyewa -->
  <div class="card mt-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th><input class="form-check-input" type="checkbox"></th>
              <th>ID</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Status Akun</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <td><input class="form-check-input" type="checkbox"></td>
                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                <td class="user-name">{{ $user->name ?? $user->username ?? '-' }}</td>
                <td class="user-email">{{ $user->email ?? '-' }}</td>
                <td>
                  <span class="status-badge {{ $user->is_banned == 0 ? 'status-active' : 'status-banned' }}">
                    {{ $user->is_banned == 0 ? 'Aktif' : 'Diblokir' }}
                  </span>
                </td>
                <td>
                  <button 
                    type="button"
                    class="btn btn-info btn-sm rounded-circle"
                    data-bs-toggle="modal"
                    data-bs-target="#detailModal"
                    data-user-id="{{ $user->id }}"
                    title="Detail"
                  >
                    <i class="bi bi-eye"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4">Data tidak ditemukan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-4">
        {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Penyewa -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detail Penyewa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <!-- Spinner Loading -->
        <div id="loading" class="text-center p-4">
          <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Memuat...</span>
          </div>
        </div>

        <!-- Konten Detail -->
        <div id="userDetail" class="d-none">
          <div class="row mb-3">
            <div class="col-md-3 fw-semibold">Nama:</div>
            <div class="col-md-9" id="userName"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-semibold">Email:</div>
            <div class="col-md-9" id="userEmail"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-semibold">Tanggal Daftar:</div>
            <div class="col-md-9" id="userCreatedAt"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-semibold">Status:</div>
            <div class="col-md-9">
              <span id="userStatus" class="status-badge"></span>
            </div>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-4 d-flex gap-3" id="actionButtons">
          <button id="banButton" class="btn btn-danger d-none">
            <i class="bi bi-person-slash me-1"></i> Blokir Penyewa
          </button>
          <button id="unbanButton" class="btn btn-success d-none">
            <i class="bi bi-person-check me-1"></i> Buka Blokir
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- ===========================
     SCRIPT UTAMA (jQuery + AJAX)
  =========================== -->
<script>
$(document).ready(function() {
  // --------------------------
  // Detail modal handler
  // --------------------------
  $('#detailModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const userId = button.data('user-id');
    const modal = $(this);

    // Reset tampilan
    modal.find('#userDetail').addClass('d-none');
    modal.find('#loading').removeClass('d-none');
    modal.find('#actionButtons button').addClass('d-none');

    // Ambil detail via AJAX
    $.ajax({
      url: '{{ route("admin.penyewa.detail") }}',
      type: 'GET',
      data: { user_id: userId },
      success: function(response) {
        if (response.success) {
          const data = response.data;

          // Isi data di modal
          modal.find('#userName').text(data.name);
          modal.find('#userEmail').text(data.email);
          modal.find('#userCreatedAt').text(new Date(data.created_at).toLocaleDateString());

          // Update status badge
          let statusBadge = modal.find('#userStatus');
          statusBadge.text(data.is_banned ? 'Diblokir' : 'Aktif');
          statusBadge.removeClass('status-active status-banned')
                     .addClass(data.is_banned ? 'status-banned' : 'status-active');

          // Tampilkan tombol sesuai kondisi
          if (data.is_banned) {
            modal.find('#banButton').addClass('d-none');
            modal.find('#unbanButton').removeClass('d-none');
          } else {
            modal.find('#banButton').removeClass('d-none');
            modal.find('#unbanButton').addClass('d-none');
          }
          modal.find('#banButton').data('user-id', userId);
          modal.find('#unbanButton').data('user-id', userId);

          // Tampilkan konten, sembunyikan spinner
          modal.find('#loading').addClass('d-none');
          modal.find('#userDetail').removeClass('d-none');
        } else {
          Swal.fire('Error!', response.message, 'error');
          modal.modal('hide');
        }
      },
      error: function() {
        Swal.fire('Error!', 'Gagal memuat detail pengguna', 'error');
      }
    });
  });

  // --------------------------
  // Tombol Blokir Penyewa
  // --------------------------
  $('#banButton').on('click', function() {
    const userId = $(this).data('user-id');
    const button = $(this);

    Swal.fire({
      title: 'Konfirmasi Blokir',
      text: "Anda yakin ingin memblokir akun ini?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Blokir!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '{{ route("admin.ban.akun") }}',
          type: 'POST',
          data: {
            user_id: userId,
            _token: '{{ csrf_token() }}'
          },
          beforeSend: () => {
            button.html(`
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Memproses...
            `).prop('disabled', true);
          },
          success: function(response) {
            if (response.success) {
              Swal.fire('Diblokir!', response.message, 'success')
                .then(() => location.reload());
            } else {
              Swal.fire('Gagal!', response.message, 'error');
              button.html(`
                <i class="bi bi-person-slash me-1"></i> Blokir Penyewa
              `).prop('disabled', false);
            }
          },
          error: function() {
            Swal.fire('Error!', 'Gagal memblokir penyewa', 'error');
            button.html(`
              <i class="bi bi-person-slash me-1"></i> Blokir Penyewa
            `).prop('disabled', false);
          }
        });
      }
    });
  });

  // --------------------------
  // Tombol Buka Blokir Penyewa
  // --------------------------
  $('#unbanButton').on('click', function() {
    const userId = $(this).data('user-id');
    const button = $(this);

    Swal.fire({
      title: 'Konfirmasi Buka Blokir',
      text: "Anda yakin ingin membuka blokir akun ini?",
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Buka Blokir!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '{{ route("admin.unban.akun") }}',
          type: 'POST',
          data: {
            user_id: userId,
            _token: '{{ csrf_token() }}'
          },
          beforeSend: () => {
            button.html(`
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Memproses...
            `).prop('disabled', true);
          },
          success: function(response) {
            if (response.success) {
              Swal.fire('Sukses!', response.message, 'success')
                .then(() => location.reload());
            } else {
              Swal.fire('Gagal!', response.message, 'error');
              button.html(`
                <i class="bi bi-person-check me-1"></i> Buka Blokir
              `).prop('disabled', false);
            }
          },
          error: function() {
            Swal.fire('Error!', 'Gagal membuka blokir penyewa', 'error');
            button.html(`
              <i class="bi bi-person-check me-1"></i> Buka Blokir
            `).prop('disabled', false);
          }
        });
      }
    });
  });
});
</script>

@endsection
