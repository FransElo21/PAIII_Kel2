<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $property->property_name }} - Hommie</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        h3, h4 {
            font-weight: 700;
        }

        .gambar_homestay {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .see-all-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 10;
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 10px;
            background-color: rgba(255,255,255,0.95);
            backdrop-filter: blur(3px);
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .price {
            font-size: 1.8rem;
            font-weight: 800;
            color: #198754;
        }

        .btn-action {
            width: 100%;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
        }

        ul.list-unstyled li {
            padding: 4px 0;
            font-size: 1rem;
        }

        #map {
            border-radius: 16px;
            border: 1px solid #dee2e6;
        }

        .section-title {
            margin-bottom: 1rem;
            border-bottom: 2px solid #198754;
            padding-bottom: 6px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="container py-5">
        {{-- Nama Properti --}}
        <h3 class="mb-1">{{ $property->property_name }}</h3>
        @if($locationData)
            <h5 class="text-muted">
                <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                {{ $locationData->prov_name }}, {{ $locationData->city_name }}
            </h5>
        @else
            <h5 class="text-muted">
                <i class="bi bi-geo-alt-fill me-1 text-muted"></i> Alamat belum tersedia
            </h5>
        @endif

        {{-- Gambar Properti --}}
        @php
            $imagesCollection = collect($images);
            $firstFiveImages = $imagesCollection->take(5);
        @endphp

        <div class="row g-3 mt-4 mb-5">
            <div class="col-md-6">
                @if(isset($firstFiveImages[0]))
                    <div class="overflow-hidden rounded-4" style="min-height: 400px;">
                        <img src="{{ asset('storage/' . $firstFiveImages[0]->images_path) }}" class="gambar_homestay" alt="Main Photo">
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="row g-3">
                    @foreach($firstFiveImages->slice(1)->values() as $index => $img)
                        <div class="col-6">
                            <div class="position-relative overflow-hidden rounded-4 shadow-sm" style="height: 190px;">
                                <img src="{{ asset('storage/' . $img->images_path) }}" class="img-fluid w-100 h-100 object-fit-cover rounded-4" alt="Image {{ $index + 2 }}">
                                @if($index === 3 && $imagesCollection->count() > 5)
                                    <button class="btn btn-light see-all-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#photoModal">
                                        <i class="bi bi-images me-1"></i> Semua Foto
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Konten Info --}}
        <div class="row g-4">
            <div class="col-md-8">
                <h4 class="section-title">Deskripsi Properti</h4>
                <p>{{ $property->description ?? 'Deskripsi belum ditambahkan.' }}</p>

                <h4 class="section-title">Fasilitas Umum</h4>
                @if(!empty($fasilitas))
                    <ul class="list-unstyled">
                        @foreach($fasilitas as $f)
                            <li><i class="bi bi-check-circle text-success me-1"></i> {{ $f->facility }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Fasilitas belum tersedia</p>
                @endif

                @if($property->latitude && $property->longitude)
                    <h4 class="section-title mt-4">Lokasi Properti</h4>
                    <div id="map" style="height: 400px;" class="mb-4 shadow-sm"></div>
                @endif
            </div>

            {{-- Sidebar Penyewaan --}}
            <div class="col-md-4">
                <div class="card p-4">
                    @php
                        $hargaSatuan = match($property->property_type_id) {
                            1 => '/ Malam',
                            2 => '/ Bulan',
                            default => '',
                        };
                    @endphp
                    <p class="price">
                        Rp{{ number_format($property->price, 0, ',', '.') }} <span class="text-dark fw-normal" style="font-size: 1rem;">{{ $hargaSatuan }}</span>
                    </p>

                    <form id="formSewa">
                        @if($property->property_type_id == 1)
                            {{-- Homestay --}}
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" id="startDate" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" id="endDate" class="form-control">
                            </div>
                        @elseif($property->property_type_id == 2)
                            {{-- Kost --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mulai Kos</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="start-kos" readonly value="{{ now()->format('d/m/Y') }}">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <select class="form-select" id="duration-kos">
                                        <option value="1">1 Bulan</option>
                                        <option value="3">3 Bulan</option>
                                        <option value="6">6 Bulan</option>
                                        <option value="12">1 Tahun</option>
                                    </select>
                                </div>
                            </div>

                            <div class="bg-light rounded p-3 mb-3">
                                <p class="fw-bold mb-2">Rincian Pembayaran</p>
                                <div class="d-flex justify-content-between"><span>Uang Muka (DP):</span><span id="dp">Rp0</span></div>
                                <div class="d-flex justify-content-between"><span>Pelunasan:</span><span id="pelunasan">Rp0</span></div>
                                <hr>
                                <div class="d-flex justify-content-between"><span>Total Penuh:</span><span id="full">Rp0</span></div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="pakaiDp">
                                <label class="form-check-label" for="pakaiDp">Bayar dengan DP</label>
                            </div>

                            <div class="mb-3">
                                <h6>Total Bayar Pertama: <strong id="pembayaranPertama">Rp0</strong></h6>
                            </div>
                        @endif

                        <div class="mb-3">
                            <p id="totalHarga" class="fw-bold text-dark"></p>
                        </div>

                        <button type="submit" class="btn btn-success btn-action">Sewa Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Foto --}}
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Semua Foto {{ $property->property_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($images as $img)
                            @if($img->images_path)
                                <div class="col-md-4 mb-3">
                                    <img src="{{ asset('storage/' . $img->images_path) }}" class="img-fluid rounded shadow-sm" alt="Foto Properti">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Flatpickr init
        flatpickr("#start-kos", {
            dateFormat: "d/m/Y",
            minDate: "today",
            defaultDate: "today",
        });

        // Map init
        document.addEventListener('DOMContentLoaded', () => {
            const lat = {{ $property->latitude ?? 0 }};
            const lng = {{ $property->longitude ?? 0 }};
            if (lat && lng) {
                const map = L.map('map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                L.marker([lat, lng]).addTo(map).bindPopup("{{ $property->property_name }}").openPopup();
            }
        });

        // Harga Homestay
        function hitungHomestay() {
            const start = new Date(document.getElementById('startDate').value);
            const end = new Date(document.getElementById('endDate').value);
            if (start && end && end > start) {
                const diff = (end - start) / (1000 * 3600 * 24);
                const total = diff * {{ $property->price }};
                document.getElementById('totalHarga').textContent = `Total untuk ${diff} malam: Rp ${total.toLocaleString('id-ID')}`;
            } else {
                document.getElementById('totalHarga').textContent = '';
            }
        }
        document.getElementById('startDate')?.addEventListener('change', hitungHomestay);
        document.getElementById('endDate')?.addEventListener('change', hitungHomestay);

        // Harga Kost
        const harga = {{ $property->price }};
        const diskon = 115000;
        function formatRupiah(n) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
        }

        function updateHarga() {
            const durasi = parseInt(document.getElementById("duration-kos").value);
            let total = harga * durasi;
            total = durasi === 1 ? total - diskon : total;
            const dp = Math.round(total * 0.3);
            const pelunasan = total - dp;

            document.getElementById("dp").innerText = formatRupiah(dp);
            document.getElementById("pelunasan").innerText = formatRupiah(pelunasan);
            document.getElementById("full").innerText = formatRupiah(total);
            document.getElementById("pembayaranPertama").innerText = formatRupiah(document.getElementById("pakaiDp").checked ? dp : total);
            document.getElementById("totalHarga").innerHTML = `<strong>Total: ${formatRupiah(total)} / ${durasi} bulan</strong>`;
        }

        document.getElementById("duration-kos")?.addEventListener("change", updateHarga);
        document.getElementById("pakaiDp")?.addEventListener("change", updateHarga);
        document.addEventListener("DOMContentLoaded", updateHarga);
    </script>
</body>
</html>
