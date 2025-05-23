@extends('layouts.index-welcome')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    :root {
      --primary: #152C5B;
      --success: #289A84;
      --light: #F8F9FA;
      --dark: #343A40;
      --radius: 12px;
      --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
  
    /* Search Form */
    .search-container {
      background: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow-md);
      padding: 2rem;
      margin-top: -3rem;
    }
  
    .search-container h4 {
      color: var(--primary);
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
  
    .search-row {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      margin-top: 1rem;
    }
  
    .search-field {
      flex: 1;
      min-width: 200px;
    }
  
    .search-field label {
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 0.5rem;
    }
  
    .search-input, 
    .price-input, 
    select.search-input {
      width: 100%;
      padding: 0.75rem 1rem;
      border-radius: var(--radius);
      border: 1px solid #ced4da;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }
  
    .search-input:focus, 
    .price-input:focus, 
    select.search-input:focus {
      border-color: var(--success);
      box-shadow: 0 0 0 0.2rem rgba(40, 154, 132, 0.2);
      outline: none;
    }
  
    .price-range {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
  
    .price-range span {
      color: #666;
    }
  
    .reset-button {
      margin-top: 1rem;
      display: flex;
      justify-content: flex-end;
    }
  
    .reset-btn {
      background: linear-gradient(45deg, var(--success), #21c493);
      color: white;
      border: none;
      padding: 0.6rem 1.5rem;
      font-weight: 600;
      border-radius: 50px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
  
    .reset-btn:hover {
      background: linear-gradient(45deg, #21c493, #1eaa8a);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(40, 154, 132, 0.3);
    }
  
    /* Property Cards */
    .property-card {
      border-radius: var(--radius);
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: var(--shadow-sm);
      border: none;
    }
  
    .property-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-md);
    }
  
    .property-card .card-img-top {
      transition: transform 0.4s ease;
    }
  
    .property-card:hover .card-img-top {
      transform: scale(1.05);
    }
  
    .type-badge {
      background: linear-gradient(45deg, var(--success), #21c493);
      color: white;
      font-size: 0.75rem;
      padding: 4px 10px;
      border-radius: 12px;
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 10;
      font-weight: 500;
    }
  
    .card-title {
      color: var(--primary);
      font-weight: 700;
      font-size: 1.1rem;
    }
  
    .text-muted {
      color: #666 !important;
    }
  
    /* Fade-in Animation */
    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s ease, transform 0.8s ease;
    }
  
    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }
  
    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .search-row {
        flex-direction: column;
      }
  
      .price-range {
        flex-direction: column;
        align-items: flex-start;
      }
  
      .price-range span {
        display: none;
      }
    }
</style>

<!-- Search Form Section -->
<section class="container mt-5">
    <div class="search-container">
      <h4 class="fw-bold mb-3">
        <i class="fas fa-home me-2 text-success"></i> Cari Homestay Murah
      </h4>
  
      <!-- Form pencarian -->
      <form method="GET" action="{{ route('search_homestay') }}">
        <div class="search-field">
          <label>Masukkan Kata Kunci</label>
          <div class="position-relative">
            <input type="text" 
                   name="keyword"
                   class="form-control pe-5" 
                   value="{{ request('keyword') }}" 
                   placeholder="Masukkan nama Homestay">
            <i class="fas fa-search position-absolute end-0 top-50 translate-middle-y me-3 text-secondary"></i>
          </div>
        </div>
  
        <div class="search-row">
          <div class="search-field">
            <label>Kota & Area</label>
            <select name="city_id" class="search-input">
              <option value="">Semua Kota</option>
              @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                  {{ $city->city_name }}
                </option>
              @endforeach
            </select>
          </div>
  
          <div class="search-field">
            <label>Urutkan</label>
            <select name="order_by" class="search-input">
              <option value="acak" {{ request('order_by') == 'acak' ? 'selected' : '' }}>Acak</option>
              <option value="termurah" {{ request('order_by') == 'termurah' ? 'selected' : '' }}>Harga Termurah</option>
              <option value="termahal" {{ request('order_by') == 'termahal' ? 'selected' : '' }}>Harga Termahal</option>
            </select>
          </div>
  
          <div class="search-field">
  <label>Harga</label>
  <div class="price-range">
    <input 
      type="text" 
      name="price_min" 
      class="price-input" 
      value="{{ request('price_min') ? 'Rp ' . number_format(request('price_min'), 0, ',', '.') : '' }}" 
      placeholder="Rp 0" 
      min="0" 
      oninput="formatPrice(this)"
    >
    <span>-</span>
    <input 
      type="text" 
      name="price_max" 
      class="price-input" 
      value="{{ request('price_max') ? 'Rp ' . number_format(request('price_max'), 0, ',', '.') : '' }}" 
      placeholder="Rp 10.000.000" 
      min="0" 
      oninput="formatPrice(this)"
    >
  </div>
</div>
        </div>
  
        <!-- Tombol submit -->
        <div class="reset-button">
          <button type="submit" class="reset-btn">Cari</button>
        </div>
      </form>
    </div>
</section>
  
  <!-- Properties Section -->
  <section class="container mt-5">
    <h3 class="fw-bold mb-4">Daftar Homestay</h3>
    
    <div class="row row-cols-1 row-cols-md-4 g-4">
        @foreach ($properties as $property)
          <div class="col fade-in">
            <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
              <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden property-card position-relative transition-card">
                <!-- Badge Tipe Properti -->
                <span class="position-absolute top-0 start-0 m-2 px-3 py-1 bg-success text-white rounded-pill small shadow-sm">
                  {{ $property->property_type }}
                </span>
                <!-- Gambar Properti -->
                <div class="overflow-hidden" style="aspect-ratio: 3/2;">
                  <img src="{{ asset('storage/' . $property->image) }}"
                      class="card-img-top object-fit-cover"
                      alt="{{ $property->property_name }}">
                </div>
                
                <div class="card-body d-flex flex-column justify-content-between">
                  <div>
                    <h6 class="card-title mb-1 fw-bold text-dark">{{ $property->property_name }}</h6>
                    <small class="text-muted mb-2">{{ $property->subdistrict }}, Surabaya</small>
                    
                    <!-- Rating Section -->
                    <div class="d-flex align-items-center mt-2">
                      <div class="me-2">
                        @for($i=1; $i <= 5; $i++)
                          <i class="fas fa-star "></i>
                        @endfor
                      </div>
                      <span class="text-muted small">rb Ulasan</span>
                    </div>
                  </div>
  
                  <div>
                    <p class="mb-1">
                      <span class="text-danger fw-bold fs-5">
                        IDR {{ number_format($property->min_price) }}
                      </span>
                    </p>
                  </div>
  
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div> 
  </section>
  
  <!-- Fade-in Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const fadeElements = document.querySelectorAll('.fade-in');
      
      const checkFade = () => {
        const triggerBottom = window.innerHeight / 5 * 4;
        
        fadeElements.forEach(element => {
          const boxTop = element.getBoundingClientRect().top;
          
          if (boxTop < triggerBottom) {
            element.classList.add('visible');
          } else {
            element.classList.remove('visible');
          }
        });
      };
      
      window.addEventListener('scroll', checkFade);
      checkFade();
    });
  </script>

  <script>
  // Fungsi untuk format harga dengan pemisah ribuan
  function formatPrice(input) {
    let value = input.value.replace(/\D/g, ''); // Menghapus karakter non-digit
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Menambahkan pemisah ribuan
    input.value = 'Rp ' + value; // Menambahkan "Rp" di depan nilai
  }
</script>


<script>
document.querySelector('form').addEventListener('submit', function(e) {
  const minInput = document.querySelector('input[name="price_min"]');
  const maxInput = document.querySelector('input[name="price_max"]');

  // Hilangkan karakter non-digit agar MySQL menerima sebagai angka valid
  if(minInput.value){
    minInput.value = minInput.value.replace(/[^\d]/g, '');
  }
  if(maxInput.value){
    maxInput.value = maxInput.value.replace(/[^\d]/g, '');
  }
});
</script>


@endsection
