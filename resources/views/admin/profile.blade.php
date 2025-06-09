@extends('layouts.admin.index-admin')
@section('content')

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
    $initials = getInitials($user->name ?? '');
@endphp

<style>
    body { background: #f3f7f9; }
    .profile-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
    }
    .profile-card {
        background: rgba(255,255,255,0.75);
        border-radius: 1.2rem;
        box-shadow: 0 6px 36px rgba(40,154,132,0.15), 0 1.5px 6px 0 rgba(80,160,120,0.05);
        padding: 2.8rem 2rem 2.5rem 2rem;
        width: 100%; max-width: 410px;
        text-align: center;
        backdrop-filter: blur(6px);
        border: 1.5px solid rgba(40,154,132,0.09);
        transition: box-shadow 0.3s;
        position: relative;
        z-index: 1;
    }
    .profile-card:hover {
        box-shadow: 0 8px 40px rgba(40,154,132,0.21), 0 2px 8px 0 rgba(80,160,120,0.07);
    }
    .profile-avatar {
        width: 110px; height: 110px; border-radius: 50%;
        background: linear-gradient(140deg, #2dd4bf 40%, #4ade80 100%);
        color: #fff;
        font-size: 2.7rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        margin: -78px auto 1.7rem auto;
        border: 5px solid #fff;
        box-shadow: 0 4px 20px rgba(45,212,191,0.19);
        transition: transform 0.15s;
        position: relative;
        z-index: 2;
    }
    .profile-card:hover .profile-avatar {
        transform: scale(1.04) rotate(-3deg);
    }
    .profile-card h4 {
        font-weight: 700;
        color: #289a84;
        letter-spacing: 0.02em;
    }
    .profile-card .text-muted {
        font-size: 1.03rem;
        color: #5f7c8a !important;
        margin-bottom: 1.3rem !important;
    }
    .profile-card strong {
        color: #404c5a;
        font-weight: 600;
        letter-spacing: 0.01em;
    }
    .profile-card .badge {
        padding: .47em 1.1em .4em 1.1em;
        border-radius: 1.3em;
        font-size: 0.92em;
        margin-top: .2em;
        letter-spacing: 0.03em;
        font-weight: 600;
    }
    .profile-card .badge.bg-success {
        background: linear-gradient(120deg, #36d399 40%, #289a84 100%);
        color: #fff;
    }
    .profile-card .badge.bg-secondary {
        background: #e0e7ef;
        color: #566575;
    }
    .profile-card .mb-2 {
        margin-bottom: 1.2rem !important;
    }
    .profile-card .mb-1 {
        margin-bottom: 0.62rem !important;
    }
    @media (max-width: 576px) {
        .profile-card {
            padding: 2rem 1rem 1.5rem 1rem;
        }
        .profile-avatar {
            width: 85px; height: 85px; font-size: 2rem;
            margin-top: -55px;
        }
    }
</style>

<div class="profile-center">
    <div class="profile-card mt-5">
        <div class="profile-avatar">{{ $initials }}</div>
        <h4 class="mb-1">{{ $user->name }}</h4>
        <div class="mb-2 text-muted"><i class="bi bi-person-gear me-1"></i>Administrator</div>
        <div class="mb-2">
            <strong><i class="bi bi-person me-1"></i>Username:</strong><br>
            {{ $user->username }}
        </div>
        <div class="mb-2">
            <strong><i class="bi bi-envelope me-1"></i>Email:</strong><br>
            {{ $user->email }}
        </div>
        <div class="mb-2">
            <strong><i class="bi bi-patch-check-fill me-1"></i>Email Verified:</strong><br>
            @if($user->email_verified_at)
                <span class="badge bg-success">Terverifikasi</span>
            @else
                <span class="badge bg-secondary">Belum</span>
            @endif
        </div>
        <div class="mb-2">
            <strong><i class="bi bi-calendar-event me-1"></i>Dibuat:</strong><br>
            {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y H:i') }}
        </div>
    </div>
</div>

{{-- Icon Bootstrap --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@endsection
