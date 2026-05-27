@extends('template-user')

@section('styles')
<style>
    .hero-section {
        position: relative;
        padding: 100px 0;
        overflow: hidden;
        background: #fafbfc;
    }
    .hero-bg-shape {
        position: absolute;
        top: -10%;
        right: -5%;
        width: 50%;
        height: 120%;
        background: linear-gradient(135deg, rgba(255, 117, 85, 0.1), rgba(255, 152, 112, 0.05));
        border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        z-index: 0;
        animation: morph 8s ease-in-out infinite alternate;
    }
    @keyframes morph {
        0% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
        100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
    }
    .hero-content {
        position: relative;
        z-index: 1;
    }
    .hero-image-wrapper {
        position: relative;
        z-index: 1;
    }
    .hero-image-wrapper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: -20px;
        right: 20px;
        bottom: -20px;
        border: 2px dashed rgba(255, 117, 85, 0.5);
        border-radius: 24px;
        z-index: -1;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-bg-shape"></div>
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-6 text-start pe-lg-5">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3 fw-bold shadow-sm" style="letter-spacing: 1px;">
                    <i class="bi bi-stars me-1"></i> LAYANAN LAUNDRY PREMIUM
                </span>
                <h1 class="display-4 fw-bold mb-4" style="color: var(--dark); line-height: 1.2;">
                    Laundry <span class="gradient-text">Bersih, Wangi,</span> & Cepat
                </h1>
                <p class="lead text-muted mb-5 fs-5">Percayakan pakaian kotor Anda kepada kami. Kami menjemput, mencuci, dan mengantarkannya kembali ke depan pintu Anda dengan kualitas terbaik.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('user.paket') }}" class="btn btn-modern px-5 py-3 fs-5 d-inline-flex align-items-center">
                        Lihat Paket <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
                
                <div class="d-flex align-items-center gap-4 mt-5 pt-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-4 gradient-text"></i>
                        <span class="fw-semibold text-secondary">Garansi Bersih</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-4 gradient-text"></i>
                        <span class="fw-semibold text-secondary">Tepat Waktu</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="hero-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?auto=format&fit=crop&q=80&w=800" alt="Laundry Service" class="img-fluid rounded-4 shadow-lg" style="object-fit: cover; height: 500px; width: 100%;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container my-5 py-5">
    <div class="text-center mb-5 pb-3">
        <h2 class="fw-bold mb-3" style="color: var(--dark); font-size: 2.5rem;">Mengapa Memilih Kami?</h2>
        <p class="text-muted fs-5">Keunggulan layanan Happy Laundry untuk kenyamanan Anda</p>
    </div>
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card card-modern h-100 p-4 border-0">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--dark);">Antar Jemput</h4>
                    <p class="text-muted">Tidak perlu keluar rumah. Kurir kami siap menjemput dan mengantar pakaian Anda langsung ke lokasi.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-modern h-100 p-4 border-0">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--dark);">Kualitas Terjamin</h4>
                    <p class="text-muted">Menggunakan deterjen premium dan perlakuan khusus untuk setiap jenis bahan pakaian Anda agar awet dan bersih.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-modern h-100 p-4 border-0">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--dark);">Tepat Waktu</h4>
                    <p class="text-muted">Selesai sesuai estimasi waktu. Pantau status pesanan laundry Anda secara real-time langsung dari website.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paket Laundry Section -->
<div class="container my-5 py-5" style="background-color: #ffffff; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div class="text-center mb-5 pb-3">
        <h2 class="fw-bold mb-3" style="color: var(--dark); font-size: 2.5rem;">Daftar Paket Laundry</h2>
        <p class="text-muted fs-5">Pilihan paket terbaik untuk kebutuhan Anda</p>
    </div>
    <div class="row g-4 justify-content-center">
        @forelse($pakets as $paket)
        <div class="col-md-6 col-lg-4">
            <div class="card card-modern h-100 p-2 border-0">
                <div class="card-body text-center p-4">
                    <div class="d-inline-flex justify-content-center align-items-center mb-4" style="width: 60px; height: 60px; border-radius: 50%; background: var(--gradient); color: white;">
                        <i class="bi bi-box2-heart fs-3"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--dark);">{{ $paket->nama_paket }}</h4>
                    <p class="text-muted mb-4">{{ $paket->deskripsi ?? 'Paket cuci premium dengan hasil maksimal' }}</p>
                    <div class="mb-4">
                        <span class="fs-2 fw-bold gradient-text">Rp {{ number_format($paket->harga_paket_per_kg, 0, ',', '.') }}</span>
                        <span class="text-muted">/ kg</span>
                    </div>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 rounded-pill py-2">Pilih Paket Ini</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-4">Belum ada paket laundry yang tersedia.</div>
        @endforelse
    </div>
</div>

<!-- Lokasi & Jam Operasional Section -->
<div class="container my-5 py-5">
    <div class="row g-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4" style="color: var(--dark); font-size: 2rem;">Lokasi Kami</h2>
            <div class="card card-modern border-0 overflow-hidden shadow-sm" style="height: 400px;">
                @if($setting && $setting->alamat_maps)
                    {!! $setting->alamat_maps !!}
                @else
                    <div class="d-flex h-100 align-items-center justify-content-center bg-light text-muted">
                        Lokasi Maps belum diatur
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-modern border-0 h-100 p-4 p-md-5 bg-primary text-white d-flex justify-content-center" style="background: var(--gradient) !important;">
                <h2 class="fw-bold mb-4 text-white"><i class="bi bi-clock-history me-2"></i> Jam Operasional</h2>
                <p class="fs-5 mb-4 opacity-75">Kami siap melayani kebutuhan laundry Anda setiap hari dengan jadwal berikut:</p>
                
                <div class="d-flex align-items-center justify-content-between p-4 bg-white rounded-4 shadow-sm mb-3" style="color: var(--dark);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box m-0" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-door-open text-primary"></i>
                        </div>
                        <span class="fs-4 fw-bold">Jam Buka</span>
                    </div>
                    <span class="fs-4 fw-bold gradient-text">{{ $setting && $setting->jam_buka ? \Carbon\Carbon::parse($setting->jam_buka)->format('H:i') : '08:00' }}</span>
                </div>

                <div class="d-flex align-items-center justify-content-between p-4 bg-white rounded-4 shadow-sm" style="color: var(--dark);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box m-0" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-door-closed text-danger"></i>
                        </div>
                        <span class="fs-4 fw-bold">Jam Tutup</span>
                    </div>
                    <span class="fs-4 fw-bold text-danger">{{ $setting && $setting->jam_tutup ? \Carbon\Carbon::parse($setting->jam_tutup)->format('H:i') : '20:00' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

