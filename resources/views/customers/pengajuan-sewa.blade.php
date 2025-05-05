@extends('layouts.index-welcome')
@section('content')
<div class="container mt-4">
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
    </style>    
    <section class="container">
        <div class="container">
            <h4 class="mb-4">Pengajuan Sewa</h4>

            <div class="stepper d-flex align-items-center justify-content-center mb-4">
                <div class="circle active">1</div>
                <div class="line active-line"></div>
                <div class="circle">2</div>
                <div class="line"></div>
                <div class="circle">3</div>
            </div>        

            <div class="row">
                <div class="col-md-12">
                    <h5>Informasi penyewa</h5>
                    <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="namaPenyewa" class="form-label"><strong>Nama penyewa</strong></label>
                            <input type="text" class="form-control" id="namaPenyewa" name="nama_penyewa" placeholder="Masukkan nama lengkap" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="inputPhoneNumber" class="form-label"><strong>Nomor Telepon</strong></label>
                            <input type="tel" class="form-control" id="inputPhoneNumber" name="telepon" required>
                        </div>                                

                        <div class="mb-3">
                            <label for="pekerjaan" class="form-label"><strong>Pekerjaan</strong></label>
                            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Masukkan pekerjaan" value="Mahasiswa" required>
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
                        <div id="formTamuLain" style="display: none;">
                            <div class="mb-3">
                                <label for="namaTamuLain" class="form-label"><strong>Nama Lengkap Tamu</strong></label>
                                <input type="text" class="form-control" id="namaTamuLain" name="nama_tamu" placeholder="Masukkan nama lengkap tamu">
                                <small class="text-muted">Masukkan nama orang yang akan menginap.</small>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex align-items-center mb-3" style="max-width: 200px;">
                            <button type="button" class="btn btn-outline-dark btn-sm" id="kurangiPenyewa">–</button>
                            <input type="number" id="jumlahPenyewa" name="jumlah_penyewa" class="form-control text-center mx-2" value="1" min="1" style="width: 80px;" readonly>
                            <button type="button" class="btn btn-outline-dark btn-sm" id="tambahPenyewa">+</button>
                        </div>
                        
                        <h5 class="mt-4">Dokumen persyaratan</h5>
                        <p class="text-muted">
                            Dokumen diperlukan pemilik property untuk verifikasi atau lapor ke RT/RW setempat.
                        </p>

                        <div class="mb-3 border border-2 border-secondary border-dashed rounded p-4 text-center bg-light">
                            <div class="mb-2">
                                <i class="bi bi-upload" style="font-size: 2rem;"></i>
                            </div>
                            <label class="btn btn-outline-secondary btn-sm">
                                Upload di sini
                                <input type="file" name="dokumen_ktp" accept="image/*,.pdf" required hidden>
                            </label>
                            <div class="mt-2 text-muted">Foto KTP (JPEG/PNG/PDF)</div>
                            <small id="fileHelp" class="form-text text-muted">Maksimal 2MB</small>
                        </div>

                        <div class="alert alert-info">
                            Pada saat masuk property, mohon siapkan kartu identitas asli untuk verifikasi.
                        </div>

                        <h5 class="mt-4">Pilih Kamar</h5>
                        <p class="text-muted">Kamu bisa memilih lebih dari satu kamar jika diperlukan.</p>

                        @foreach ($rooms as $room)
                        <div class="mb-3 p-3 border rounded position-relative room-card d-flex justify-content-between align-items-center">
                            @if($room->stok > 0)
                                <span class="badge bg-success position-absolute quantity-badge">{{ $room->stok }} Tersedia</span>
                            @else
                                <span class="badge bg-danger position-absolute quantity-badge">Habis</span>
                            @endif

                            <div class="d-flex align-items-center" style="gap: 15px;">
                                <img src="{{ asset('storage/' . $room->image_path) }}" class="rounded" style="width: 200px; height: 140px; object-fit: cover;" alt="{{ $room->room_type }}">
                                <div>
                                    <div class="fw-semibold">{{ $room->room_type }}</div>
                                    <div class="small text-muted">Harga: Rp{{ number_format($room->latest_price, 0, ',', '.') }}/bulan</div>
                                    {{-- <div class="small text-muted mt-1">{{ $room->description }}</div> --}}
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-minus-{{ $room->room_id }}" onclick="ubahJumlah('{{ $room->room_id }}', -1, {{ $room->stok }})" disabled>–</button>
                                <span id="jumlah-tampil-{{ $room->room_id }}" class="mx-3 fw-semibold">0</span>
                                <input type="hidden" name="jumlah_kamar[{{ $room->room_id }}]" id="jumlah_kamar_{{ $room->room_id }}" value="0">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-plus-{{ $room->room_id }}" onclick="ubahJumlah('{{ $room->room_id }}', 1, {{ $room->stok }})">+</button>
                            </div>
                        </div>
                        @endforeach

                        <div class="my-5" style="max-width: 700px;">
                            <h5 class="fw-semibold mb-1">Catatan tambahan</h5>
                            <label class="form-label text-muted mb-2">Penjelasan terkait pengajuan sewa dan transaksimu</label>
                            <textarea class="form-control" rows="3" maxlength="200" placeholder="Misal: saya membawa barang elektronik berupa TV" style="resize: none;" oninput="updateCharCount()" id="catatanTambahan" name="catatan_tambahan"></textarea>
                            <div class="text-end text-muted" style="font-size: 0.875rem;"><span id="charCount">0</span>/200</div>

                            <hr class="my-4">

                            <h5 class="fw-semibold mb-2">Durasi Menginap</h5>

                            <div class="d-flex align-items-center gap-2 mb-2" style="max-width: 300px;">
                                <button type="button" class="btn btn-outline-dark btn-sm" onclick="ubahDurasi(-1)">–</button>
                                <input type="text" class="form-control text-center" id="jumlahDurasi" name="durasi" value="3" readonly>
                                <button type="button" class="btn btn-outline-dark btn-sm" onclick="ubahDurasi(1)">+</button>
                                <select class="form-select form-select-sm" id="satuanDurasi" name="satuan_durasi">
                                    <option value="Hari">Hari</option>
                                    <option value="Minggu">Minggu</option>
                                    <option value="Bulan" selected>Bulan</option>
                                </select>
                            </div>

                            <small class="text-muted mb-4 d-block">Durasi sewa dapat disesuaikan di kemudian hari</small>

                            <h5 class="fw-semibold mb-2">Tanggal mulai </h5>
                            <div class="mb-4 d-flex justify-content-between align-items-center" style="max-width: 300px;">
                                <input type="date" class="form-control me-3" id="tanggalMulai" name="tanggal_mulai" required>
                                <a href="#" class="text-decoration-none" onclick="resetTanggal(event)">Reset</a>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4">Ajukan Sewa</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Tenant count controls
    document.getElementById('kurangiPenyewa').addEventListener('click', function() {
        const jumlahInput = document.getElementById('jumlahPenyewa');
        let currentValue = parseInt(jumlahInput.value);
        if (currentValue > 1) {
            jumlahInput.value = currentValue - 1;
        }
    });

    document.getElementById('tambahPenyewa').addEventListener('click', function() {
        const jumlahInput = document.getElementById('jumlahPenyewa');
        let currentValue = parseInt(jumlahInput.value);
        jumlahInput.value = currentValue + 1;
    });

    // Guest type toggle
    document.getElementById('sayaTamu').addEventListener('change', function() {
        document.getElementById('formTamuLain').style.display = 'none';
    });

    document.getElementById('bookingOrangLain').addEventListener('change', function() {
        document.getElementById('formTamuLain').style.display = 'block';
    });

    // Room quantity adjustment
    function ubahJumlah(id, delta, maxQuantity) {
        const input = document.getElementById('jumlah_kamar_' + id);
        const tampil = document.getElementById('jumlah-tampil-' + id);
        const btnMinus = document.getElementById('btn-minus-' + id);
        const btnPlus = document.getElementById('btn-plus-' + id);

        let value = parseInt(input.value) || 0;
        value = value + delta;

        if (value < 0) value = 0;
        if (value > maxQuantity) value = maxQuantity;

        input.value = value;
        tampil.innerText = value;
        btnMinus.disabled = (value <= 0);
        btnPlus.disabled = (value >= maxQuantity);
    }

    // Duration controls
    let jumlah = 3;
    const jumlahInput = document.getElementById("jumlahDurasi");

    function ubahDurasi(nilai) {
        jumlah += nilai;
        if (jumlah < 1) jumlah = 1;
        jumlahInput.value = jumlah;
    }

    // Date controls
    function resetTanggal(event) {
        event.preventDefault();
        document.getElementById("tanggalMulai").value = "";
    }

    // Character counter
    function updateCharCount() {
        const textarea = document.getElementById('catatanTambahan');
        const counter = document.getElementById('charCount');
        counter.textContent = textarea.value.length;
    }

    // Set default date to today
    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date();
        const dd = String(today.getDate()).padStart(2, '0');
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const yyyy = today.getFullYear();
        document.getElementById("tanggalMulai").min = yyyy + '-' + mm + '-' + dd;
        document.getElementById("tanggalMulai").value = yyyy + '-' + mm + '-' + dd;
        
        // Initialize character counter
        updateCharCount();
    });

    // File size validation
    document.querySelector('input[name="dokumen_ktp"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file.size > 2097152) { // 2MB in bytes
            alert('File terlalu besar. Maksimal 2MB');
            e.target.value = '';
        }
    });
</script>
@endsection
