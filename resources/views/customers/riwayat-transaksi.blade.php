@extends('layouts.index-welcome')
@section('content')

<style>
  :root {
    --green: #289A84;
    --green-light: #eaf7f2;
    --gray: #6c757d;
    --gray-light: #f1f3f5;
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
    --radius-lg: 0.75rem;
  }

  body {
    background: var(--gray-light);
  }

  /* Sidebar */
  .sidebar {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    padding: 2rem 1rem;
    height: 100%;
  }
  .menu-item {
    color: var(--gray);
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    border-radius: var(--radius-lg);
    transition: background .2s, color .2s;
    display: flex;
    align-items: center;
  }
  .menu-item i {
    margin-right: .5rem;
  }
  .menu-item.active,
  .menu-item:hover {
    background: var(--green);
    color: white !important;
  }

  /* Filters */
  .filter-btn {
    border-radius: 50px;
    padding: .5rem 2.25rem;
    background: white;
    color: var(--gray);
    border: 1px solid var(--gray-light);
    transition: background .2s, color .2s;
    margin-right: .5rem;
    margin-bottom: .5rem;
    text-decoration: none;
  }
  .filter-btn.active,
  .filter-btn:hover {
    background: var(--green);
    color: white;
    border-color: var(--green);
  }

  /* Transaction card */
  .transaction-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    position: relative;
  }
  .transaction-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
  }
  .transaction-card .row {
    margin: 0;
  }
  .transaction-card img {
    border-top-left-radius: var(--radius-lg);
    border-bottom-left-radius: var(--radius-lg);
    object-fit: cover;
    height: 100%;
    width: 100%;
  }
  .transaction-details {
    padding: 1.5rem;
  }
  .transaction-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: .25rem .75rem;
    border-radius: 50px;
    font-size: .85rem;
    font-weight: 600;
    text-transform: uppercase;
    color: white;
  }
  .transaction-status.belum-dibayar {
    background: #ffc107; /* kuning */
    color: #333;
  }
  .transaction-status.berhasil {
    background: var(--green); /* hijau */
    color: #fff;
  }
  .transaction-status.selesai {
    background: #17a2b8; /* biru */
    color: #fff;
  }
  .transaction-status.dibatalkan {
    background: #dc3545; /* merah */
    color: #fff;
  }
  .transaction-status.kadaluarsa {
    background: #6c757d; /* abu-abu */
    color: #fff;
  }

  /* Search */
  .search-container input {
    border-radius: 50px;
    padding-left: 1.25rem;
  }
  .search-container button {
    border-radius: 50px;
  }

  /* Tombol lebih besar daripada btn-sm */
  .transaction-details .btn {
    font-size: 0.9rem;
    padding: 0.3rem 0.6rem;
  }
</style>

<div class="container py-4">
  <div class="row">
    <!-- Sidebar -->
    <aside class="col-lg-3 mb-4">
      <div class="sidebar">
        <a href="{{ route('profileuser.show') }}"
           class="menu-item {{ request()->routeIs('profileuser.show') ? 'active' : '' }}">
          <i class="bi bi-person-circle"></i> Profil Saya
        </a>
        <a href="{{ route('riwayat-transaksi.index') }}"
           class="menu-item {{ request()->routeIs('riwayat-transaksi.index') ? 'active' : '' }}">
          <i class="bi bi-clock-history"></i> Riwayat Transaksi
        </a>
      </div>
    </aside>

    <!-- Main -->
    <section class="col-lg-9">
      <h4 class="mb-4">Riwayat Transaksi</h4>

      {{-- Search & Filters --}}
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        {{-- 1. Form pencarian --}}
        <form class="d-flex search-container mb-3 mb-md-0" action="{{ route('riwayat-transaksi.index') }}" method="GET">
          {{-- Pertahankan parameter status jika sedang difilter --}}
          <input type="hidden" name="status" value="{{ request('status') }}">
          <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            class="form-control me-2"
            placeholder="Cari Pesanan..."
          >
          <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-search"></i>
          </button>
        </form>

        {{-- 2. Tombol filter status, sertakan parameter 'q' jika ada --}}
        <div>
          @php
            // Ambil nilai q dari request (bisa kosong)
            $qParam = request('q') ? ['q' => request('q')] : [];
          @endphp

          <a
            href="{{ route('riwayat-transaksi.index', array_merge(['status' => null], $qParam)) }}"
            class="filter-btn {{ !request('status') ? 'active' : '' }}"
          >Semua</a>

          <a
            href="{{ route('riwayat-transaksi.index', array_merge(['status' => 'Belum Dibayar'], $qParam)) }}"
            class="filter-btn {{ request('status') == 'Belum Dibayar' ? 'active' : '' }}"
          >Belum Bayar</a>

          <a
            href="{{ route('riwayat-transaksi.index', array_merge(['status' => 'Berhasil'], $qParam)) }}"
            class="filter-btn {{ request('status') == 'Berhasil' ? 'active' : '' }}"
          >Berhasil</a>

          <a
            href="{{ route('riwayat-transaksi.index', array_merge(['status' => 'Dibatalkan'], $qParam)) }}"
            class="filter-btn {{ request('status') == 'Dibatalkan' ? 'active' : '' }}"
          >Dibatalkan</a>
        </div>
      </div>

      {{-- Transaction Cards --}}
      @forelse($bookings as $b)
        @php
          // Parse check-in & check-out
          $checkIn  = \Carbon\Carbon::parse($b->check_in);
          $checkOut = \Carbon\Carbon::parse($b->check_out);
          // Hitung jumlah malam
          $nights = $checkIn->diffInDays($checkOut);

          // Mapping CSS class berdasarkan status (dalam Bahasa Indonesia)
          $statusClass = match($b->status) {
            'Belum Dibayar' => 'belum-dibayar',
            'Berhasil'      => 'berhasil',
            'Selesai'       => 'selesai',
            'Dibatalkan'    => 'dibatalkan',
            default         => 'kadaluarsa',
          };

          // Label yang ditampilkan adalah nilai dari $b->status
          $statusLabel = $b->status;
        @endphp

        <div class="transaction-card mb-4">
          <div class="row g-0">
            <div class="col-md-4">
              <img
                src="{{ $b->property_image ? asset('storage/'.$b->property_image) : asset('assets/images/property.jpg') }}"
                alt="Foto Properti"
              >
            </div>
            <div class="col-md-8">
              <div class="transaction-details">
                <div class="d-flex justify-content-between">
                  <span class="transaction-status {{ $statusClass }}">
                    {{ $statusLabel }}
                  </span>
                </div>

                <h5 class="mt-2">{{ $b->property_name }}</h5>
                <p class="mb-1 small">{{ $b->alamat_selengkapnya }}</p>
                <p class="mb-1 small">
                  Durasi: <strong>{{ $nights }}</strong> malam
                  ({{ $checkIn->format('d-m-Y') }} – {{ $checkOut->format('d-m-Y') }})
                </p>
                <p class="fw-bold">Total: Rp {{ number_format($b->total_price, 0, ',', '.') }}</p>

                <div class="mt-3">
                  <a
                    href="{{ route('riwayat-transaksi.detail', $b->booking_id) }}"
                    class="btn btn-success me-2 rounded-pill"
                  >Lihat Detail</a>

                  @if($b->status === 'Belum Dibayar')
                    <a
                      href="{{ route('payment.show', ['booking_id' => $b->booking_id]) }}"
                      class="btn btn-primary rounded-pill ms-2"
                    >Lanjutkan Pembayaran</a>
                  @endif

                  @if($checkOut->isPast() && $b->status === 'Selesai' && !$b->reviewed)
                    <a
                      href="{{ route('review.create', ['booking_id' => $b->booking_id]) }}"
                      class="btn btn-outline-success rounded-pill ms-2"
                    ><i class="bi bi-chat-left-text me-1"></i>Ulasan</a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <p class="text-center text-muted">Tidak ada transaksi yang sesuai.</p>
      @endforelse

      {{-- Pagination (jika menggunakan paginate di controller) --}}
      @if(method_exists($bookings, 'links'))
        <div class="mt-4">{{ $bookings->appends(request()->only(['q','status']))->links() }}</div>
      @endif
    </section>
  </div>
</div>

{{-- Bootstrap Icons & JS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
