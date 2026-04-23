@extends('template-admin.layout')

@section('content')
<section class="pc-container">
    <div class="pc-content">

        {{-- STATS CARDS --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                             style="width:56px;height:56px;background:#fcece6;">
                            <i class="ti ti-users" style="font-size:1.6rem;color:#dc653d;"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Total Pelanggan</p>
                            <h3 class="mb-0 fw-bold">{{ $totalPelanggan }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                             style="width:56px;height:56px;background:#fff3cd;">
                            <i class="ti ti-clock-hour4" style="font-size:1.6rem;color:#e6a817;"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Menunggu Timbangan</p>
                            <h3 class="mb-0 fw-bold">{{ $pesananMenunggu }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                             style="width:56px;height:56px;background:#d4edda;">
                            <i class="ti ti-washing-machine" style="font-size:1.6rem;color:#28a745;"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Sedang Diproses</p>
                            <h3 class="mb-0 fw-bold">{{ $pesananProses }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                             style="width:56px;height:56px;background:#d1ecf1;">
                            <i class="ti ti-currency-dollar" style="font-size:1.6rem;color:#17a2b8;"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Total Pendapatan</p>
                            <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PESANAN TERBARU --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">5 Pesanan Terbaru</h5>
                <a href="{{ route('pesanan.index') }}" class="btn btn-sm" style="background:#dc653d;color:white;">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Antar Jemput</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesananTerbaru as $p)
                            <tr>
                                <td>{{ $p->pelanggan->user->name ?? '-' }}</td>
                                <td>{{ $p->paketLaundry->nama_paket ?? '-' }}</td>
                                <td>{{ $p->dijemput == 'ya' ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $p->total_harga ? 'Rp '.number_format($p->total_harga,0,',','.') : '-' }}</td>
                                <td>
                                    @if($p->status_pesanan == 'menunggu_timbangan')
                                        <span class="badge bg-warning text-dark">Menunggu Timbangan</span>
                                    @elseif($p->status_pesanan == 'diproses')
                                        <span class="badge bg-primary">Diproses</span>
                                    @elseif($p->status_pesanan == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $p->status_pesanan }}</span>
                                    @endif
                                </td>
                                <td>{{ $p->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
