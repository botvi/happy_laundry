@extends('template-user')

@section('content')
<div class="container my-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold" style="color: #2b3a55;">Riwayat Pesanan Saya</h2>
            <p class="text-muted">Pantau status dan detail laundry Anda di sini.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('user.paket') }}" class="btn btn-modern">Buat Pesanan Baru</a>
        </div>
    </div>

    @php
        $aktif    = request('status', 'semua');
        $semua    = $pesanans->count();
        $menunggu = $pesanans->where('status_pesanan', 'menunggu_timbangan')->count();
        $proses   = $pesanans->where('status_pesanan', 'diproses')->count();
        $selesai  = $pesanans->where('status_pesanan', 'selesai')->count();
    @endphp

    {{-- TABS --}}
    <div class="d-flex gap-2 flex-wrap mb-4">
        @foreach([
            ['key'=>'semua',              'label'=>'Semua',              'count'=>$semua,    'color'=>'#6c757d'],
            ['key'=>'menunggu_timbangan', 'label'=>'Menunggu Timbangan', 'count'=>$menunggu, 'color'=>'#e6a817'],
            ['key'=>'diproses',           'label'=>'Diproses',           'count'=>$proses,   'color'=>'#0d6efd'],
            ['key'=>'selesai',            'label'=>'Selesai',            'count'=>$selesai,  'color'=>'#198754'],
        ] as $tab)
        <a href="{{ route('user.riwayat', ['status' => $tab['key']]) }}"
           class="btn btn-sm px-3 py-2 fw-medium"
           style="{{ $aktif == $tab['key']
                ? 'background:'.$tab['color'].';color:white;border:2px solid '.$tab['color'].';'
                : 'background:white;color:'.$tab['color'].';border:2px solid '.$tab['color'].';' }}">
            {{ $tab['label'] }}
            <span class="badge ms-1"
                  style="{{ $aktif == $tab['key']
                    ? 'background:rgba(255,255,255,0.3);color:white;'
                    : 'background:'.$tab['color'].';color:white;' }}">
                {{ $tab['count'] }}
            </span>
        </a>
        @endforeach
    </div>

    @php
        $tampil = $aktif == 'semua' ? $pesanans : $pesanans->where('status_pesanan', $aktif);
    @endphp

    @if($tampil->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <div class="display-1 text-muted mb-3"><i class="bi bi-inbox"></i></div>
            <h4 class="text-muted">Tidak ada pesanan</h4>
            <p class="text-muted mb-4">
                @if($aktif == 'semua')
                    Anda belum pernah membuat pesanan laundry.
                @else
                    Tidak ada pesanan dengan status "{{ str_replace('_', ' ', $aktif) }}".
                @endif
            </p>
            <a href="{{ route('user.paket') }}" class="btn btn-modern px-4 py-2">Pilih Paket Laundry</a>
        </div>
    @else
        <div class="d-flex flex-column gap-4">
            @foreach($tampil as $pesanan)
            @php
                $status = $pesanan->status_pesanan;

                $headerBg = '#f5f5f5';
                if ($status == 'menunggu_timbangan') $headerBg = '#fff8e1';
                elseif ($status == 'diproses') $headerBg = '#e8f0fe';
                elseif ($status == 'selesai') $headerBg = '#e8f5e9';

                $hargaPerKg  = $pesanan->paketLaundry->harga_paket_per_kg ?? 0;
                $beratKg     = $pesanan->jumlah_kilogram;
                $hargaLaundry = ($beratKg && $hargaPerKg > 0) ? $beratKg * $hargaPerKg : null;
                $totalBg     = $pesanan->total_harga ? '#fcece6' : '#f8f9fa';
                $colInfo     = $pesanan->gambar_bukti_timbangan ? 'col-md-8' : 'col-md-12';
            @endphp

            <div class="card card-modern overflow-hidden">

                {{-- HEADER STATUS --}}
                <div class="card-header border-0 d-flex justify-content-between align-items-center py-3 px-4"
                     style="background: {{ $headerBg }};">
                    <div class="d-flex align-items-center gap-2">
                        @if($status == 'menunggu_timbangan')
                            <i class="bi bi-hourglass-split fs-5" style="color:#e6a817;"></i>
                            <span class="fw-semibold" style="color:#e6a817;">Menunggu Timbangan</span>
                        @elseif($status == 'diproses')
                            <i class="bi bi-arrow-repeat fs-5 text-primary"></i>
                            <span class="fw-semibold text-primary">Sedang Diproses</span>
                        @elseif($status == 'selesai')
                            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                            <span class="fw-semibold text-success">Selesai</span>
                        @else
                            <span class="fw-semibold text-muted">{{ $status }}</span>
                        @endif
                    </div>
                    <small class="text-muted">{{ $pesanan->created_at->format('d M Y, H:i') }}</small>
                </div>

                <div class="card-body px-4 pt-3 pb-4">
                    <div class="row g-4">

                        {{-- KIRI: Info Pesanan --}}
                        <div class="{{ $colInfo }}">
                            <h4 class="fw-bold mb-1">{{ $pesanan->paketLaundry->nama_paket ?? 'Paket Dihapus' }}</h4>
                            <p class="text-muted small mb-3">{{ $pesanan->paketLaundry->deskripsi ?? '' }}</p>

                            <div class="row g-3">
                                {{-- Harga per Kg --}}
                                <div class="col-6 col-sm-4">
                                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                        <div class="text-muted small mb-1"><i class="bi bi-tag me-1"></i>Harga per Kg</div>
                                        <div class="fw-bold" style="color:#dc653d;">
                                            Rp {{ number_format($hargaPerKg, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Berat --}}
                                <div class="col-6 col-sm-4">
                                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                        <div class="text-muted small mb-1"><i class="bi bi-speedometer2 me-1"></i>Berat Timbangan</div>
                                        <div class="fw-bold">
                                            @if($beratKg)
                                                {{ $beratKg }} Kg
                                            @else
                                                <span class="text-muted fst-italic small">Belum ditimbang</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Harga Laundry --}}
                                <div class="col-6 col-sm-4">
                                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                        <div class="text-muted small mb-1"><i class="bi bi-calculator me-1"></i>Harga Laundry</div>
                                        <div class="fw-bold">
                                            @if($hargaLaundry)
                                                Rp {{ number_format($hargaLaundry, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted fst-italic small">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Antar Jemput --}}
                                <div class="col-6 col-sm-4">
                                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                        <div class="text-muted small mb-1"><i class="bi bi-truck me-1"></i>Antar Jemput</div>
                                        <div class="fw-bold">{{ $pesanan->dijemput == 'ya' ? 'Ya' : 'Tidak' }}</div>
                                    </div>
                                </div>

                                {{-- Ongkir --}}
                                <div class="col-6 col-sm-4">
                                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                                        <div class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>Ongkos Kirim</div>
                                        <div class="fw-bold">
                                            @if($pesanan->dijemput == 'ya')
                                                Rp {{ number_format($pesanan->ongkir_antar_jemput ?? 0, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Total Bayar --}}
                                <div class="col-6 col-sm-4">
                                    <div class="p-3 rounded-3" style="background: {{ $totalBg }};">
                                        <div class="text-muted small mb-1"><i class="bi bi-wallet2 me-1"></i>Total Bayar</div>
                                        <div class="fw-bold fs-6" style="color:#dc653d;">
                                            @if($pesanan->total_harga)
                                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted fst-italic small">Menunggu Admin</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KANAN: Bukti Timbangan --}}
                        @if($pesanan->gambar_bukti_timbangan)
                        <div class="col-md-4">
                            <div class="h-100 d-flex flex-column">
                                <p class="text-muted small fw-semibold mb-2">
                                    <i class="bi bi-camera me-1"></i> Bukti Timbangan
                                </p>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                                    <a href="{{ asset('uploads/bukti/'.$pesanan->gambar_bukti_timbangan) }}"
                                       target="_blank" title="Klik untuk lihat full size">
                                        <img src="{{ asset('uploads/bukti/'.$pesanan->gambar_bukti_timbangan) }}"
                                             alt="Bukti Timbangan"
                                             class="img-fluid rounded-3 shadow-sm"
                                             style="max-height:180px;object-fit:cover;width:100%;cursor:pointer;
                                                    transition:transform 0.2s;"
                                             onmouseover="this.style.transform='scale(1.03)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                </div>
                                <small class="text-muted text-center mt-2 d-block">Klik gambar untuk memperbesar</small>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
