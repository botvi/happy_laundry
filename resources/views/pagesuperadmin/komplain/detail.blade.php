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
                <li class="breadcrumb-item"><a href="{{ route('superadmin.komplain.index') }}">Komplain</a></li>
                <li class="breadcrumb-item" aria-current="page">Detail</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Detail Komplain</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
                <h5>Informasi Komplain</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Pelanggan</th>
                        <td>: {{ $komplain->pelanggan->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Terkait Pesanan</th>
                        <td>: 
                            @if($komplain->pesanan)
                                <a href="{{ route('pesanan.show', $komplain->pesanan->id) }}" target="_blank">Lihat Pesanan #{{ $komplain->pesanan->id }}</a>
                            @else
                                <span class="text-muted">Tidak terkait pesanan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk</th>
                        <td>: {{ $komplain->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Status Saat Ini</th>
                        <td>: 
                          @if($komplain->status == 'menunggu')
                              <span class="badge bg-warning">Menunggu</span>
                          @elseif($komplain->status == 'diproses')
                              <span class="badge bg-primary">Diproses</span>
                          @else
                              <span class="badge bg-success">Selesai</span>
                          @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Subjek</th>
                        <td>: {{ $komplain->subjek }}</td>
                    </tr>
                </table>
                <hr>
                <h6>Isi Pesan / Komplain:</h6>
                <div class="p-3 bg-light rounded border">
                    {!! nl2br(e($komplain->pesan)) !!}
                </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
                <h5>Tanggapan & Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.komplain.update', $komplain->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Ubah Status</label>
                        <select name="status" class="form-select">
                            <option value="menunggu" {{ $komplain->status == 'menunggu' ? 'selected' : '' }}>Menunggu Balasan</option>
                            <option value="diproses" {{ $komplain->status == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="selesai" {{ $komplain->status == 'selesai' ? 'selected' : '' }}>Selesai / Ditutup</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tulis Balasan / Solusi</label>
                        <textarea name="balasan" class="form-control" rows="6" placeholder="Ketik balasan Anda untuk pelanggan di sini...">{{ $komplain->balasan }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Tanggapan</button>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
