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
                <li class="breadcrumb-item" aria-current="page">Data Komplain</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Daftar Komplain Pelanggan</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Pelanggan</th>
                      <th>Nomor Pesanan</th>
                      <th>Subjek</th>
                      <th>Status</th>
                      <th>Tanggal</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($komplains as $komplain)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $komplain->pelanggan->user->name ?? '-' }}</td>
                      <td>
                          @if($komplain->pesanan)
                              <span class="badge bg-secondary">Order #{{ $komplain->pesanan->id }}</span>
                          @else
                              -
                          @endif
                      </td>
                      <td>{{ Str::limit($komplain->subjek, 30) }}</td>
                      <td>
                          @if($komplain->status == 'menunggu')
                              <span class="badge bg-warning">Menunggu</span>
                          @elseif($komplain->status == 'diproses')
                              <span class="badge bg-primary">Diproses</span>
                          @else
                              <span class="badge bg-success">Selesai</span>
                          @endif
                      </td>
                      <td>{{ $komplain->created_at->format('d M Y, H:i') }}</td>
                      <td>
                          <a href="{{ route('superadmin.komplain.show', $komplain->id) }}" class="btn btn-sm btn-info text-white">Detail & Balas</a>
                      </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada komplain masuk.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
