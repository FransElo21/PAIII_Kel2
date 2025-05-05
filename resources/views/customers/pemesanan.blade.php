@extends('layouts.index-welcome')

@section('content')

<style>
    .step {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .step .circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        box-shadow: 0 0 0 4px #e7f1ff;
    }

    .step .line {
        flex-grow: 1;
        height: 3px;
        background-color: #dee2e6;
        margin: 0 10px;
        margin-top: 16px;
        border-radius: 10px;
    }

    .border-dashed {
        border-style: dashed !important;
    }

    .alert-soft {
        background-color: #f8f9fa;
        border-color: #d3dce6;
        color: #495057;
    }

    .card-custom {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
    }

    .btn-custom {
        border-radius: 50px;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
    }
    .stepper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #dcdcdc;
        color: #000;
        text-align: center;
        line-height: 40px;
        font-weight: bold;
        border: 1px solid #289A84;
    }

    .circle.active {
        background-color: #289A84;
        color: #fff;
    }

    .line {
        flex: 1;
        width: 300px;
        height: 3px;
        background-color: #dcdcdc;
    }

    .line.active-line {
        background-color: #289A84;
    }
    
    .room-card {
        transition: all 0.3s ease;
    }
    
    .room-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .quantity-badge {
        top: 8px;
        right: 8px;
        z-index: 10;
    }
    .breadcrumb {
              display: flex;
              flex-wrap: wrap;
              list-style: none;
            }
        
            .breadcrumb-item + .breadcrumb-item::before {
              content: "›";
              padding: 0 0.5rem;
              color: #6c757d;
            }
        
            .breadcrumb-item a {
              text-decoration: none;
              color: #6c757d;
            }
        
            .breadcrumb-item.active {
              color: #007bff;
              pointer-events: none;
            }
</style>

<div class="container">
    <!-- Bagian Utama -->
    <div class="container-fluid">

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Property</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $property->property_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pemesanan</li>
        </ol>

        <div class="stepper d-flex align-items-center justify-content-center mb-4">
            <div class="circle active">1</div>
            <div class="line active-line"></div>
            <div class="circle">2</div>
            <div class="line"></div>
            <div class="circle">3</div>
        </div>   

        <div class="row">
            <!-- Kolom Form Utama -->
            <div class="col-lg-8 col-md-12 order-1 order-lg-0">
                
                <h4 class="mb-4">Isi Data Diri untuk Pemesanan</h4>

                <div class="card rounded-5 mb-4">
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            @csrf

                            <!-- Hidden input untuk data kamar -->
                            <input type="hidden" name="rooms" value='{{ request("rooms") }}'>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control rounded-3" id="name" name="name" required style="border-radius: 1.5rem;" placeholder="Masukkan Nama Sesuai KTP">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">NIK</label>
                                <input type="text" class="form-control rounded-3" id="name" name="name" required style="border-radius: 1.5rem;" placeholder="Masukkan No NIK">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control rounded-3" id="email" name="email" required style="border-radius: 1.5rem;" placeholder="Masukkan Email">
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Siapa yang menginap?</strong></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="guest_option" id="sayaTamu" value="saya" checked>
                                    <label class="form-check-label" for="sayaTamu">Saya adalah tamu</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="guest_option" id="bookingOrangLain" value="lain">
                                    <label class="form-check-label" for="bookingOrangLain">Saya memesankan untuk orang lain</label>
                                </div>
                            </div>

                            <div id="guest_option" style="display: none;">
                                <div class="mb-3">
                                    <label for="namaTamuLain" class="form-label"><strong>Nama Lengkap Tamu</strong></label>
                                    <input type="text" class="form-control rounded-3" id="namaTamuLain" name="nama_tamu" placeholder="Masukkan Nama Sesuai KTP" style="border-radius: 0.5rem;">
                                </div>
                            </div>

                            <div class="alert alert-info">
                                Pada saat masuk property, mohon siapkan kartu identitas asli untuk verifikasi.
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Selected Rooms -->
                <h5 class="fw-bold mb-3">Kamar yang Dipilih</h5>
                @foreach ($selectedRooms as $room)
                <div class="card mb-2 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">{{ $room['room_id'] }} | {{ $room['quantity'] }} kamar</h6>
                        <small class="text-muted">Harga: Rp{{ number_format($room['price_per_room'], 0, ',', '.') }}</small>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success fs-6">Rp{{ number_format($room['subtotal'], 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach

                <!-- Tampilkan Total Harga -->
                <div class="alert alert-soft mt-4 p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Total</strong><br>
                        <span class="text-danger h4">
                            IDR {{ number_format(array_sum(array_column($selectedRooms, 'subtotal')), 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-custom px-4">Lanjutkan</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 position-sticky top-0 order-0 order-lg-1" style="height: 100vh; overflow-y: auto;">
                <div class="card mb-4 rounded-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $property->property_name }}</h5>
                        <hr class="my-3">
            
                        <!-- Carousel Gambar -->
                        <div id="propertyCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($images as $key => $image)
                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->images_path) }}" class="d-block w-100 rounded-3" alt="...">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
            
                        <!-- Lokasi Property -->
                        <div class="row g-0 mb-3">
                            <div class="col-md-12">
                                <p class="card-text text-muted small">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $property->subdistrict }}
                                </p>
                            </div>
                        </div>
            
                        <!-- Tanggal Menginap -->
                        <div class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card bg-white border rounded-3 shadow-sm p-3 text-center">
                                        <h6 class="text-muted mb-1">Check-In</h6>
                                        <h5 class="fw-bold">
                                            {{ \Carbon\Carbon::parse(request()->query('check_in'))->format('D, j M Y') }}
                                        </h5>
                                        <small class="text-muted">After 14:00</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-white border rounded-3 shadow-sm p-3 text-center">
                                        <h6 class="text-muted mb-1">Check-Out</h6>
                                        <h5 class="fw-bold">
                                            {{ \Carbon\Carbon::parse(request()->query('check_out'))->format('D, j M Y') }}
                                        </h5>
                                        <small class="text-muted">Before 12:00</small>
                                    </div>
                                </div>
                            </div>
            
                            {{-- <!-- Badge Durasi -->
                            <div class="text-center mt-2">
                                <span class="badge bg-primary text-white rounded-pill px-3 py-2">
                                    {{ $diffDays ?? 0 }} night{{ $diffDays != 1 ? 's' : '' }}
                                </span>
                            </div> --}}
                        </div>
            
                        <!-- Total Harga -->
                        <div class="alert alert-soft p-3 d-flex justify-content-between align-items-center rounded-3 bg-light-blue">
                            <div class="text-end">
                                <span class="text-danger h5 fw-bold">
                                    Rp{{ number_format(request()->query('total_price'), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
      const radioButtons = document.querySelectorAll('input[name="guest_option"]');
      const guestNameDiv = document.getElementById('guest_option');
  
      radioButtons.forEach(radio => {
        radio.addEventListener('change', function () {
          if (this.value === 'lain') {
            guestNameDiv.classList.remove('d-none');
          } else {
            guestNameDiv.classList.add('d-none');
          }
        });
      });
  
      // Fade-in Animation
      const fadeElements = document.querySelectorAll('.fade-in');
      fadeElements.forEach(element => {
        element.classList.add('visible');
      });
    });
  </script>