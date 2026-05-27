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

        {{-- CHARTS --}}
        <div class="row g-4 mb-4">
            {{-- Chart Pendapatan & Pesanan 12 Bulan --}}
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">Grafik Pendapatan & Pesanan</h5>
                            <small class="text-muted">12 Bulan Terakhir</small>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge" style="background:#dc653d;">
                                <i class="ti ti-circle-filled me-1" style="font-size:0.5rem;"></i>Pendapatan
                            </span>
                            <span class="badge bg-primary">
                                <i class="ti ti-circle-filled me-1" style="font-size:0.5rem;"></i>Pesanan
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPendapatan" height="120"></canvas>
                    </div>
                </div>
            </div>

            {{-- Chart Donut Status --}}
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Status Pesanan</h5>
                        <small class="text-muted">Keseluruhan</small>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <canvas id="chartStatus" height="220"></canvas>
                        <div class="mt-3 w-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><span class="badge bg-warning text-dark me-1">&nbsp;</span> Menunggu Timbangan</span>
                                <strong>{{ $pesananMenunggu }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><span class="badge bg-primary me-1">&nbsp;</span> Diproses</span>
                                <strong>{{ $pesananProses }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span><span class="badge bg-success me-1">&nbsp;</span> Selesai</span>
                                <strong>{{ $pesananSelesai }}</strong>
                            </div>
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

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    // ── Data dari Laravel ──────────────────────────────────────────────
    const chartLabels     = @json($chartLabels);
    const chartPendapatan = @json($chartPendapatan);
    const chartPesanan    = @json($chartPesanan);
    const statusLabels    = @json($statusLabels);
    const statusData      = @json($statusData);

    // ── Grafik Garis: Pendapatan & Pesanan ────────────────────────────
    const ctxLine = document.getElementById('chartPendapatan').getContext('2d');
    new Chart(ctxLine, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: chartPendapatan,
                    backgroundColor: 'rgba(220,101,61,0.15)',
                    borderColor: '#dc653d',
                    borderWidth: 2,
                    borderRadius: 6,
                    type: 'bar',
                    yAxisID: 'y',
                },
                {
                    label: 'Jumlah Pesanan',
                    data: chartPesanan,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d6efd',
                    tension: 0.4,
                    type: 'line',
                    yAxisID: 'y1',
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            if (ctx.dataset.label.includes('Pendapatan')) {
                                return ' Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                            }
                            return ' ' + ctx.parsed.y + ' Pesanan';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                },
                y: {
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        font: { size: 11 },
                        callback: v => 'Rp ' + (v / 1000).toLocaleString('id-ID') + 'K'
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: { font: { size: 11 }, stepSize: 1 }
                }
            }
        }
    });

    // ── Grafik Donut: Status Pesanan ──────────────────────────────────
    const ctxDonut = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: ['#ffc107', '#0d6efd', '#198754'],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' pesanan'
                    }
                }
            }
        }
    });
</script>
@endsection
