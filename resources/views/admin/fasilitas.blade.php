@extends('layouts.admin.index-admin')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"  rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
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

    <div class="container ">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Daftar Fasilitas</h3>
            <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
                <i class="bi bi-plus-lg me-2"></i> Tambah Fasilitas
            </button>
        </div>

        <!-- Tabel Fasilitas -->
        <div class="card mt-4 shadow-sm rounded-4">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Ikon</th>
                                <th>Nama Fasilitas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paginatedFacilities as $facility)
                                <tr>
                                    <!-- Calculate the correct pagination number -->
                                    <td>{{ $loop->iteration + ($paginatedFacilities->currentPage() - 1) * $paginatedFacilities->perPage() }}</td> <!-- Displaying the correct page number -->
                                    <td><i class="bi bi-{{ $facility->icon }}"></i></td>
                                    <td>{{ $facility->name }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button 
                                                class="btn btn-warning btn-sm rounded-circle"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editFacilityModal"
                                                onclick="editFacility({{ $facility->id }})" 
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" style="display: inline;">
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

        <!-- Pagination Controls -->
        <div class="mt-3">
            {{ $paginatedFacilities->links() }}
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addFacilityModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin.facilities.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Fasilitas</label>
                        <input type="text" name="facility_name" class="form-control rounded-pill" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ikon</label>
                        <input type="text" name="icon" class="form-control rounded-pill" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editFacilityModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Fasilitas</label>
                        <input type="text" name="facility_name" id="edit_facility_name" class="form-control rounded-pill" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ikon</label>
                        <input type="text" name="icon" id="edit_icon" class="form-control rounded-pill" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editFacility(id) {
            fetch(`/admin/facilities/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editForm').action = `/admin/facilities/${id}`;
                    document.getElementById('edit_facility_name').value = data.facility_name;
                    document.getElementById('edit_icon').value = data.icon;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
