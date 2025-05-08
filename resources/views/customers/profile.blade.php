@extends('layouts.index-welcome')
@section('content')
<style>
    /* Sidebar styling */
    .sidebar {
        background-color: #f8f9fa;
        border-right: 1px solid #dee2e6;
        padding: 2rem 1rem;
        min-height: 100vh;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        color: #495057;
        text-decoration: none;
    }

    .menu-item:hover,
    .menu-item.active {
        background-color: #28a745;
        color: white !important;
        font-weight: 500;
    }

    .menu-item i {
        font-size: 1.1rem;
    }

    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        background-color: #28a745;
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        margin-top: -60px;
        object-fit: cover;
    }

    .verified-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 5px;
    }

    .activity-list .list-group-item {
        border: none;
        padding: 0.75rem 0;
    }

    @media (max-width: 768px) {
        .sidebar {
            border-right: none;
            border-bottom: 1px solid #dee2e6;
            min-height: auto;
        }
    }
</style>

<!-- Main Content -->
<div class="container">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('profileuser.show') }}" class="menu-item {{ request()->routeIs('profileuser.show') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i> Profil Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('riwayat-transaksi.index') }}" class="menu-item {{ request()->routeIs('riwayat-transaksi.index') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat Transaksi
                    </a>
                </li>
            </ul>
        </div>

        <!-- Profile Section -->
        <div class="col-md-9">
            <div class="card profile-card">
                <div class="card-header profile-header">
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('assets/images/avatars/01.png') }}" alt="Avatar" class="profile-avatar shadow-sm">

                    <h5 class="mt-3 mb-1">{{ $user->username }}</h5>
                    <p class="text-muted">{{ $user->userType_name }}</p>

                    
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection