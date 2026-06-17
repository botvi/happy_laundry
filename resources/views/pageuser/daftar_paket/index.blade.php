@extends('template-user')

@section('content')
<div class="py-5 bg-light mb-5" style="background: linear-gradient(135deg, rgba(255, 117, 85, 0.05), rgba(255, 152, 112, 0.02)); border-bottom: 1px solid rgba(255,117,85,0.1);">
    <div class="container text-center pt-4 pb-2">
        <h1 class="display-5 fw-bold mb-3" style="color: var(--dark);">Pilih Paket <span class="gradient-text">Laundry Anda</span></h1>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">Layanan premium dengan harga terjangkau. Percayakan pakaian kotor Anda pada ahli kami dan nikmati waktu luang Anda.</p>
    </div>
</div>

<div class="container mb-5 pb-5">
    <div class="row g-4 justify-content-center">
        @foreach($pakets as $paket)
        <div class="col-md-4">
            <div class="card card-modern h-100 p-4 border-0">
                <div class="card-body d-flex flex-column text-center position-relative">
                    <div class="mb-4">
                        <div class="icon-box" style="width: 80px; height: 80px; font-size: 2.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">
                                <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                <path d="m21.37 4.07-5-2A.9.9 0 0 0 16 2h-1c-.55 0-1 .45-1 1 0 1.1-.9 2-2 2s-2-.9-2-2c0-.55-.45-1-1-1H8c-.13 0-.25.02-.37.07l-5 2A1 1 0 0 0 2 5v6c0 .55.45 1 1 1h2v9c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-9h2c.55 0 1-.45 1-1V5a1 1 0 0 0-.63-.93M20 10h-3v10H7V10H4V5.68l4.13-1.65c.45 1.71 2.02 2.98 3.87 2.98s3.41-1.26 3.87-2.98L20 5.68z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="card-title fw-bold mb-3" style="color: var(--dark);">{{ $paket->nama_paket }}</h3>
                    <p class="text-muted flex-grow-1 mb-4">{{ $paket->deskripsi }}</p>
                    <div class="mt-auto">
                        <div class="p-3 rounded-4 mb-4" style="background: rgba(255, 117, 85, 0.05);">
                            <h3 class="gradient-text fw-bold mb-0">Rp {{ number_format($paket->harga_paket_per_kg, 0, ',', '.') }} <span class="text-muted fs-6 fw-normal">/ {{ $paket->satuan ?? 'kg' }}</span></h3>
                        </div>
                        <a href="{{ route('user.pesanan.create', ['paket_id' => $paket->id]) }}" class="btn btn-modern w-100 py-3 shadow-sm">
                            <i class="bi bi-cart-plus me-2"></i> Pilih Paket Ini
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
