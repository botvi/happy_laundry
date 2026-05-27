@extends('template-admin.layout')

@section('content')
<section class="pc-container">
    <div class="pc-content">
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard-superadmin">Home</a></li>
                <li class="breadcrumb-item" aria-current="page">Profil Laundry</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Pengaturan Profil & Landing Page</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <form action="{{ route('landing-setting.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                  <label class="form-label">Alamat / Embed URL Google Maps</label>
                  <textarea name="alamat_maps" class="form-control" rows="4" placeholder='Contoh: <iframe src="https://www.google.com/maps/embed?pb=..."></iframe>'>{{ $setting->alamat_maps ?? '' }}</textarea>
                  <small class="text-muted">Masukkan kode Embed Iframe dari Google Maps agar peta bisa ditampilkan.</small>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jam Buka</label>
                        <input type="time" name="jam_buka" class="form-control" value="{{ $setting->jam_buka ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Tutup</label>
                        <input type="time" name="jam_tutup" class="form-control" value="{{ $setting->jam_tutup ?? '' }}">
                    </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Running Text (Pengumuman Berjalan)</label>
                  <input type="text" name="running_text" class="form-control" placeholder="Contoh: Promo! Diskon 20% untuk cuci bedcover minggu ini." value="{{ $setting->running_text ?? '' }}">
                  <small class="text-muted">Teks ini akan berjalan di bagian paling atas halaman website.</small>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
