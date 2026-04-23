@extends('template-user')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-modern p-4">
                <div class="card-body">
                    <h2 class="mb-4 fw-bold text-center">Buat Pesanan Laundry</h2>
                    
                    <form action="{{ route('user.pesanan.store') }}" method="POST" id="formPesanan">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Paket Laundry</label>
                            <select name="paket_laundry_id" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Paket --</option>
                                @foreach($pakets as $paket)
                                    <option value="{{ $paket->id }}" {{ $paket_id == $paket->id ? 'selected' : '' }}>
                                        {{ $paket->nama_paket }} - Rp {{ number_format($paket->harga_paket_per_kg, 0, ',', '.') }} / kg
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Gunakan Layanan Antar Jemput?</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="antar_jemput" id="antar_ya" value="ya" required>
                                    <label class="form-check-label" for="antar_ya">Ya, Antar & Jemput</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="antar_jemput" id="antar_tidak" value="tidak">
                                    <label class="form-check-label" for="antar_tidak">Tidak, Saya antar sendiri</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section Lokasi (Hidden initially) -->
                        <div id="lokasiSection" style="display: none;" class="p-3 bg-light rounded mb-4 border">
                            <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> Lokasi Penjemputan</h5>
                            <button type="button" class="btn btn-outline-primary mb-3" id="btnGetLocation">
                                <i class="bi bi-crosshair"></i> Dapatkan Lokasi Saya
                            </button>
                            
                            <div id="locationStatus" class="small text-muted mb-3">Lokasi belum didapatkan.</div>

                            <input type="hidden" name="latitude_antar_jemput" id="latitude">
                            <input type="hidden" name="longitude_antar_jemput" id="longitude">
                            <input type="hidden" name="ongkir_antar_jemput" id="ongkir_input">

                            <div class="alert alert-info d-none" id="ongkirInfo">
                                <strong>Estimasi Jarak:</strong> <span id="jarakText">0</span> meter <br>
                                <strong>Biaya Antar Jemput:</strong> Rp <span id="ongkirText">0</span>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle"></i> <strong>Catatan:</strong> Total harga laundry (kg x harga paket) akan dihitung oleh admin setelah pakaian ditimbang.
                        </div>

                        <button type="submit" class="btn btn-modern w-100 py-3 mt-3 fs-5" id="btnSubmit">Buat Pesanan Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
