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
                <li class="breadcrumb-item" aria-current="page">Pesanan</li>
              </ul>
            </div>
            <div class="col-md-12">
              <h2 class="mb-0">Data Pesanan</h2>
            </div>
          </div>
        </div>
      </div>

      {{-- COUNT BADGES --}}
      @php
        $semua     = $pesanans->count();
        $menunggu  = $pesanans->where('status_pesanan', 'menunggu_timbangan')->count();
        $diproses  = $pesanans->where('status_pesanan', 'diproses')->count();
        $selesai   = $pesanans->where('status_pesanan', 'selesai')->count();
        $aktifTab  = request('status', 'semua');
      @endphp

      {{-- TABS --}}
      <ul class="nav nav-tabs mb-0" id="pesananTabs" role="tablist"
          style="border-bottom: 2px solid #dc653d;">
        @foreach([
          ['key'=>'semua',             'label'=>'Semua',              'count'=>$semua,    'color'=>'secondary'],
          ['key'=>'menunggu_timbangan','label'=>'Menunggu Timbangan', 'count'=>$menunggu, 'color'=>'warning'],
          ['key'=>'diproses',          'label'=>'Diproses',           'count'=>$diproses, 'color'=>'primary'],
          ['key'=>'selesai',           'label'=>'Selesai',            'count'=>$selesai,  'color'=>'success'],
        ] as $tab)
        <li class="nav-item" role="presentation">
          <a href="{{ route('pesanan.index', ['status' => $tab['key']]) }}"
             class="nav-link {{ $aktifTab == $tab['key'] ? 'active fw-semibold' : '' }}"
             style="{{ $aktifTab == $tab['key'] ? 'color:#dc653d;border-color:#dc653d #dc653d #fff;' : '' }}">
            {{ $tab['label'] }}
            <span class="badge bg-{{ $tab['color'] }} ms-1">{{ $tab['count'] }}</span>
          </a>
        </li>
        @endforeach
      </ul>

      <div class="card border-0 shadow-sm" style="border-top-left-radius:0;border-top-right-radius:0;">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Pelanggan</th>
                  <th>Paket</th>
                  <th>Berat</th>
                  <th>Total Harga</th>
                  <th>Status</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $filtered = $aktifTab == 'semua'
                    ? $pesanans
                    : $pesanans->where('status_pesanan', $aktifTab);
                @endphp
                @forelse($filtered as $pesanan)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $pesanan->pelanggan->user->name ?? '-' }}</td>
                  <td>{{ $pesanan->paketLaundry->nama_paket ?? '-' }}</td>
                  <td>{{ $pesanan->jumlah_kilogram ? $pesanan->jumlah_kilogram.' Kg' : '-' }}</td>
                  <td>{{ $pesanan->total_harga ? 'Rp '.number_format($pesanan->total_harga,0,',','.') : 'Belum Dihitung' }}</td>
                  <td>
                    @if($pesanan->status_pesanan == 'menunggu_timbangan')
                      <span class="badge bg-warning text-dark">Menunggu Timbangan</span>
                    @elseif($pesanan->status_pesanan == 'diproses')
                      <span class="badge bg-primary">Diproses</span>
                    @elseif($pesanan->status_pesanan == 'selesai')
                      <span class="badge bg-success">Selesai</span>
                    @else
                      <span class="badge bg-secondary">{{ $pesanan->status_pesanan }}</span>
                    @endif
                  </td>
                  <td>{{ $pesanan->created_at->format('d M Y') }}</td>
                  <td>
                    <a href="{{ route('pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                    <a href="{{ route('pesanan.edit', $pesanan->id) }}" class="btn btn-sm btn-warning">Timbang</a>
                    <form action="{{ route('pesanan.destroy', $pesanan->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Yakin hapus data ini?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    Tidak ada pesanan dengan status ini.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
</section>
@endsection