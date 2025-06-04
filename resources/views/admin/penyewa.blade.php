@extends('layouts.admin.index-admin')
@section('content')

<style>
    /* Styling breadcrumb dan lainnya tetap seperti sebelumnya */

    /* Custom styling untuk icon di dalam input */
    .input-group .form-control {
        border-top-left-radius: 25px;
        border-bottom-left-radius: 25px;
    }
    .input-group-text {
        background-color: #fff;
        border-top-right-radius: 25px;
        border-bottom-right-radius: 25px;
        border-left: none;
        cursor: pointer;
    }
    .input-group-text i {
        color: #6c757d; /* warna icon */
    }
    /* Responsive width */
    @media (max-width: 576px) {
        .input-group {
            width: 100%;
        }
    }
     /* Breadcrumb */
        .breadcrumb {
            /* padding: 0.75rem 1.25rem; */
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); */
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
        <li class="breadcrumb-item active" aria-current="page">Penyewa</li>
    </ol>

    <h3>Daftar Penyewa (Customer)</h3>

    <!-- Form Pencarian -->
    <form action="{{ route('users.rolePenyewa') }}" method="GET" class="mb-3">
        <div class="input-group" style="max-width: 350px;">
            <span class="input-group-text" id="basic-addon1">
                <i class="bi bi-search"></i>
            </span>
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Cari nama, username, atau email..."
                value="{{ request('search') }}"
                aria-label="Cari"
                aria-describedby="basic-addon1"
            >
            {{-- <button class="btn btn-primary" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                Cari
            </button> --}}
        </div>
    </form>

    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive white-space-nowrap">
                    <table class="table align-middle">
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
                                <td>{{ $user->name ?? $user->username ?? '-' }}</td>
                                <td>{{ $user->email ?? '-' }}</td>
                                <td>{{ $user->user_role_id }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="" class="btn btn-warning btn-sm rounded-circle" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-circle" onclick="return confirm('Yakin ingin menghapus user ini?')" title="Delete">
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
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
