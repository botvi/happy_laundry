@extends('template-user')

@section('content')
<!-- Hero Section -->
<div class="hero-section text-center py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-start">
                <h1 class="display-4 fw-bold mb-4" style="color: #2b3a55;">Laundry Bersih, Wangi, & Cepat</h1>
                <p class="lead text-muted mb-4">Percayakan pakaian kotor Anda kepada kami. Kami menjemput, mencuci, dan mengantarkannya kembali ke depan pintu Anda dengan layanan terbaik.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('user.paket') }}" class="btn btn-modern px-4 py-3 fs-5">Lihat Paket Laundry</a>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <img src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?auto=format&fit=crop&q=80&w=800" alt="Laundry Service" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #2b3a55;">Mengapa Memilih Happy Laundry?</h2>
        <p class="text-muted">Keunggulan layanan kami untuk kenyamanan Anda</p>
    </div>
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card card-modern h-100 p-4">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-truck text-primary fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Antar Jemput</h4>
                    <p class="text-muted">Tidak perlu keluar rumah. Kurir kami siap menjemput dan mengantar pakaian Anda langsung ke lokasi.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-modern h-100 p-4">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check text-primary fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Kualitas Terjamin</h4>
                    <p class="text-muted">Menggunakan deterjen premium dan perlakuan khusus untuk setiap jenis bahan pakaian Anda.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-modern h-100 p-4">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-clock-history text-primary fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Tepat Waktu</h4>
                    <p class="text-muted">Selesai sesuai estimasi waktu. Pantau status pesanan laundry Anda secara real-time dari website.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
