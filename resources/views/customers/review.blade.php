@extends('layouts.index-welcome')
@section('content')

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card border-0 rounded-4 shadow-sm review-card">
        <div class="card-body p-5">

          <h3 class="text-center mb-4 fw-bold text-primary">Beri Ulasan</h3>

          <form action="{{ route('review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            <input type="hidden" name="rating" id="ratingValue" required>

            {{-- Star Rating --}}
            <div class="mb-4 text-center">
              <div class="star-rating d-inline-flex gap-2 fs-2" id="starRating">
                @for ($i = 1; $i <= 5; $i++)
                  <i class="bi bi-star" data-value="{{ $i }}"></i>
                @endfor
              </div>
              <div class="mt-2 text-muted small">Klik bintang untuk memberi nilai</div>
            </div>

            {{-- Comment --}}
            <div class="mb-4">
              <label for="comment" class="form-label fw-semibold">Komentar (Opsional)</label>
              <textarea name="comment" id="comment"
                        class="form-control rounded-3 shadow-none"
                        rows="4"
                        placeholder="Tulis ulasan Anda..."
              ></textarea>
            </div>

            {{-- Submit --}}
            <div class="d-grid">
              <button type="submit"
                      class="btn btn-primary btn-lg rounded-pill py-2 submit-btn">
                <i class="bi bi-chat-dots me-2"></i> Kirim Ulasan
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
  :root {
    --primary: #4e9af1;
    --shadow-light: 0 4px 20px rgba(0, 0, 0, 0.05);
    --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.1);
  }

  body {
    background: #f8f9fa;
  }

  .review-card {
    background: #ffffff;
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .review-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
  }

  .star-rating {
    cursor: pointer;
    color: #ddd;
  }
  .star-rating .bi-star,
  .star-rating .bi-star-fill {
    transition: color .15s ease, transform .15s ease;
  }
  .star-rating .active {
    color: gold;
    transform: scale(1.2);
  }

  .form-control {
    border: 1px solid #dee2e6;
    transition: border-color .2s ease, box-shadow .2s ease;
  }
  .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 .2rem rgba(78,154,241,0.25);
  }

  .submit-btn {
    background: linear-gradient(135deg, var(--primary) 0%, #66b8ff 100%);
    border: none;
    box-shadow: var(--shadow-light);
    transition: background .2s ease, transform .2s ease;
  }
  .submit-btn:hover {
    background: linear-gradient(135deg, #2179d6 0%, #4e9af1 100%);
    transform: translateY(-2px);
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const stars     = document.querySelectorAll('#starRating .bi-star');
  const input     = document.getElementById('ratingValue');
  let currentRating = 0;

  stars.forEach(star => {
    const val = +star.dataset.value;

    star.addEventListener('click', () => {
      currentRating = val;
      input.value   = val;

      // highlight up to this star
      stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= val));
    });

    star.addEventListener('mouseover', () => {
      stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= val));
    });

    star.addEventListener('mouseout', () => {
      stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= currentRating));
    });
  });
});
</script>

@endsection
