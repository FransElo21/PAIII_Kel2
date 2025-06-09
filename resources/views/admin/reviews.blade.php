@extends('layouts.admin.index-admin')

@section('content')
<!-- Google Fonts & Bootstrap Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
  .reviews-page {
    --font-base: 'Inter', sans-serif;
    --bg-light: #F8F9FA;
    --bg-card: #FFF;
    --primary: #289A84;
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
    --radius: .75rem;
    --transition: .2s ease-in-out;
  }
  .reviews-page .search-bar {
    max-width: 400px;
    margin-bottom: 1.5rem;
  }
  .reviews-page .search-bar .form-control {
    border-radius: 50px 0 0 50px;
    padding-right: 3rem;
  }
  .reviews-page .search-bar .btn-search {
    border-radius: 0 50px 50px 0;
  }
  .reviews-page .card {
    background: var(--bg-card);
    border: none;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    transition: transform var(--transition), box-shadow var(--transition);
  }
  .reviews-page .card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
  }
  .reviews-page .card-header {
    background: transparent;
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 1rem;
  }
  .reviews-page .table thead th {
    background: var(--bg-light);
    border-bottom: none;
  }
  .reviews-page .table tbody tr {
    background: var(--bg-card);
    transition: background var(--transition);
  }
  .reviews-page .table-hover tbody tr:hover {
    background: var(--bg-light);
  }
  .reviews-page .table td, 
  .reviews-page .table th {
    vertical-align: middle;
    padding: 0.75rem 1rem;
  }
  .reviews-page .star-rating i {
    font-size: 1rem;
    transition: transform var(--transition);
  }
  .reviews-page .star-rating i:hover {
    transform: scale(1.2);
  }
  /* Tambahan: highlight baris yang dilaporkan */
  .row-reported {
    border: 2.5px solid #e53935 !important;
    background: #fff0f3 !important;
    box-shadow: 0 2px 12px #e5393522;
    /* biar lebih menonjol */
  }
</style>

<div class="container-fluid reviews-page">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Daftar Semua Ulasan</h4>
    {{-- Search Form --}}
    <form class="d-flex search-bar" method="GET" action="{{ route('admin.reviews.index') }}">
      <input 
        name="search" 
        type="search" 
        class="form-control" 
        placeholder="Cari property atau pengulas…" 
        value="{{ request('search') }}"
      >
      <button class="btn btn-primary btn-search" type="submit">
        <i class="bi bi-search"></i>
      </button>
    </form>
  </div>

  <div class="card mb-5">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th style="width:50px;">#</th>
              <th>Property</th>
              <th>Pengulas</th>
              <th>Rating</th>
              <th>Komentar</th>
              <th>Alasan Report</th>
              <th>Status</th>
              <th>Tanggal</th>
              <th style="width:120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reviews as $idx => $rev)
              @php
                $isReported = isset($rev['is_reported']) && $rev['is_reported'] == 1;
              @endphp
              <tr class="{{ $isReported ? 'row-reported' : '' }}" @if(!empty($rev['is_hidden']) && !$isReported) style="opacity:.5;" @endif>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $rev['name'] }}</td>
                <td>{{ $rev['reviewer_name'] }}</td>
                <td class="star-rating">
                  @for($i=1; $i<=5; $i++)
                    @if($i <= $rev['rating'])
                      <i class="bi bi-star-fill text-warning"></i>
                    @else
                      <i class="bi bi-star text-secondary"></i>
                    @endif
                  @endfor
                </td>
                <td>{{ $rev['comment'] }}</td>
                <td>
                  @if($isReported)
                    <span class="text-danger">{{ $rev['report_reason'] ?? '-' }}</span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  @if($isReported)
                    <span class="badge bg-warning text-dark">Dilaporkan</span>
                  @else
                    <span class="badge bg-success">Normal</span>
                  @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($rev['created_at'])->translatedFormat('d M Y, H:i') }}</td>
                <td>
                  <div class="d-flex gap-2">
                    @if(isset($rev['is_hidden']) && $rev['is_hidden'] == 1)
                      <form action="{{ route('admin.reviews.unhide', $rev['id'] ?? $rev['review_id']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" title="Tampilkan kembali">
                          <i class="bi bi-eye"></i> Unhide
                        </button>
                      </form>
                    @else
                      <form action="{{ route('admin.reviews.hide', $rev['id'] ?? $rev['review_id']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm" title="Sembunyikan">
                          <i class="bi bi-eye-slash"></i> Hide
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center text-secondary py-4">
                  Belum ada ulasan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
