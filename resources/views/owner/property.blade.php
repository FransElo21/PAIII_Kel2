@extends('layouts.owner.index-owner')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@php
    // Ambil & hapus sekali flash message
    $successMsg = session()->pull('success');
    $errorMsg   = session()->pull('error');
@endphp

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<div class="row g-3 align-items-center">
    <div class="col-auto">
        {{-- Form Search --}}
        <form method="GET" action="{{ url()->current() }}">
            <div class="position-relative">
                <input
                    class="form-control px-5 rounded-pill"
                    type="search"
                    name="search"
                    placeholder="Cari Property"
                    value="{{ request('search') }}"
                >
                <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">
                    search
                </span>
            </div>
        </form>
    </div>
    <div class="col-auto flex-grow-1"></div>
    <div class="col-auto">
        <a href="{{ route('owner.add-property') }}" class="btn btn-primary px-4 rounded-pill">
            <i class="bi bi-plus-lg me-2"></i> Tambah Property
        </a>
    </div>
</div>

<div class="card mt-4 shadow-sm rounded-4">
    <div class="card-body p-3">
        <div class="table-responsive white-space-nowrap">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><input class="form-check-input" type="checkbox"></th>
                        <th>No.</th>
                        <th>Nama Property</th>
                        <th>Kategoti</th>
                        <th>Alamat</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($properties as $idx => $property)
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $property->name }}</td>
                        <td>{{ $property->property_type }}</td>
                        <td>{{ $property->subdistrict }}</td>
                        <td>{{ \Carbon\Carbon::parse($property->created_at)->format('d M Y') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a
                                  href="{{ route('owner.edit-property', $property->id) }}"
                                  class="btn btn-warning btn-sm rounded-circle"
                                  title="Edit"
                                >
                                  <i class="bi bi-pencil-square"></i>
                                </a>
                                <form
                                  id="delete-form-{{ $property->id }}"
                                  action="{{ route('owner.delete-property') }}"
                                  method="POST" style="display:inline;"
                                >
                                    @csrf
                                    <input type="hidden" name="property_id" value="{{ $property->id }}">
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
                        <td colspan="7" class="text-center py-4 text-muted">
                            No properties found.
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

        {{-- Jika nanti pakai pagination manual, di sini --}}
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
</style>

@endsection
