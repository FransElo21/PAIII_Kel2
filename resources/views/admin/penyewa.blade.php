@extends('layouts.admin.index-admin')
@section('content')

<style>
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
            html {
                scroll-behavior: smooth;
            }
</style>

<div class="container">
    <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Users</a></li>
          <li class="breadcrumb-item active" aria-current="page">Penyewa</li>
    </ol>

    <h3>Daftar Penyewa (Costumer)</h3>

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
                            @foreach ($users as $user)
                            <tr>
                                <td><input class="form-check-input" type="checkbox"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name ?? $user->username ?? '-' }}</td>
                                <td>{{ $user->email ?? '-' }}</td>
                                <td>{{ $user->user_role_id }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- Tombol Edit (Menuju Halaman Edit) -->
                                        <a href="" class="btn btn-warning btn-sm rounded-circle" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form id="delete-form-" action="" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="property_id" value="">
                                            <button type="button" class="btn btn-danger btn-sm rounded-circle" onclick="" title="Delete">
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
            
            <!-- Pagination (jika diperlukan) -->
        </div>
    </div>
@endsection
