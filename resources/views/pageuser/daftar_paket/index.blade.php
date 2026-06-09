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
                            <i class="bi bi-box-seam"></i>
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
