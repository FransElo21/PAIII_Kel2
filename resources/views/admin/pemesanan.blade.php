@extends('layouts.admin.index-admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@php
    $successMsg = session()->pull('success');
    $errorMsg   = session()->pull('error');
@endphp

<script>
    @if($successMsg)
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ $successMsg }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if($errorMsg)
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '{{ $errorMsg }}',
            showConfirmButton: false,
            timer: 2500
        });
    @endif
</script>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <h3 class="mb-0 fw-bold">Riwayat Pemesanan</h3>
        <form class="d-flex align-items-center" method="GET" action="" style="max-width: 330px; width:100%;">
            <input name="search"
                   value="{{ $search ?? '' }}"
                   type="search"
                   class="form-control form-control-sm rounded-pill me-2"
                   placeholder="Cari properti/nama/email..."
                   style="max-width: 170px;">
            <button class="btn btn-primary btn-sm rounded-pill px-3" type="submit">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>
    </div>

    <!-- Tabel Booking -->
    <div class="card mt-4 shadow-sm rounded-4">
        <div class="card-body p-3">
            <div class="table-responsive white-space-nowrap">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Property</th>
                            <th>Nama Tamu</th>
                            <th>Email</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $idx => $b)
                        <tr>
                            <td>{{ $bookings->firstItem() + $idx }}</td>
                            <td class="fw-semibold">{{ $b->property_name }}</td>
                            <td>{{ $b->guest_name }}</td>
                            <td><span class="badge bg-secondary bg-opacity-25 text-dark">{{ $b->email }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($b->check_in)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($b->check_out)->format('d M Y') }}</td>
                            <td class="fw-bold text-primary">{{ number_format($b->total_price,0,',','.') }}</td>
                            <td>
                                @php
                                    $statusClass = match(strtolower($b->status)) {
                                        'dibatalkan'   => 'bg-danger',
                                        'belum bayar'  => 'bg-warning text-dark',
                                        'selesai', 'berhasil' => 'bg-primary',
                                        'kadaluwarsa'  => 'bg-secondary',
                                        default        => 'bg-success',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($b->created_at)->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <button class="btn btn-outline-primary btn-sm rounded-circle"
                                    data-id="{{ $b->id }}"
                                    onclick="showDetail({{ $b->id }})"
                                    title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Tidak ada data pemesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $bookings->withQueryString()->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Booking -->
<div class="modal fade" id="modalBookingDetail" tabindex="-1" aria-labelledby="modalBookingDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalBookingDetailLabel">Detail Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detail-content">
                    <div class="text-center my-5">
                        <div class="spinner-border"></div>
                        <p>Memuat detail ...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .white-space-nowrap { white-space: nowrap; }
    .card { border-radius: 1.5rem; }
    .modal-content { border-radius: 1.25rem; }
    .table thead th { font-weight: 600; letter-spacing: 0.02em; }
    .badge { font-size: 0.95em; }
    .rounded-circle { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; }
    .btn-sm { padding: .35rem .6rem; }
    .table > :not(caption) > * > * { vertical-align: middle; }
    @media (max-width: 500px) {
        form.d-flex input[name="search"] {
            max-width: 110px !important;
            font-size: .90rem;
        }
    }
</style>

<script>
function showDetail(id) {
    let modal = new bootstrap.Modal(document.getElementById('modalBookingDetail'));
    document.getElementById('detail-content').innerHTML = `<div class="text-center my-5"><div class="spinner-border"></div><p>Memuat detail ...</p></div>`;
    modal.show();

    fetch(`/admin/bookings/${id}/detail`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                document.getElementById('detail-content').innerHTML = `<div class="alert alert-danger">${res.message || 'Gagal memuat detail.'}</div>`;
                return;
            }
            let total = 0;
            let html = `
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Kamar</th>
                                <th>Tipe</th>
                                <th>Qty</th>
                                <th>Harga/Kamar</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            if(res.details.length === 0) {
                html += `<tr><td colspan="5" class="text-center text-muted">Tidak ada detail booking.</td></tr>`;
            } else {
                res.details.forEach(item => {
                    total += parseInt(item.subtotal);
                    html += `<tr>
                        <td>${item.room_name}</td>
                        <td>${item.room_type ?? '-'}</td>
                        <td>${item.quantity}</td>
                        <td>${parseInt(item.price_per_room).toLocaleString()}</td>
                        <td>${parseInt(item.subtotal).toLocaleString()}</td>
                    </tr>`;
                });
                html += `
                    <tr>
                        <th colspan="4" class="text-end">Total Harga</th>
                        <th>${total.toLocaleString()}</th>
                    </tr>
                `;
            }
            html += `</tbody></table></div>`;
            document.getElementById('detail-content').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('detail-content').innerHTML = `<div class="alert alert-danger">Gagal memuat detail booking.</div>`;
        });
}
</script>
@endsection
