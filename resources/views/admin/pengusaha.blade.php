@extends('layouts.admin.index-admin')
@section('content')

<!-- Bootstrap Icons & SweetAlert2 (sudah Anda sertakan) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css"> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<!-- ===========================
     Custom CSS Modern & Halus
  =========================== -->
<style>
  :root {
    --primary-color: #289A84;       /* Hijau utama */
    --primary-light: #E6F7F1;       /* Hijau terang */
    --secondary-color: #6C757D;     /* Abu-abu sekunder */
    --bg-light-gray: #F8F9FA;       /* Latar yang sangat terang */
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

  /* ======= Judul dan Tombol ======= */
  h4 {
    font-weight: 600;
    margin-bottom: 1rem;
    color: #343A40;
  }
  .btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    border-radius: var(--border-radius-md);
    font-weight: 500;
    transition: background-color var(--transition-fast), transform var(--transition-fast);
  }
  .btn-primary:hover {
    background-color: #1F7A68;
    transform: translateY(-2px);
  }
  .btn-primary i {
    margin-right: 0.4rem;
  }

  /* ======= Search Bar ======= */
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

  /* ======= Tabel Modern ======= */
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
    background-color: var(--bg-light-gray);
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

  /* ======= Tombol Modal ======= */
  #unconfirmedModal .btn-secondary,
  #detailModal .btn-secondary {
    background-color: #6C757D;
    border-color: #6C757D;
    border-radius: var(--border-radius-md);
    transition: background-color var(--transition-fast), transform var(--transition-fast);
  }
  #unconfirmedModal .btn-secondary:hover,
  #detailModal .btn-secondary:hover {
    background-color: #565E64;
    transform: translateY(-1px);
  }
  /* Tombol aksi di detailModal */
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
    <li class="breadcrumb-item active" aria-current="page">Pengusaha</li>
  </ol>
  
  <!-- Judul dan Tombol Konfirmasi -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Pengusaha (Owner)</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#unconfirmedModal" style="border-radius: 20px ;">
      <i class="bi bi-person-check"></i> Konfirmasi Akun Baru
    </button>
  </div>

  <!-- Form Pencarian -->
  <form action="{{ route('users.rolePengusaha') }}" method="GET" class="mb-4" style="max-width: 350px;">
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

  <!-- Modal: Unconfirmed Accounts -->
  <div class="modal fade" id="unconfirmedModal" tabindex="-1" aria-labelledby="unconfirmedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="unconfirmedModalLabel">Akun Pengusaha yang Perlu Dikonfirmasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <!-- Pencarian di dalam modal -->
          <div class="mb-3" style="max-width: 300px;">
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-search"></i>
              </span>
              <input id="searchUnconfirmed" type="text" class="form-control" placeholder="Cari nama atau email...">
            </div>
          </div>
          <!-- Table Unconfirmed -->
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Tanggal Daftar</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="unconfirmedTableBody">
                <tr>
                  <td colspan="5" class="text-center spinner-cell">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Memuat data...</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Kartu Tabel Utama -->
  <div class="card">
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
                  <button type="button" 
                          class="btn btn-info btn-sm rounded-circle" 
                          data-bs-toggle="modal" 
                          data-bs-target="#detailModal" 
                          data-user-id="{{ $user->id }}" 
                          title="Detail"
                          style="transition: background-color var(--transition-fast), transform var(--transition-fast);">
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

<!-- Modal: Detail Pengguna -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detail Pengusaha</h5>
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
            <i class="bi bi-person-slash me-1"></i> Blokir Pengusaha
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
  // Tampilkan pesan dari session (SweetAlert2)
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Sukses!',
      text: '{{ session('success') }}',
      timer: 2500,
      showConfirmButton: false
    });
  @endif
  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '{{ session('error') }}',
      timer: 2500,
      showConfirmButton: false
    });
  @endif

  // -----------------------------
  // 1) Load Unconfirmed Accounts
  // -----------------------------
  let unconfirmedData = [];
  $('#unconfirmedModal').on('shown.bs.modal', function () {
    loadUnconfirmedAccounts();
  });

  function loadUnconfirmedAccounts() {
    $.ajax({
      url: '{{ route("admin.pengusaha.unconfirmed") }}',
      type: 'GET',
      success: function(response) {
        if (response.success) {
          unconfirmedData = response.data;
          renderUnconfirmedTable(unconfirmedData);
        } else {
          Swal.fire('Error', response.message, 'error');
        }
      },
      error: function(xhr) {
        let errorMsg = xhr.responseJSON?.message || 'Gagal memuat data';
        Swal.fire('Error', errorMsg, 'error');
      }
    });
  }

  function renderUnconfirmedTable(data) {
    let tableBody = $('#unconfirmedTableBody');
    tableBody.empty();

    if (data.length === 0) {
      tableBody.append(`
        <tr>
          <td colspan="5" class="text-center py-4">Tidak ada akun yang perlu dikonfirmasi</td>
        </tr>
      `);
      return;
    }

    data.forEach(function(user) {
      let row = `
        <tr>
          <td>${user.id}</td>
          <td>${user.name || user.username || '-'}</td>
          <td>${user.email || '-'}</td>
          <td>${new Date(user.created_at).toLocaleDateString()}</td>
          <td>
            <button class="btn btn-success btn-sm confirm-btn" data-user-id="${user.id}">
              <i class="bi bi-check-circle me-1"></i> Konfirmasi
            </button>
          </td>
        </tr>
      `;
      tableBody.append(row);
    });

    // Attach click handler
    $('.confirm-btn').off('click').on('click', function() {
      let userId = $(this).data('user-id');
      confirmAccount(userId, $(this));
    });
  }

  function confirmAccount(userId, button) {
    $.ajax({
      url: '{{ route("admin.pengusaha.confirm") }}',
      type: 'POST',
      data: {
        user_id: userId,
        _token: '{{ csrf_token() }}'
      },
      beforeSend: function() {
        button.prop('disabled', true).html(`
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
          Memproses...
        `);
      },
      success: function(response) {
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Akun berhasil dikonfirmasi',
            timer: 1800,
            showConfirmButton: false
          }).then(() => {
            // Hapus baris dari tabel
            button.closest('tr').fadeOut(300, function() {
              $(this).remove();
              unconfirmedData = unconfirmedData.filter(u => u.id !== userId);
              renderUnconfirmedTable(unconfirmedData);
            });
          });
        } else {
          Swal.fire('Gagal!', 'Gagal mengkonfirmasi akun', 'error');
          button.prop('disabled', false).html(`
            <i class="bi bi-check-circle me-1"></i> Konfirmasi
          `);
        }
      },
      error: function() {
        Swal.fire('Error!', 'Terjadi kesalahan saat mengkonfirmasi akun', 'error');
        button.prop('disabled', false).html(`
          <i class="bi bi-check-circle me-1"></i> Konfirmasi
        `);
      }
    });
  }

  // --------------------------
  // 2) Search di Unconfirmed Modal
  // --------------------------
  $('#searchUnconfirmed').on('keyup', function() {
    let searchTerm = $(this).val().toLowerCase();
    let filtered = unconfirmedData.filter(user => {
      let name = (user.name || user.username || '').toLowerCase();
      let email = (user.email || '').toLowerCase();
      return name.includes(searchTerm) || email.includes(searchTerm);
    });
    renderUnconfirmedTable(filtered);
  });

  // --------------------------
  // 3) Detail Modal Pengguna
  // --------------------------
  $('#detailModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const userId = button.data('user-id');
    const modal = $(this);

    // Reset tampilan
    modal.find('#userDetail').addClass('d-none');
    modal.find('#loading').removeClass('d-none');
    modal.find('#actionButtons button').addClass('d-none');

    // Fetch detail via AJAX
    $.ajax({
      url: '{{ route("admin.pengusaha.detail") }}',
      type: 'GET',
      data: { user_id: userId },
      success: function(response) {
        if (response.success) {
          const data = response.data;

          // Isi data
          modal.find('#userName').text(data.name);
          modal.find('#userEmail').text(data.email);
          modal.find('#userCreatedAt').text(new Date(data.created_at).toLocaleDateString());

          // Update status badge
          let statusBadge = modal.find('#userStatus');
          statusBadge.text(data.is_banned ? 'Diblokir' : 'Aktif');
          statusBadge.removeClass('status-active status-banned')
                     .addClass(data.is_banned ? 'status-banned' : 'status-active');

          // Tentukan tombol mana yang tampil
          if (data.is_banned) {
            modal.find('#banButton').addClass('d-none');
            modal.find('#unbanButton').removeClass('d-none');
          } else {
            modal.find('#banButton').removeClass('d-none');
            modal.find('#unbanButton').addClass('d-none');
          }

          // Simpan user_id pada tombol
          modal.find('#banButton').data('user-id', userId);
          modal.find('#unbanButton').data('user-id', userId);

          // Tampilkan detail, sembunyikan loading
          modal.find('#loading').addClass('d-none');
          modal.find('#userDetail').removeClass('d-none');
        } else {
          Swal.fire('Error!', response.message, 'error');
          modal.modal('hide');
        }
      },
      error: function() {
        Swal.fire('Error!', 'Gagal memuat detail pengguna', 'error');
        modal.modal('hide');
      }
    });
  });

  // --------------------------
  // 4) Tombol Ban (Blokir) 
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
                <i class="bi bi-person-slash me-1"></i> Blokir Pengusaha
              `).prop('disabled', false);
            }
          },
          error: function() {
            Swal.fire('Error!', 'Gagal memblokir pengusaha', 'error');
            button.html(`
              <i class="bi bi-person-slash me-1"></i> Blokir Pengusaha
            `).prop('disabled', false);
          }
        });
      }
    });
  });

  // --------------------------
  // 5) Tombol Unban (Buka Blokir)
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
            Swal.fire('Error!', 'Gagal membuka blokir pengusaha', 'error');
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
