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
                            <li class="breadcrumb-item" aria-current="page">Laporan</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <h2 class="mb-0">Laporan Pesanan</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                {{-- Tombol Filter Cepat --}}
                <div class="mb-3">
                    <span class="fw-semibold me-2 text-muted small">Filter Cepat:</span>
                    <a href="{{ route('laporan.index', array_merge(request()->except('periode','dari','sampai'), ['periode' => 'minggu'])) }}"
                       class="btn btn-sm me-1 {{ request('periode') == 'minggu' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="ti ti-calendar-week me-1"></i>Minggu Ini
                    </a>
                    <a href="{{ route('laporan.index', array_merge(request()->except('periode','dari','sampai'), ['periode' => 'bulan'])) }}"
                       class="btn btn-sm me-1 {{ request('periode') == 'bulan' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="ti ti-calendar-month me-1"></i>Bulan Ini
                    </a>
                    <a href="{{ route('laporan.index', array_merge(request()->except('periode','dari','sampai'), ['periode' => 'tahun'])) }}"
                       class="btn btn-sm me-1 {{ request('periode') == 'tahun' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                        <i class="ti ti-calendar me-1"></i>Tahun Ini
                    </a>
                    <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-refresh me-1"></i>Semua
                    </a>
                </div>

                <hr class="my-3">

                {{-- Filter Manual --}}
                <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu_timbangan" {{ request('status') == 'menunggu_timbangan' ? 'selected' : '' }}>Menunggu Timbangan / Dihitung</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Dari Tanggal</label>
                        <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sampai Tanggal</label>
                        <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                            <i class="ti ti-x"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Label Periode Aktif --}}
        @if(request('periode') || (request('dari') && request('sampai')))
        <div class="alert alert-info d-flex align-items-center py-2 mb-3" style="border-left: 4px solid #17a2b8;">
            <i class="ti ti-info-circle me-2"></i>
            @if(request('periode') == 'minggu')
                Menampilkan data <strong>minggu ini</strong> ({{ \Carbon\Carbon::now()->startOfWeek()->format('d M Y') }} – {{ \Carbon\Carbon::now()->endOfWeek()->format('d M Y') }})
            @elseif(request('periode') == 'bulan')
                Menampilkan data <strong>bulan ini</strong> ({{ \Carbon\Carbon::now()->format('F Y') }})
            @elseif(request('periode') == 'tahun')
                Menampilkan data <strong>tahun {{ \Carbon\Carbon::now()->year }}</strong>
            @else
                Menampilkan data dari <strong>{{ request('dari') }}</strong> sampai <strong>{{ request('sampai') }}</strong>
            @endif
        </div>
        @endif

        {{-- Summary --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Total Data Pesanan</p>
                        <h3 class="fw-bold mb-0">{{ $pesanans->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Pesanan Selesai</p>
                        <h3 class="fw-bold mb-0">{{ $pesanans->where('status_pesanan', 'selesai')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Total Pendapatan</p>
                        <h3 class="fw-bold mb-0" style="color:#dc653d;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Daftar Pesanan</h5>
                <a href="{{ route('laporan.print', request()->all()) }}" target="_blank"
                   class="btn btn-sm" style="background:#dc653d;color:white;">
                    <i class="ti ti-printer me-1"></i> Cetak
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Pelanggan</th>
                                <th>Tipe</th>
                                <th>Paket</th>
                                <th>Jumlah / Berat</th>
                                <th>Ongkir</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if(($p->pelanggan->user->role ?? '') == 'superadmin')
                                        <span class="text-secondary fw-semibold">Offline / Walk-in</span>
                                    @else
                                        {{ $p->pelanggan->user->name ?? '-' }}
                                    @endif
                                </td>
                                <td>
                                    @if(($p->pelanggan->user->role ?? '') == 'superadmin')
                                        <span class="badge bg-secondary">Offline</span>
                                    @else
                                        <span class="badge bg-success">Online</span>
                                    @endif
                                </td>
                                <td>{{ $p->paketLaundry->nama_paket ?? '-' }}</td>
                                <td>
                                    @if($p->jumlah_kilogram)
                                        {{ $p->jumlah_kilogram }} {{ ($p->paketLaundry->satuan ?? 'kg') == 'helai' ? 'Helai' : 'Kg' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $p->ongkir_antar_jemput ? 'Rp '.number_format($p->ongkir_antar_jemput,0,',','.') : '-' }}</td>
                                <td>{{ $p->total_harga ? 'Rp '.number_format($p->total_harga,0,',','.') : '-' }}</td>
                                <td>
                                    @if($p->status_pesanan == 'menunggu_timbangan')
                                        <span class="badge bg-warning text-dark">{{ ($p->paketLaundry->satuan ?? 'kg') == 'helai' ? 'Menunggu Dihitung' : 'Menunggu Timbangan' }}</span>
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
                            <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection