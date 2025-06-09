@extends('layouts.owner.index-owner')
@section('content')

<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-circle bg-primary bg-opacity-10">
                <i class="bi bi-chat-left-text text-primary fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">Ulasan Properti</h3>
        </div>
        
        @if(count($reviews) > 0)
            <div class="badge bg-gradient-primary d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2">
                <i class="bi bi-star-fill text-warning"></i>
                <span class="fs-6 fw-medium">{{ count($reviews) }} Ulasan</span>
            </div>
        @endif
    </div>

    @if(count($reviews) === 0)
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light">
            <div class="mb-4">
                <i class="bi bi-chat-dots display-4 text-muted opacity-50"></i>
            </div>
            <h5 class="fw-semibold mb-2">Belum Ada Ulasan</h5>
            <p class="text-muted mb-0">Ulasan akan muncul di sini ketika pengunjung memberikan penilaian untuk properti Anda</p>
        </div>
    @else
        <div class="position-relative mb-4">
            <!-- Search Input -->
            <div class="position-relative">
                <input 
                    type="text" 
                    id="searchInput" 
                    class="form-control rounded-pill ps-5" 
                    placeholder="Cari Property"
                    aria-label="Search reviews"
                >
                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                    <i class="bi bi-search"></i>
                </span>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded-4 border-0 overflow-hidden">
            <table class="table table-hover align-middle mb-0" id="reviewsTable">
                <thead class="bg-gradient-primary text-white">
                    <tr>
                        <th class="text-nowrap py-3 px-4">Properti</th>
                        <th class="text-nowrap py-3 px-4">Pengulas</th>
                        <th class="text-nowrap py-3 px-4">Rating</th>
                        <th class="py-3 px-4">Ulasan</th>
                        <th class="text-nowrap py-3 px-4">Tanggal</th>
                        <th class="text-nowrap py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($reviews as $review)
                        <tr class="align-middle hover-row"
                            @if(!empty($review->is_hidden) && $review->is_hidden == 1)
                                style="opacity:0.55;"
                            @endif
                        >
                            <td class="fw-semibold text-nowrap py-3 px-4">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-building text-primary"></i>
                                    <span>{{ $review->property_name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="text-nowrap py-3 px-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-secondary bg-opacity-20 text-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <span>{{ $review->reviewer_name ?? 'Anonim' }}</span>
                                </div>
                            </td>
                            <td class="text-nowrap py-3 px-4">
                                <div class="d-flex align-items-center">
                                    @php
                                        $rating = $review->rating ?? 0;
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp

                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <i class="bi bi-star-fill text-warning me-1 fs-5"></i>
                                    @endfor
                                    @if ($halfStar)
                                        <i class="bi bi-star-half text-warning me-1 fs-5"></i>
                                    @endif
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <i class="bi bi-star text-muted me-1 fs-5"></i>
                                    @endfor

                                    <strong class="ms-2 text-primary">{{ number_format($rating, 1) }}</strong>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="mb-0 text-truncate line-clamp-2" style="max-width: 400px;" title="{{ $review->comment }}">
                                    {{ $review->comment ?? '-' }}
                                </p>
                            </td>
                            <td class="text-nowrap py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-2 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="text-nowrap py-3 px-4">
                                @if(!empty($review->is_hidden) && $review->is_hidden == 1)
                                    <span class="badge bg-secondary text-light">Disembunyikan oleh Admin</span>
                                @elseif(empty($review->is_reported) || $review->is_reported == 0)
                                    <!-- Tombol Laporkan (trigger modal) -->
                                    <button 
                                        class="btn btn-outline-danger btn-sm"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reportModal"
                                        data-review-id="{{ $review->review_id }}"
                                        data-property="{{ $review->property_name }}"
                                    >
                                        <i class="bi bi-flag"></i> Laporkan
                                    </button>
                                @else
                                    <span class="badge bg-warning text-dark">Telah dilaporkan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal Laporkan Review -->
        <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('owner.reviews.report') }}">
                    @csrf
                    <input type="hidden" name="review_id" id="reportReviewId">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reportModalLabel">Laporkan Ulasan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <strong>Properti:</strong> <span id="modalPropertyName"></span>
                            </div>
                            <div class="mb-3">
                                <label for="report_reason" class="form-label">Alasan laporan</label>
                                <textarea class="form-control" name="report_reason" id="report_reason" rows="3" required placeholder="Tuliskan alasan laporan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Kirim Laporan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #289A84, #152C5B);
    }

    .table thead th {
        font-weight: 700;
        color: #ffffff;
        border: none;
        vertical-align: middle;
    }

    .table tbody tr.hover-row:hover {
        background-color: #f8f9fa;
        transform: translateX(2px);
        transition: all 0.2s ease;
    }

    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .rounded-4 {
        border-radius: 1.5rem;
    }

    .table-responsive {
        border-radius: 1.5rem;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table > :not(caption) > * > * {
        padding: 0.75rem 1rem;
    }

    #searchInput {
        background: #f8f9fa;
        border: 1px solid #ced4da;
        box-shadow: none;
    }

    #searchInput:focus {
        border-color: #adb5bd;
        box-shadow: 0 0 0 0.25rem rgba(74, 86, 112, 0.25);
    }

    .position-relative > .form-control {
        padding-left: 48px !important;
    }

    @media (max-width: 768px) {
        .table thead {
            display: none;
        }
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #dee2e6;
        }
        .table tbody td:last-child {
            border-bottom: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Script pencarian tetap
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('reviewsTable');
        const rows = table.tBodies[0].rows;

        searchInput.addEventListener('input', function () {
            const filter = this.value.toLowerCase().trim();
            
            for (let row of rows) {
                const property = row.cells[0].textContent.toLowerCase();
                const reviewer = row.cells[1].textContent.toLowerCase();
                const comment = row.cells[3].textContent.toLowerCase();

                if (
                    property.includes(filter) ||
                    reviewer.includes(filter) ||
                    comment.includes(filter)
                ) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Autofill modal report
        var reportModal = document.getElementById('reportModal');
        if(reportModal) {
            reportModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var reviewId = button.getAttribute('data-review-id');
                var property = button.getAttribute('data-property');
                document.getElementById('reportReviewId').value = reviewId;
                document.getElementById('modalPropertyName').innerText = property;
                document.getElementById('report_reason').value = '';
            });
        }
    });
</script>

@endsection
