@extends('template-user')

@section('content')
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3" style="color: #2b3a55;">Pilih Paket Laundry Anda</h1>
        <p class="lead text-muted">Layanan premium dengan harga terjangkau. Percayakan pakaian Anda pada kami.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        @foreach($pakets as $paket)
        <div class="col-md-4">
            <div class="card card-modern h-100 p-3">
                <div class="card-body d-flex flex-column text-center">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle" style="width: 64px; height: 64px;">
                            <i class="bi bi-box-seam text-primary fs-2"></i>
                        </div>
                    </div>
                    <h3 class="card-title fw-bold">{{ $paket->nama_paket }}</h3>
                    <p class="text-muted flex-grow-1">{{ $paket->deskripsi }}</p>
                    <div class="mt-auto">
                        <h4 class="text-primary fw-bold mb-4">Rp {{ number_format($paket->harga_paket_per_kg, 0, ',', '.') }} <small class="text-muted fs-6 fw-normal">/ kg</small></h4>
                        <a href="{{ route('user.pesanan.create', ['paket_id' => $paket->id]) }}" class="btn btn-modern w-100">Pilih Paket</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
