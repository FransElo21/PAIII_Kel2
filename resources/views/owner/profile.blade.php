@extends('layouts.owner.index-owner')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success') || session('error'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: '{{ session('success') }}',
          timer: 1800,
          showConfirmButton: false
        });
      @endif

      @if(session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Oops!',
          text: '{{ session('error') }}',
          timer: 2000,
          showConfirmButton: false
        });
      @endif
    });
  </script>
@endif

@php
    function getInitials($name) {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $w) {
            if ($w) $initials .= strtoupper($w[0]);
            if (strlen($initials) == 2) break;
        }
        return $initials;
    }
    $initials = getInitials($data->name ?? '');

    // Anti broken image: cek file exists di public_path
    $avatarUrl = null;
    if ($data->photo && file_exists(public_path('storage/' . $data->photo))) {
        $avatarUrl = asset('storage/' . $data->photo);
    }
@endphp

<style>
  :root {
    --green: #289A84;
    --green-light: #eaf7f2;
    --gray-light: #f6f7fb;
    --gray-dark: #344356;
    --white: #ffffff;
    --radius-lg: 1rem;
    --shadow-md: 0 4px 32px rgba(40, 154, 132, 0.13);
  }
  body { background: var(--gray-light); }
  .profile-card {
    border: none;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden; margin-bottom: 2rem;
    background: var(--white);
    transition: box-shadow .2s;
  }
  .profile-card:hover {
    box-shadow: 0 8px 40px rgba(40, 154, 132, 0.20);
  }
  .profile-header {
    background: linear-gradient(105deg, var(--green) 0%, #43ca97 100%);
    height: 140px;
    position: relative;
  }
  .profile-avatar-wrapper {
    position: absolute; top: 100px; left: 50%;
    transform: translateX(-50%);
    width: 128px; height: 128px;
    background: var(--white);
    border-radius: 50%; padding: 5px;
    box-shadow: 0 4px 20px rgba(40,154,132,0.10);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; color: var(--green);
    background: var(--white);
    border: 4px solid var(--gray-light);
    overflow: hidden;
  }
  .profile-avatar-wrapper img {
    width: 100%; height: 100%; border-radius: 50%; object-fit: cover;
    border: none;
    box-shadow: none;
    background: #fff;
  }
  .avatar-initials {
    font-weight: 700;
    font-size: 2.8rem;
    background: var(--green-light);
    color: var(--green);
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
    letter-spacing: 2px;
    user-select: none;
  }
  .profile-body {
    padding-top: 86px; text-align: center; background: var(--white);
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  }
  .profile-body h5 {
    margin-bottom: .25rem; font-weight: 700; color: var(--gray-dark); font-size: 1.45rem;
  }
  .profile-body p {
    margin-bottom: .7rem; color: #7a889b;
  }
  .verified-badge {
    display: inline-block;
    background: var(--green);
    color: var(--white);
    font-size: .85rem;
    padding: .24rem .85rem;
    border-radius: 1rem;
    margin-top: .4rem;
    box-shadow: 0 2px 10px rgba(40,154,132,0.09);
    letter-spacing: .2px;
  }
  .profile-list-label {
    color: #8a97ac;
    font-size: 1rem;
    margin-bottom: 0;
    font-weight: 500;
    letter-spacing: .01em;
  }
  .profile-list-value {
    font-weight: 600;
    font-size: 1.09rem;
    color: var(--gray-dark);
    margin-bottom: 1.1rem;
  }
  @media (max-width: 575px) {
    .profile-card { margin-top: 36px; }
    .profile-avatar-wrapper { width: 90px; height: 90px; top: 65px; font-size: 1.6rem; }
    .profile-body { padding-top: 65px; }
  }
</style>

<div class="container py-4">
  <div class="row justify-content-center gx-4">
    <section class="col-lg-6 col-md-8 col-12">
      <div class="card profile-card">
        <div class="profile-header">
          <div class="profile-avatar-wrapper">
            @if($avatarUrl)
              <img src="{{ $avatarUrl }}" alt="Foto Profil" loading="lazy">
            @else
              <span class="avatar-initials">{{ $initials }}</span>
            @endif
          </div>
        </div>
        <div class="card-body profile-body">
          <h5>{{ $data->username ?? '-' }}</h5>
          <p class="mb-1">Pemilik Properti</p>
          @if($data->email_verified_at)
            <span class="verified-badge">
              <i class="bi bi-patch-check-fill"></i> Terverifikasi
            </span>
          @endif

          <div class="mt-4 text-start px-4">
            <div>
              <span class="profile-list-label">Nama Lengkap</span>
              <div class="profile-list-value">{{ $data->name }}</div>
            </div>
            <div>
              <span class="profile-list-label">Email</span>
              <div class="profile-list-value">{{ $data->email }}</div>
            </div>
            <div>
              <span class="profile-list-label">Nomor HP</span>
              <div class="profile-list-value">{{ $data->phone_pemilik_properti ?? '-' }}</div>
            </div>
            <div>
              <span class="profile-list-label">Jenis Kelamin</span>
              <div class="profile-list-value">{{ $data->gender_pemilik_properti ?? '-' }}</div>
            </div>
            <div>
              <span class="profile-list-label">Alamat</span>
              <div class="profile-list-value">{{ $data->address_pemilik_properti ?? '-' }}</div>
            </div>
          </div>

          <a href="{{ route('profileowner.edit') }}"
             class="btn btn-outline-success mt-4 rounded-pill px-4 fw-semibold">
            <i class="bi bi-pencil-square me-1"></i> Edit Profil
          </a>
        </div>
      </div>
    </section>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@endsection
