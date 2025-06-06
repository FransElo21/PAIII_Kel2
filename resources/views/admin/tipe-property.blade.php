@extends('layouts.admin.index-admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
@php
    // Get and remove flash messages
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

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Tipe Property</h3>
        <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addPropertyTypeModal">
            <i class="bi bi-plus-lg me-2"></i> Tambah Tipe Properti
        </button>
    </div>

    <!-- Tabel Tipe Property -->
    <div class="card mt-4 shadow-sm rounded-4">
        <div class="card-body p-3">
            <div class="table-responsive white-space-nowrap">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tipe Property</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($propertyTypes as $type)
                            <tr>
                                <td>{{ $loop->iteration }}</td>  <!-- Menampilkan nomor urut -->
                                <td>{{ $type->property_type }}</td>
                                <td>{{ $type->description }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button 
                                            class="btn btn-warning btn-sm rounded-circle"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editPropertyTypeModal"
                                            onclick="editPropertyType({{ $type->id }})" 
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.tipe_property.destroy', $type->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-circle" onclick="return confirm('Apakah Anda yakin?')" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addPropertyTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.tipe_property.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tipe Property</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Tipe Property</label>
                    <input type="text" name="property_type" class="form-control rounded-pill" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control rounded-3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary rounded-pill">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editPropertyTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="editForm" method="POST">
            @csrf
            @method('PUT') <!-- Gunakan @method('PUT') untuk metode PUT -->
            <div class="modal-header">
                <h5 class="modal-title">Edit Tipe Property</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Tipe Property</label>
                    <input type="text" name="property_type" id="edit_property_type" class="form-control rounded-pill" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="edit_description" class="form-control rounded-3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary rounded-pill">Perbarui</button>
            </div>
        </form>
    </div>
</div>


<script>
    function editPropertyType(id) {
    fetch(`/admin/tipe-property/${id}/edit`)  // Pastikan route ini sesuai dengan route di web.php
        .then(response => response.json())   // Mendapatkan data dalam format JSON
        .then(data => {
            // Pastikan data tidak null atau tidak ditemukan
            if (data.message) {
                alert(data.message);
                return;
            }

            // Update form action
            document.getElementById('editForm').action = `/admin/tipe-property/${id}/update`;

            // Populate the form fields with the data returned
            document.getElementById('edit_property_type').value = data.property_type;
            document.getElementById('edit_description').value = data.description;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
</script>

@endsection
