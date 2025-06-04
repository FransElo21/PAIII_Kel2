@extends('layouts.owner.index-owner')
@section('content')

<style>
    /* Container styling */
    .container {
        padding: 1rem;
    }

    /* Card styling */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }

    /* Table styling */
    .table-container {
        overflow: hidden;
        border-radius: 15px;
    }
    
    .table {
        margin-bottom: 0;
        color: #2D3748;
    }
    
    .table thead {
        background: linear-gradient(110deg, #48BB78 0%, #38A169 100%);
        color: white;
    }
    
    .table th, .table td {
        text-align: center;
        vertical-align: middle !important;
        padding: 1.25rem 0.75rem;
        border-top: 1px solid #E2E8F0;
        border-bottom: 1px solid #E2E8F0;
    }
    
    /* Image styling */
    .table img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .table img:hover {
        transform: scale(1.1);
    }

    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        text-transform: capitalize;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .status-belum-dibayar   { background-color: #ffc107; color: #000; } /* kuning */
    .status-berhasil        { background-color: #28a745; color: #fff; } /* hijau */
    .status-dibatalkan      { background-color: #dc3545; color: #fff; } /* merah */
    .status-selesai         { background-color: #17a2b8; color: #fff; } /* biru */


    /* Action buttons */
    .action-buttons a {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 4px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .action-buttons a:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* Search & filter styling */
    .search-container input {
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        border: 2px solid #E2E8F0;
        transition: all 0.3s ease;
        width: 250px;
    }
    .search-container input:focus {
        outline: none;
        border-color: #48BB78;
        box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.2);
    }

    /* Filter buttons */
    .filter-btn {
        border-radius: 50px;
        padding: 0.6rem 1.4rem;
        font-weight: 500;
        transition: all 0.3s ease;
        margin: 0.3rem;
        border: none;
        background: #F7FAFC;
        color: #4A5568;
    }
    .filter-btn:hover {
        background: #48BB78;
        color: white;
    }
    .filter-btn.active {
        background: #2F855A;
        color: white;
        box-shadow: 0 4px 12px rgba(47, 133, 90, 0.3);
    }

    /* Pagination styling */
    .pagination {
        border-radius: 50px;
        overflow: hidden;
    }
    .pagination li a {
        border-radius: 50px;
        margin: 0 7px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    .pagination li.active a {
        background: #48BB78;
        color: white;
        box-shadow: 0 4px 10px rgba(72, 187, 120, 0.3);
    }
    .pagination li a:hover:not(.active) {
        background: #F0FFF4;
        color: #2F855A;
    }

    @media (max-width: 768px) {
        .search-container input {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        .filter-btn {
            font-size: 0.85rem;
        }
    }
</style>

<div class="container mb-5">

    {{-- Search + Filters --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="search-container mb-3 mb-md-0">
            <input 
                type="text" 
                id="searchInput" 
                class="form-control me-2" 
                placeholder="Cari Pesanan..."
            >
        </div>
        <div>
            @php 
              $statuses = [
                ''           => 'Semua', 
                'Belum Dibayar' => 'Belum Dibayar',
                'Berhasil'      => 'Berhasil',
                'Selesai'       => 'Selesai',
                'Dibatalkan'    => 'Dibatalkan',
              ]; 
            @endphp
            @foreach($statuses as $key => $label)
                <button 
                  type="button"
                  class="filter-btn {{ request('status') == $key ? 'active' : '' }}"
                  data-status="{{ $key }}"
                >
                  {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Tabel Bookings --}}
    <div class="row g-4">
        <div class="card mt-4">
            <div class="card-body">
                <div class="product-table">
                    <div class="table-responsive white-space-nowrap">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Properti</th>
                                    <th>Nama Penyewa</th>
                                    <th>Tanggal</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bookingTableBody">
                                @forelse ($bookings as $index => $booking)
                                    <tr data-status="{{ $booking->status }}">
                                        <td>
                                          {{ ($bookings->currentPage()-1)*$bookings->perPage() + $index + 1 }}
                                        </td>
                                        <td class="d-flex align-items-center">
                                            <img 
                                              src="{{ $booking->property_image 
                                                  ? asset('storage/'.$booking->property_image) 
                                                  : asset('assets/images/property.jpg') }}"
                                              alt="{{ $booking->property_name }}"
                                            >
                                            <div class="ms-3">
                                                <div class="fw-bold">{{ $booking->property_name }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $booking->guest_name }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($booking->check_in)->format('j M') }} – 
                                            {{ \Carbon\Carbon::parse($booking->check_out)->format('j M Y') }}
                                        </td>
                                        <td>Rp {{ number_format((int)$booking->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="status-badge status-{{ Str::slug(strtolower($booking->status)) }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                        <a 
                                            href="{{ route('owner.bookings.detail', $booking->booking_id) }}" 
                                            class="btn btn-sm btn-info rounded-circle" 
                                            title="Detail"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-receipt fs-4 text-muted"></i>
                                            <p class="mt-2 mb-0 text-muted">Belum ada transaksi.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {!! $bookings->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT: client-side search + status filter --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const tableRows   = document.querySelectorAll('#bookingTableBody tr');
    const filterBtns  = document.querySelectorAll('.filter-btn');

    // Live search by any text in the row
    searchInput.addEventListener('input', () => {
      const term = searchInput.value.toLowerCase().trim();
      tableRows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term)
                          ? ''
                          : 'none';
      });
    });

    // Status filter buttons
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        // toggle active class
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const status = btn.getAttribute('data-status');
        const term   = searchInput.value.toLowerCase().trim();

        tableRows.forEach(row => {
          const matchesStatus = !status || row.dataset.status === status;
          const matchesSearch = row.textContent.toLowerCase().includes(term);
          row.style.display = (matchesStatus && matchesSearch) ? '' : 'none';
        });
      });
    });
  });

  if (matchesStatus && matchesSearch) {
  row.closest('.card').classList.add('border', 'border-success');
} else {
  row.closest('.card').classList.remove('border', 'border-success');
}
</script>

@endsection
