@extends('template-user')

@extends('template-user')

@section('content')
<div class="container my-5 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="text-center mb-4 pb-2">
                <h2 class="fw-bold" style="color: var(--dark);">Buat Pesanan <span class="gradient-text">Laundry</span></h2>
                <p class="text-muted">Isi detail pesanan Anda di bawah ini</p>
            </div>

            <div class="card card-modern border-0">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('user.pesanan.store') }}" method="POST" id="formPesanan">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Pilih Paket Laundry</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-box-seam text-primary"></i></span>
                                <select name="paket_laundry_id" class="form-select border-start-0 ps-0 fw-medium" required>
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($pakets as $paket)
                                        <option value="{{ $paket->id }}" {{ $paket_id == $paket->id ? 'selected' : '' }}>
                                            {{ $paket->nama_paket }} - Rp {{ number_format($paket->harga_paket_per_kg, 0, ',', '.') }} / kg
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Layanan Antar Jemput?</label>
                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="antar_jemput" id="antar_ya" value="ya" required>
                                    <label class="btn btn-outline-primary w-100 py-3 text-start d-flex align-items-center rounded-4" for="antar_ya">
                                        <div class="icon-box-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                            <i class="bi bi-truck fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Ya, Antar Jemput</div>
                                            <small class="text-muted">Kurir akan mengambil pakaian</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="antar_jemput" id="antar_tidak" value="tidak">
                                    <label class="btn btn-outline-secondary w-100 py-3 text-start d-flex align-items-center rounded-4 border-2" for="antar_tidak">
                                        <div class="icon-box-sm bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                            <i class="bi bi-person-walking fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Antar Sendiri</div>
                                            <small class="text-muted">Bawa langsung ke outlet</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section Lokasi (Hidden initially) -->
                        <div id="lokasiSection" style="display: none;" class="p-4 bg-light rounded-4 mb-4 border border-primary-subtle">
                            <h5 class="fw-bold mb-3 d-flex align-items-center text-dark">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 32px; height: 32px;"><i class="bi bi-geo-alt-fill"></i></div>
                                Lokasi Penjemputan
                            </h5>
                            
                            <button type="button" class="btn btn-primary bg-primary border-0 rounded-pill px-4 mb-3" id="btnGetLocation">
                                <i class="bi bi-crosshair me-2"></i> Dapatkan Lokasi Saya
                            </button>
                            
                            <div id="locationStatus" class="small text-muted mb-3 p-2 bg-white rounded-3 border d-none"></div>

                            <input type="hidden" name="latitude_antar_jemput" id="latitude">
                            <input type="hidden" name="longitude_antar_jemput" id="longitude">
                            <input type="hidden" name="ongkir_antar_jemput" id="ongkir_input">

                            <div class="alert bg-white border border-info border-start-0 border-end-0 border-bottom-0 border-top-3 mt-3 shadow-sm d-none" id="ongkirInfo">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted"><i class="bi bi-signpost-split me-2 text-info"></i>Estimasi Jarak</span>
                                    <span class="fw-bold text-dark"><span id="jarakText">0</span> meter</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="bi bi-wallet2 me-2 text-success"></i>Biaya Antar Jemput</span>
                                    <span class="fw-bold text-primary">Rp <span id="ongkirText">0</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="alert bg-primary-subtle border-0 rounded-4 d-flex align-items-center mt-4">
                            <i class="bi bi-info-circle-fill text-primary fs-3 me-3"></i> 
                            <div class="text-dark">
                                <strong>Catatan:</strong> Total harga laundry (kg × harga paket) akan dihitung secara akurat oleh admin setelah pakaian ditimbang.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-modern w-100 py-3 mt-4 fs-5 rounded-pill shadow-lg d-flex align-items-center justify-content-center" id="btnSubmit">
                            <i class="bi bi-check-circle me-2"></i> Buat Pesanan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-check:checked + .btn-outline-primary {
        background-color: rgba(255, 117, 85, 0.1);
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(255, 117, 85, 0.15);
    }
    .btn-check:checked + .btn-outline-secondary {
        background-color: #f8f9fa;
        border-color: #6c757d !important;
        color: #495057;
        box-shadow: 0 0 0 4px rgba(108, 117, 125, 0.15);
    }
    .btn-outline-secondary {
        border-color: #dee2e6;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #495057;
        border-color: #ced4da;
    }
</style>
@endsection

@section('scripts')
<script>
    const radioAntarJemput = document.querySelectorAll('input[name="antar_jemput"]');
    const lokasiSection = document.getElementById('lokasiSection');
    const btnGetLocation = document.getElementById('btnGetLocation');
    const locationStatus = document.getElementById('locationStatus');
    const ongkirInfo = document.getElementById('ongkirInfo');
    
    // Data dari admin setting
    const laundryLat = {{ $ongkos->latitude_lokasi_laundry ?? 0 }};
    const laundryLng = {{ $ongkos->longitude_lokasi_laundry ?? 0 }};
    const hargaPerMeter = {{ $ongkos->harga_per_meter ?? 0 }};

    // Haversine formula to calculate distance in meters
    function getDistanceFromLatLonInMeters(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2-lat1);  // deg2rad below
        var dLon = deg2rad(lon2-lon1); 
        var a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2); 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        var d = R * c; // Distance in km
        return Math.round(d * 1000); // Distance in meters
    }

    function deg2rad(deg) {
        return deg * (Math.PI/180);
    }

    radioAntarJemput.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if(e.target.value === 'ya') {
                lokasiSection.style.display = 'block';
                if(!document.getElementById('latitude').value) {
                    document.getElementById('btnSubmit').disabled = true;
                }
            } else {
                lokasiSection.style.display = 'none';
                document.getElementById('btnSubmit').disabled = false;
            }
        });
    });

    btnGetLocation.addEventListener('click', () => {
        if (navigator.geolocation) {
            locationStatus.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mencari lokasi...';
            navigator.geolocation.getCurrentPosition(showPosition, showError, {
                enableHighAccuracy: true
            });
        } else {
            locationStatus.innerHTML = "Geolocation is not supported by this browser.";
        }
    });

    function showPosition(position) {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;

        document.getElementById('latitude').value = userLat;
        document.getElementById('longitude').value = userLng;

        locationStatus.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Lokasi berhasil didapatkan!</span>';

        // Hitung jarak
        if(laundryLat && laundryLng) {
            const distanceMeters = getDistanceFromLatLonInMeters(laundryLat, laundryLng, userLat, userLng);
            const totalOngkir = distanceMeters * hargaPerMeter;

            document.getElementById('jarakText').innerText = distanceMeters;
            document.getElementById('ongkirText').innerText = totalOngkir.toLocaleString('id-ID');
            document.getElementById('ongkir_input').value = totalOngkir;

            ongkirInfo.classList.remove('d-none');
            document.getElementById('btnSubmit').disabled = false;
        } else {
            locationStatus.innerHTML = '<span class="text-danger">Admin belum mengatur lokasi laundry. Tidak bisa menggunakan fitur antar jemput.</span>';
        }
    }

    function showError(error) {
        let msg = "";
        switch(error.code) {
            case error.PERMISSION_DENIED:
                msg = "User denied the request for Geolocation.";
                break;
            case error.POSITION_UNAVAILABLE:
                msg = "Location information is unavailable.";
                break;
            case error.TIMEOUT:
                msg = "The request to get user location timed out.";
                break;
            case error.UNKNOWN_ERROR:
                msg = "An unknown error occurred.";
                break;
        }
        locationStatus.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Gagal: ${msg}</span>`;
    }
</script>
@endsection
