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
                <li class="breadcrumb-item"><a href="{{ route('paket-laundry.index') }}">Paket Laundry</a></li>
                <li class="breadcrumb-item" aria-current="page">Edit Paket</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Edit Paket Laundry</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-sm-8">
          <div class="card">
            <div class="card-body">
              <form action="{{ route('paket-laundry.update', $paket->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label class="form-label">Nama Paket</label>
                  <input type="text" name="nama_paket" class="form-control" value="{{ $paket->nama_paket }}" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" rows="3" required>{{ $paket->deskripsi }}</textarea>
                </div>
                <div class="form-group">
                  <label class="form-label">Harga per Kg (Rp)</label>
                  <input type="number" name="harga_paket_per_kg" class="form-control" value="{{ $paket->harga_paket_per_kg }}" required>
                </div>
                <div class="text-end mt-4">
                  <a href="{{ route('paket-laundry.index') }}" class="btn btn-secondary">Kembali</a>
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