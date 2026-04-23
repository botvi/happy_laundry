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
                <li class="breadcrumb-item"><a href="{{ route('pesanan.index') }}">Pesanan</a></li>
                <li class="breadcrumb-item" aria-current="page">Update Pesanan</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Update Pesanan & Input Timbangan</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-sm-8">
          <div class="card">
            <div class="card-body">
              <form action="{{ route('pesanan.update', $pesanan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nama Pelanggan:</strong> {{ $pesanan->pelanggan->user->name ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Paket Laundry:</strong> {{ $pesanan->paketLaundry->nama_paket ?? '-' }} 
                        (Rp {{ number_format($pesanan->paketLaundry->harga_paket_per_kg ?? 0, 0, ',', '.') }} / kg)
                    </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Jumlah Berat (Kg)</label>
                  <input type="number" step="0.1" name="jumlah_kilogram" class="form-control" value="{{ $pesanan->jumlah_kilogram }}" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label">Bukti Timbangan (Gambar)</label>
                  <input type="file" name="gambar_bukti_timbangan" class="form-control" accept="image/*">
                  @if($pesanan->gambar_bukti_timbangan)
                    <div class="mt-2">
                        <img src="{{ asset('uploads/bukti/'.$pesanan->gambar_bukti_timbangan) }}" alt="Bukti Timbangan" style="max-height: 150px;">
                    </div>
                  @endif
                </div>

                <div class="form-group">
                  <label class="form-label">Status Pesanan</label>
                  <select name="status_pesanan" class="form-control" required>
                    <option value="menunggu_timbangan" {{ $pesanan->status_pesanan == 'menunggu_timbangan' ? 'selected' : '' }}>Menunggu Timbangan</option>
                    <option value="diproses" {{ $pesanan->status_pesanan == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ $pesanan->status_pesanan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                  </select>
                </div>

                <div class="text-end mt-4">
                  <a href="{{ route('pesanan.index') }}" class="btn btn-secondary">Kembali</a>
                  <button type="submit" class="btn btn-primary">Simpan & Hitung Total</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
