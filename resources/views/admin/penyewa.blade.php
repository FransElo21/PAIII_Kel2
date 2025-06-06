@extends('layouts.admin.index-admin')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css"> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<style>
    /* Style untuk search bar */
    .input-group-text {
        border-radius: 20px 0 0 20px !important;
        padding: 0.375rem 0.75rem;
    }
    .form-control {
        padding: 0.375rem 0.75rem;
    }
    .border-start-0 {
        border-left: 0 !important;
    }
    .border-end-0 {
        border-right: 0 !important;
    }
    /* Breadcrumb */
    .breadcrumb {
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #94a3b8;
    }
    .breadcrumb-item a {
        color: #64748b;
        transition: color 0.3s ease;
    }
    .breadcrumb-item a:hover {
        color: #289A84;
    }
    .breadcrumb-item.active {
        color: #289A84;
        font-weight: 600;
    }
    /* Status badge styles */
    .status-badge {
        padding: 0.4em 0.6em;
        font-size: 0.85em;
        border-radius: 1rem;
        font-weight: 500;
    }
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    .status-banned {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengguna</a></li>
        <li class="breadcrumb-item active" aria-current="page">Penyewa</li>
    </ol>

    <h4 class="mb-4" >Daftar Penyewa (Customer)</h4>

    <!-- Form Pencarian -->
    <form action="{{ route('users.rolePenyewa') }}" method="GET" class="mb-3 col-md-3 p-0">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search"></i>
            </span>
            <input
                name="search"
                type="text"
                class="form-control border-start-0"
                placeholder="Cari nama atau email..."
                style="border-radius: 0 20px 20px 0"
                value="{{ request('search') }}"
                autocomplete="off"
            >
        </div>
    </form>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
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
                                <span class="status-badge 
                                    {{ $user->is_banned == 0 ? 'status-active' : 'status-banned' }}">
                                    {{ $user->is_banned == 0 ? 'Aktif' : 'Diblokir' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-sm rounded-circle" 
                                            data-bs-toggle="modal" data-bs-target="#detailModal" 
                                            data-user-id="{{ $user->id }}" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
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
            
            <div class="mt-3 d-flex justify-content-center">
                {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pengguna -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Penyewa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loading" class="d-none text-center p-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                </div>
                
                <div id="userDetail" class="d-none">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Nama:</div>
                        <div class="col-md-9" id="userName"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Email:</div>
                        <div class="col-md-9" id="userEmail"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Tanggal Daftar:</div>
                        <div class="col-md-9" id="userCreatedAt"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Status:</div>
                        <div class="col-md-9">
                            <span id="userStatus" class="status-badge"></span>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4" id="actionButtons">
                    <button id="banButton" class="btn btn-danger d-none" style="border-radius: 20px;">
                        <i class="bi bi-person-slash"></i> Blokir Penyewa
                    </button>
                    <button id="unbanButton" class="btn btn-success d-none" style="border-radius: 20px;">
                        <i class="bi bi-person-check"></i> Buka Blokir
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Detail modal handler
    $('#detailModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const userId = button.data('user-id');
        const modal = $(this);
        
        modal.find('#userDetail').addClass('d-none');
        modal.find('#loading').removeClass('d-none');
        modal.find('#actionButtons button').addClass('d-none');
        
        $.ajax({
            url: '{{ route("admin.penyewa.detail") }}',
            type: 'GET',
            data: { user_id: userId },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    
                    modal.find('#userName').text(data.name);
                    modal.find('#userEmail').text(data.email);
                    modal.find('#userCreatedAt').text(new Date(data.created_at).toLocaleDateString());
                    
                    const statusBadge = modal.find('#userStatus');
                    statusBadge.text(data.is_banned ? 'Diblokir' : 'Aktif');
                    statusBadge.removeClass('status-active status-banned')
                              .addClass(data.is_banned ? 'status-banned' : 'status-active');
                    
                    modal.find('#banButton')
                        .toggleClass('d-none', data.is_banned)
                        .data('user-id', userId);
                        
                    modal.find('#unbanButton')
                        .toggleClass('d-none', !data.is_banned)
                        .data('user-id', userId);
                    
                    modal.find('#loading').addClass('d-none');
                    modal.find('#userDetail').removeClass('d-none');
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Gagal memuat detail pengguna', 'error');
            }
        });
    });

    // Tombol Blokir
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
                    beforeSend: () => button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...'),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Diblokir!', response.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                            button.html('<i class="bi bi-person-slash"></i> Blokir Penyewa');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal memblokir penyewa', 'error');
                        button.html('<i class="bi bi-person-slash"></i> Blokir Penyewa');
                    }
                });
            }
        });
    });

    // Tombol Buka Blokir
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
                    beforeSend: () => button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...'),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Sukses!', response.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                            button.html('<i class="bi bi-person-check"></i> Buka Blokir');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal membuka blokir penyewa', 'error');
                        button.html('<i class="bi bi-person-check"></i> Buka Blokir');
                    }
                });
            }
        });
    });
});
</script>
@endsection