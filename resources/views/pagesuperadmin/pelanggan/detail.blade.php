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
                            <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                            <li class="breadcrumb-item" aria-current="page">Detail</li>
                        </ul>
                    </div>
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Detail Pelanggan</h2>
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Info Pelanggan --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white fw-bold mb-3"
                             style="width:80px;height:80px;font-size:2rem;background:#dc653d;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        <hr>
                        <div class="text-start">
                            <p class="mb-2"><i class="ti ti-phone me-2 text-muted"></i> {{ $user->no_wa ?? '-' }}</p>
                            <p class="mb-2"><i class="ti ti-map-pin me-2 text-muted"></i> {{ $user->pelanggan->alamat ?? 'Belum diisi' }}</p>
                            <p class="mb-0"><i class="ti ti-calendar me-2 text-muted"></i> Daftar: {{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Pesanan --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Riwayat Pesanan</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Paket</th>
                                        <th>Berat (Kg)</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->pelanggan->pesanan ?? [] as $pesanan)
                                    <tr>
                                        <td>{{ $pesanan->paketLaundry->nama_paket ?? '-' }}</td>
                                        <td>{{ $pesanan->jumlah_kilogram ?? '-' }}</td>
                                        <td>{{ $pesanan->total_harga ? 'Rp '.number_format($pesanan->total_harga,0,',','.') : 'Belum dihitung' }}</td>
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
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
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
