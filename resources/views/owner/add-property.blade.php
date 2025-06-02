@extends('layouts.owner.index-owner')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
:root { --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.05); }
.container-fluid { padding:; }
.card {
    border: none; border-radius: 1rem;
    box-shadow: var(--shadow-soft);
    transition: transform .3s ease, box-shadow .3s ease;
}
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
}
.form-control, .form-select, textarea {
    border-radius: 1rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    transition: box-shadow .3s, border-color .3s;
}
.form-control:focus, .form-select:focus, textarea:focus {
    box-shadow: 0 0 0 .25rem rgba(13,110,253,0.25);
    border-color: #0d6efd;
}
.breadcrumb-item + .breadcrumb-item::before { content: ">"; color: #adb5bd; }
.breadcrumb a { color: #0d6efd; transition: color .2s ease; }
.breadcrumb a:hover { color: #0a58ca; text-decoration: none; }
.facilities-container {
    border:1px solid #e9ecef; border-radius:1rem;
    padding:1rem; box-shadow:var(--shadow-soft); background:#fff;
}
.autocomplete-dropdown { position:absolute; width:100%; max-height:200px; overflow-y:auto; background:#fff; border:1px solid #ced4da; border-top:none; border-radius:0 0 .5rem .5rem; z-index:1000; display:none; }
.autocomplete-item { padding:8px 12px; cursor:pointer; }
.autocomplete-item:hover { background:#f8f9fa; }
.facility-tag { background-color:#f1f3f5; border-radius:2rem; padding:.4rem .8rem; display:inline-flex; align-items:center; margin:.2rem; }
.facility-tag i { margin-right:.4rem; }
.remove-facility { margin-left:.5rem; cursor:pointer; font-weight:bold; color:#6c757d; }
.remove-facility:hover { color:#dc3545; }
.btn-primary, .btn-success {
    border-radius:2rem; padding:.6rem 1.4rem;
    transition:transform .2s, box-shadow .2s;
}
.btn-primary:hover, .btn-success:hover {
    transform:translateY(-2px);
    box-shadow:var(--shadow-soft);
}
#map {
    border-radius:1rem; box-shadow:var(--shadow-soft);
    transition:box-shadow .3s;
}
#map:hover { box-shadow:0 8px 30px rgba(0,0,0,0.1); }

#image-drop-area { border:2px dashed #6c757d; border-radius:1rem; padding:2rem; text-align:center; color:#6c757d; cursor:pointer; transition:background .3s; }
#image-drop-area.dragover { background:rgba(0,0,0,0.03); }
#image-preview-list .image-item { position:relative; display:inline-block; margin:.5rem; }
#image-preview-list .image-item img { border-radius:.5rem; width:100px; height:100px; object-fit:cover; box-shadow:var(--shadow-soft); }
#image-preview-list .image-item .info { text-align:center; margin-top:.4rem; width:100px; font-size:.85rem; overflow:hidden; }
#image-preview-list .image-item .remove-btn { position:absolute; top:4px; right:4px; background:rgba(0,0,0,0.6); border:none; color:#fff; border-radius:50%; width:24px; height:24px; font-size:16px; line-height:22px; text-align:center; cursor:pointer; }
#map { border-radius:1rem; box-shadow:var(--shadow-soft); transition:box-shadow .3s; }
#map:hover { box-shadow:0 8px 30px rgba(0,0,0,0.1); }

 /* Breadcrumb */
        .breadcrumb {
            /* padding: 0.75rem 1.25rem; */
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); */
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #94a3b8;
        }

        .breadcrumb-item a {
            color: #64748b;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #289A84;
        }

        .breadcrumb-item.active {
            color: #289A84;
            font-weight: 600;
        }
</style>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item"><a href="/property">Property</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah Property</li>
    </ol>
  </nav>

<div class="container-fluid">
  <div class="card mb-5">
    <div class="card-body">
      <h4 class="fw-bold mb-4">Tambah Properti</h4>
      <form id="propertyForm" action="{{ route('owner.store-property') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
          <!-- Nama -->
          <div class="col-md-6">
        <div class="form-floating">
            <input 
            type="text" 
            class="form-control" 
            id="name" 
            name="name" 
            placeholder=" " 
            required>
            <label for="name">Nama Properti</label>
        </div>
        </div>

          <!-- Tipe -->
          <div class="col-md-6">
            <div class="form-floating">
              <select class="form-select" id="property_type" name="property_type_id" required>
                <option value="" disabled selected>Pilih tipe properti</option>
                @foreach($propertyTypes as $type)
                  <option value="{{ $type->id }}">{{ $type->property_type }}</option>
                @endforeach
              </select>
              <label for="property_type">Tipe Properti</label>
            </div>
          </div>

          <!-- Provinsi -->
          <div class="col-md-3">
            <div class="form-floating">
              <select class="form-select" id="province" name="province" required>
                <option value="" disabled selected>Provinsi</option>
              </select>
              <label for="province">Provinsi</label>
            </div>
          </div>
          <!-- Kota/Kabupaten -->
          <div class="col-md-3">
            <div class="form-floating">
              <select class="form-select" id="city" name="city" disabled required>
                <option value="" disabled selected>Kota/Kabupaten</option>
              </select>
              <label for="city">Kota/Kabupaten</label>
            </div>
          </div>
          <!-- Kecamatan -->
          <div class="col-md-3">
            <div class="form-floating">
              <select class="form-select" id="district" name="district" disabled required>
                <option value="" disabled selected>Kecamatan</option>
              </select>
              <label for="district">Kecamatan</label>
            </div>
          </div>
          <!-- Kelurahan -->
          <div class="col-md-3">
            <div class="form-floating">
              <select class="form-select" id="subdistrict" name="subdis_id" disabled required>
                <option value="" disabled selected>Kelurahan</option>
              </select>
              <label for="subdistrict">Kelurahan</label>
            </div>
          </div>

          <!-- Alamat Selengkapnya -->
        <div class="col-12">
        <div class="form-floating">
            <textarea 
            class="form-control" 
            id="full_address" 
            name="full_address" 
            placeholder="Alamat Selengkapnya" 
            style="height: 100px" 
            required></textarea>
            <label for="full_address">Alamat Selengkapnya</label>
        </div>
        </div>

          <!-- Deskripsi -->
          <div class="col-12">
            <div class="form-floating">
              <textarea class="form-control" placeholder="Deskripsi properti" id="description" name="description" style="height:100px" required></textarea>
              <label for="description">Deskripsi</label>
            </div>
          </div>

          <!-- Fasilitas -->
          <div class="col-12">
            <label class="form-label fw-semibold">Fasilitas</label>
            <div class="facilities-container position-relative mb-3">
              <input type="text" class="form-control mb-2" id="facilityInput" placeholder="Cari fasilitas...">
              <div class="autocomplete-dropdown" id="facilityDropdown"></div>
              <div id="selectedFacilities" class="d-flex flex-wrap"></div>
              <div id="hiddenFacilitiesContainer"></div>
            </div>
          </div>

          <!-- Upload Gambar -->
          <div class="col-12">
            <label class="form-label fw-semibold">Upload Gambar</label>
            <div id="image-drop-area">
              <i class="bi bi-cloud-arrow-up" style="font-size:2rem;"></i>
              <p>Drag & drop images here<br>atau klik untuk memilih</p>
              <input type="file" id="imageInput" name="images[]" accept="image/*" multiple style="display:none;">
            </div>
            <div id="image-preview-list"></div>
          </div>

          <!-- Peta -->
          <div class="col-12">
            <label class="form-label fw-semibold">Koordinat</label>
            <div id="map" style="height:350px;"></div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
          </div>
        </div>

        <button type="submit" class="btn btn-success mt-4">Tambah Properti</button>
      </form>
    </div>
  </div>
</div>

{{-- Leaflet CSS/JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJ+YRFyaHS+8Qv+er58QmsQJf5VfXck4uml3I="
        crossorigin="anonymous"></script>

<script>
$(function(){
    // Load Provinsi
    $.getJSON('/provinces', data => {
        $('#province').append(data.map(p => `<option value="${p.id}">${p.prov_name}</option>`));
    });

    // Provinsi → Kota/Kabupaten
    $('#province').change(function(){
        let prov = $(this).val();
        $('#city,#district,#subdistrict').html('<option disabled selected>Memuat…</option>').prop('disabled',true);
        if(!prov) return;
        $.getJSON(`/cities/${prov}`, data => {
            $('#city').html('<option disabled selected>Pilih Kota/Kabupaten</option>').prop('disabled',false)
                      .append(data.map(c=>`<option value="${c.id}">${c.city_name}</option>`));
        });
    });

    // Kota → Kecamatan
    $('#city').change(function(){
        let city = $(this).val();
        $('#district,#subdistrict').html('<option disabled selected>Memuat…</option>').prop('disabled',true);
        if(!city) return;
        $.getJSON(`/districts/${city}`, data => {
            $('#district').html('<option disabled selected>Pilih Kecamatan</option>').prop('disabled',false)
                           .append(data.map(d=>`<option value="${d.id}">${d.dis_name}</option>`));
        });
    });

    // Kecamatan → Kelurahan
    $('#district').change(function(){
        let dist = $(this).val();
        $('#subdistrict').html('<option disabled selected>Memuat…</option>').prop('disabled',true);
        if(!dist) return;
        $.getJSON(`/subdistricts/${dist}`, data => {
            $('#subdistrict').html('<option disabled selected>Pilih Kelurahan</option>').prop('disabled',false)
                             .append(data.map(s=>`<option value="${s.id}">${s.subdis_name}</option>`));
        });
    });

    // Init Map
    const map = L.map('map').setView([-2.5489,118.0149],5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    const marker = L.marker([-2.5489,118.0149],{draggable:true}).addTo(map);
    const upd = ll=>{ $('#latitude').val(ll.lat.toFixed(6)); $('#longitude').val(ll.lng.toFixed(6)); };
    marker.on('moveend',e=>upd(e.target.getLatLng()));
    map.on('click',e=>{ marker.setLatLng(e.latlng); upd(e.latlng); });
    navigator.geolocation?.getCurrentPosition(p=>{ const ll={lat:p.coords.latitude,lng:p.coords.longitude}; map.setView([ll.lat,ll.lng],16); marker.setLatLng([ll.lat,ll.lng]); upd(ll); });

    // Facilities Logic
    let allFac = [], selIds = [];
    $('#facilityInput').on('input',function(){
        let q = $(this).val().trim();
        if(!q){ $('#facilityDropdown').hide(); return; }
        $.getJSON(`/get-facilities?search=${encodeURIComponent(q)}`, data => {
            allFac = data;
            let items = data.map(f=>`<div class="autocomplete-item" data-id="${f.id}" data-name="${f.name}" data-icon="${f.icon || ''}"><i class="bi ${f.icon}"></i> ${f.name}</div>`);
            $('#facilityDropdown').html(items.join('')).show();
        });
    });
    
    $('#facilityDropdown').on('click','.autocomplete-item', function(){
        let id = $(this).data('id'), name = $(this).data('name'), icon=$(this).data('icon');
        if(!selIds.includes(id.toString())){
            selIds.push(id.toString());
            let tag = $(`<span class="facility-tag" data-id="${id}"><i class="bi ${icon}"></i> ${name}<span class="remove-facility">&times;</span></span>`);
            $('#selectedFacilities').append(tag);
            $('#hiddenFacilitiesContainer').append(`<input type="hidden" name="facilities[]" value="${id}">`);
        }
        $('#facilityInput').val(''); $('#facilityDropdown').hide();
    });

    $('#selectedFacilities').on('click','.remove-facility', function(){
        let parent = $(this).closest('.facility-tag'), fid = parent.data('id').toString();
        selIds = selIds.filter(x=>x!==fid);
        parent.remove();
        $(`#hiddenFacilitiesContainer input[value="${fid}"]`).remove();
    });
});
</script>

<script>
    // Image Upload
  const drop = $('#image-drop-area'), inp = $('#imageInput')[0], previews = $('#image-preview-list');
  drop.on('click', () => inp.click());
  drop.on('dragenter dragover', e => { e.preventDefault(); drop.addClass('dragover'); });
  drop.on('dragleave drop', e => { e.preventDefault(); drop.removeClass('dragover'); if(e.type==='drop') inp.files = e.originalEvent.dataTransfer.files, showPre(); });
  $(inp).on('change', showPre);
  function showPre(){
    previews.empty();
    Array.from(inp.files).forEach((f,i) => {
      const reader = new FileReader(); reader.onload = ev => {
        const ext=f.name.split('.').pop(), size=(f.size/1024).toFixed(1);
        const itm = $(
          `<div class="image-item">
            <img src="${ev.target.result}"><div class="info">${f.name}<br>${size} KB | .${ext}</div>
            <button type="button" class="remove-btn">&times;</button>
          </div>`);
        itm.find('.remove-btn').click(()=>{
          const dt=new DataTransfer();
          Array.from(inp.files).filter((_,j)=>j!==i).forEach(x=>dt.items.add(x)); inp.files=dt.files; showPre();
        });
        previews.append(itm);
      };
      reader.readAsDataURL(f);
    });
  }
</script>

@endsection
