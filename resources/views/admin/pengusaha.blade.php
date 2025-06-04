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
    
    /* Hapus border kiri pada input */
    .border-start-0 {
        border-left: 0 !important;
    }
    
    /* Hapus border kanan pada ikon */
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
</style>

<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengguna</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengusaha</li>
    </ol>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Daftar Pengusaha (Owner)</h3>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#unconfirmedModal" style="border-radius: 20px;">
            <i class="bi bi-person-check"></i> Konfirmasi Akun Baru
        </button>
    </div>

    <!-- Form Pencarian -->
    <form action="{{ route('users.rolePengusaha') }}" method="GET" class="mb-3 col-md-3 p-0">
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
            {{-- <button type="submit" class="btn btn-primary" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                Cari
            </button> --}}
        </div>
    </form>

    <!-- Modal for unconfirmed accounts -->
    <div class="modal fade" id="unconfirmedModal" tabindex="-1" aria-labelledby="unconfirmedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unconfirmedModalLabel">Akun Pengusaha yang Perlu Dikonfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search input untuk modal -->
                    <div class="mb-3 col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input id="searchUnconfirmed" type="text" class="form-control border-start-0" placeholder="Cari nama atau email..." style="border-radius: 0 20px 20px 0">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="unconfirmedTableBody">
                                <!-- Data akan dimuat via AJAX -->
                                <tr>
                                    <td colspan="5" class="text-center">Memuat data...</td>
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

    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive white-space-nowrap">
                    <table class="table align-middle" id="mainUserTable">
                        <thead class="table-light">
                            <tr>
                                <th><input class="form-check-input" type="checkbox"></th>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>User Role ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <td><input class="form-check-input" type="checkbox"></td>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td class="user-name">{{ $user->name ?? $user->username ?? '-' }}</td>
                                <td class="user-email">{{ $user->email ?? '-' }}</td>
                                <td>{{ $user->user_role_id }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="" class="btn btn-warning btn-sm rounded-circle" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form id="delete-form-{{ $user->id }}" action="" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="property_id" value="">
                                            <button type="button" class="btn btn-danger btn-sm rounded-circle" onclick="" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form> 
                                    </div>                              
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $users->appends(['search' => request('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Tampilkan pesan sukses/error dari session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // ==== LOAD UNCONFIRMED ACCOUNTS ====
        $('#unconfirmedModal').on('shown.bs.modal', function () {
            loadUnconfirmedAccounts();
        });

        let unconfirmedData = []; // cache data unconfirmed untuk search

        function loadUnconfirmedAccounts() {
            $.ajax({
                url: '{{ route("admin.pengusaha.unconfirmed") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        unconfirmedData = response.data; // simpan data
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
                tableBody.append('<tr><td colspan="5" class="text-center">Tidak ada akun yang perlu dikonfirmasi</td></tr>');
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
                            <button class="btn btn-success btn-sm confirm-btn" data-user-id="${user.id}" style="border-radius: 20px;">
                                <i class="bi bi-check-circle"></i> Konfirmasi
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            // Attach click event for confirm buttons
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
                    button.prop('disabled', true);
                    button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: 'Akun berhasil dikonfirmasi',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            button.closest('tr').fadeOut(400, function() {
                                $(this).remove();
                                unconfirmedData = unconfirmedData.filter(u => u.id !== userId);
                                renderUnconfirmedTable(unconfirmedData);
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengkonfirmasi akun',
                            timer: 3000,
                            showConfirmButton: false
                        });
                        button.prop('disabled', false);
                        button.html('<i class="bi bi-check-circle"></i> Konfirmasi');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengkonfirmasi akun',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    button.prop('disabled', false);
                    button.html('<i class="bi bi-check-circle"></i> Konfirmasi');
                }
            });
        }

        // ==== SEARCH UNTUK MODAL UNCONFIRMED ====
        $('#searchUnconfirmed').on('keyup', function() {
            let searchTerm = $(this).val().toLowerCase();

            let filtered = unconfirmedData.filter(user => {
                let name = (user.name || user.username || '').toLowerCase();
                let email = (user.email || '').toLowerCase();
                return name.includes(searchTerm) || email.includes(searchTerm);
            });

            renderUnconfirmedTable(filtered);
        });
    });
</script>

@endsection
