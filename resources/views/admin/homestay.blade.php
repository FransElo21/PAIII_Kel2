@extends('layouts.admin.index-admin')

@section('content')
<!-- Custom Styles -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    // Retrieve and remove flash messages once
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

<!-- Main Content -->
<div class="container mt-4">
    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <!-- Search Form -->
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

    <!-- Property List Table -->
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
                                    <a
                                        href="{{ url('admin/homestay/' . $property->id . '/detail') }}"
                                        class="btn btn-sm btn-info rounded-circle"
                                        title="Detail"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a
                                        href="{{ url('admin/homestay/' . $property->id . '/bookings') }}"
                                        class="btn btn-success btn-sm rounded-circle"
                                        title="Booking"
                                    >
                                        <i class="bi bi-calendar-check"></i>
                                    </a>
                                    <form
                                        id="delete-form-{{ $property->id }}"
                                        action="{{ url('admin/homestay/' . $property->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm rounded-circle"
                                            onclick="confirmDelete({{ $property->id }})"
                                            title="Delete"
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
                    {{ $properties->links() }}  <!-- Pagination links -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
</style>

@endsection
