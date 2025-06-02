@extends('layouts.index-welcome')
@section('content')

@php
  // Helper untuk inisial (jika belum ada di model, tambahkan juga accessor seperti sebelumnya)
  $initials = Auth::user()->initials;
  $avatarUrl = Auth::user()->profile_picture 
               ? asset('storage/' . Auth::user()->profile_picture) 
               : null;
@endphp

<style>
  :root {
    --green: #289A84;
    --gray-light: #f1f3f5;
    --gray-dark: #495057;
    --white: #ffffff;
    --radius-lg: 1rem;
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.1);
  }
  body { background: var(--gray-light); }

  /* Sidebar */
  .sidebar {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    padding: 2rem 1rem;
  }
  .menu-item {
    color: var(--gray-dark);
    padding: .75rem 1rem;
    border-radius: var(--radius-lg);
    margin-bottom: .5rem;
    display: flex; align-items: center;
    transition: background .2s, color .2s;
  }
  .menu-item i { margin-right: .5rem; font-size: 1.2rem; }
  .menu-item.active,
  .menu-item:hover {
    background: var(--green); color: var(--white)!important;
  }

  /* Profile card */
  .profile-card {
    border: none;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden; margin-bottom: 2rem;
  }
  .profile-header {
    background: linear-gradient(120deg, var(--green) 0%, #2fa163 100%);
    height: 160px; position: relative;
  }
  .profile-avatar-wrapper {
    position: absolute; top: 100px; left: 50%;
    transform: translateX(-50%);
    width: 140px; height: 140px;
    background: var(--white);
    border-radius: 50%; padding: 4px;
    box-shadow: var(--shadow-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; color: var(--white);
    background: var(--gray-dark);
  }
  .profile-avatar-wrapper img {
    width: 100%; height: 100%; border-radius: 50%;
    object-fit: cover;
  }
  .profile-body {
    padding-top: 100px; text-align: center;
    background: var(--white);
  }
  .profile-body h5 {
    margin-bottom: .25rem; font-weight: 600;
    color: var(--gray-dark);
  }
  .profile-body p {
    margin-bottom: 1rem; color: var(--gray-dark);
  }
  .verified-badge {
    display: inline-block;
    background: var(--green);
    color: var(--white);
    font-size: .75rem;
    padding: .25rem .75rem;
    border-radius: 12px;
    margin-top: .5rem;
  }
  .edit-avatar-btn {
    position: absolute; bottom: 8px; right: 8px;
    background: rgba(255,255,255,0.8);
    border-radius: 50%; padding: .3rem;
    transition: background .2s;
  }
  .edit-avatar-btn:hover { background: var(--white); }

  /* Inisial avatar */
  .avatar-initials {
    font-weight: 600;
    background: var(--green);
    color: var(--white);
  }
</style>

<div class="container py-4">
  <div class="row gx-4">
    <!-- Sidebar -->
    <aside class="col-lg-3">
      <div class="sidebar">
        <a href="{{ route('profileuser.show') }}"
           class="menu-item {{ request()->routeIs('profileuser.show') ? 'active':'' }}">
          <i class="bi bi-person-circle"></i> Profil Saya
        </a>
        <a href="{{ route('riwayat-transaksi.index') }}"
           class="menu-item {{ request()->routeIs('riwayat-transaksi.index') ? 'active':'' }}">
          <i class="bi bi-clock-history"></i> Riwayat Transaksi
        </a>
      </div>
    </aside>

    <!-- Profile -->
    <section class="col-lg-9">
      <div class="card profile-card">
        <div class="profile-header">
          <!-- Tombol ganti avatar -->
          <form action="" method="POST"
                enctype="multipart/form-data" class="edit-avatar-btn">
            @csrf
            <label for="avatarInput" class="mb-0" title="Ganti Foto">
              <i class="bi bi-pencil-fill text-dark"></i>
            </label>
            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none"
                   onchange="this.form.submit()">
          </form>

          <div class="profile-avatar-wrapper avatar-initials">
            @if($avatarUrl)
              <img src="{{ $avatarUrl }}" alt="Avatar">
            @else
              {{ $initials }}
            @endif
          </div>
        </div>

        <div class="card-body profile-body">
          {{-- <h5>{{ $user->name }}</h5> --}}
          <p class="mb-1">{{ $user->userType_name }}</p>
          {{-- @if($user->is_verified)
            <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
          @endif --}}

          <div class="mt-4 text-start px-4">
            <h6 class="text-muted mb-2">Email</h6>
            <p>{{ $user->email }}</p>

            <h6 class="text-muted mb-2">Nomor HP</h6>
            <p>{{ $user->phone ?? '-' }}</p>

            <h6 class="text-muted mb-2">Alamat</h6>
            <p>{{ $user->address ?? '-' }}</p>
          </div>

          <a href=""
             class="btn btn-outline-success mt-4 rounded-pill px-4">
            <i class="bi bi-pencil-square me-1"></i> Edit Profil
          </a>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Bootstrap Icons & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
