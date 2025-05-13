@extends('layouts.index-welcome')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4" style="background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="card-body p-5">
                    <h3 class="mb-4 text-center fw-bold text-primary">Beri Ulasan</h3>
                    
                    <form action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <!-- Rating dengan Bintang -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Rating</label>
                            <div class="star-rating d-flex gap-2 fs-3" id="starRating">
                                <i class="bi bi-star" data-value="1"></i>
                                <i class="bi bi-star" data-value="2"></i>
                                <i class="bi bi-star" data-value="3"></i>
                                <i class="bi bi-star" data-value="4"></i>
                                <i class="bi bi-star" data-value="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="ratingValue" required>
                            <small class="text-muted">Klik bintang untuk memberikan rating</small>
                        </div>

                        <!-- Komentar -->
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-medium">Komentar (Opsional)</label>
                            <textarea name="comment" id="comment" class="form-control rounded-3" rows="5" placeholder="Tulis ulasan Anda..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg btn-gradient rounded-pill py-2 fw-semibold shadow-sm">
                                Kirim Ulasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome untuk ikon bintang -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">

<style>
    .star-rating {
        cursor: pointer;
        color: #ccc;
    }

    .star-rating .bi-star:hover,
    .star-rating .bi-star.active {
        color: gold;
        transform: scale(1.2);
    }

    .star-rating .bi-star {
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #6c757d;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
    }

    .btn-gradient {
        background: linear-gradient(45deg, #007bff, #00c6ff);
        color: white;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.star-rating .bi-star');
        const ratingInput = document.getElementById('ratingValue');

        stars.forEach(star => {
            star.addEventListener('click', function () {
                const value = parseInt(this.getAttribute('data-value'));

                // Hapus semua kelas 'active'
                stars.forEach(s => s.classList.remove('active'));

                // Tambahkan kelas 'active' ke bintang yang dipilih dan sebelumnya
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('active');
                }

                // Update nilai rating di input hidden
                ratingInput.value = value;
            });

            // Hover efek (opsional)
            star.addEventListener('mouseover', function () {
                const hoverValue = parseInt(this.getAttribute('data-value'));
                stars.forEach((s, index) => {
                    s.classList.toggle('active', index < hoverValue);
                });
            });

            star.addEventListener('mouseout', function () {
                const currentValue = parseInt(ratingInput.value);
                stars.forEach((s, index) => {
                    s.classList.toggle('active', index < currentValue);
                });
            });
        });
    });
</script>

@endsection