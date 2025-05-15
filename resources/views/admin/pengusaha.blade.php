@extends('layouts.admin.index-admin')
@section('content')

<div class="container">
    <h3>Daftar Pengusaha (Owner)</h3>

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
