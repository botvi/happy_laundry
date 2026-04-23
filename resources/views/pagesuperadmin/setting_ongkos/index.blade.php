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
                <li class="breadcrumb-item" aria-current="page">Setting Ongkos</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Setting Ongkos (Antar Jemput)</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-header">
              <h5>Form Setting Ongkos</h5>
            </div>
            <div class="card-body">
              <form action="{{ route('setting-ongkos.update', $ongkos->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label class="form-label">Harga per Meter (Rp)</label>
                  <input type="number" name="harga_per_meter" class="form-control" placeholder="Contoh: 1000" value="{{ $ongkos->harga_per_meter }}" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Latitude Lokasi Laundry</label>
                  <input type="text" name="latitude_lokasi_laundry" class="form-control" placeholder="Latitude" value="{{ $ongkos->latitude_lokasi_laundry }}" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Longitude Lokasi Laundry</label>
                  <input type="text" name="longitude_lokasi_laundry" class="form-control" placeholder="Longitude" value="{{ $ongkos->longitude_lokasi_laundry }}" required>
                </div>
                <div class="card-footer text-end">
                  <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection