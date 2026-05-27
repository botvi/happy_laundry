@extends('template-user')

@section('content')
<div class="container my-5 py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h2 class="fw-bold" style="color: var(--dark);">Pusat <span class="gradient-text">Bantuan</span></h2>
            <p class="text-muted mb-0">Keluhan atau pertanyaan Anda akan dibantu oleh tim kami.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('user.komplain.create') }}" class="btn btn-modern px-4 py-2 shadow-sm rounded-pill d-inline-flex align-items-center">
                <i class="bi bi-pencil-square me-2"></i> Ajukan Komplain Baru
            </a>
        </div>
    </div>

    @if($komplains->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <div class="display-1 text-muted mb-3"><i class="bi bi-chat-square-dots"></i></div>
            <h4 class="text-muted">Belum Ada Komplain</h4>
            <p class="text-muted mb-4">Anda belum pernah mengajukan komplain atau masukan.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($komplains as $k)
            <div class="col-md-6">
                <div class="card card-modern h-100 p-3 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-bold mb-0" style="color: var(--dark);">{{ Str::limit($k->subjek, 40) }}</h5>
                            @if($k->status == 'menunggu')
                                <span class="badge bg-warning rounded-pill px-3 py-2 text-dark">Menunggu</span>
                            @elseif($k->status == 'diproses')
                                <span class="badge bg-primary rounded-pill px-3 py-2">Diproses</span>
                            @else
                                <span class="badge bg-success rounded-pill px-3 py-2">Selesai</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-calendar3 me-1"></i> {{ $k->created_at->format('d M Y, H:i') }}
                            @if($k->pesanan_id)
                                &nbsp; | &nbsp; <i class="bi bi-tag me-1"></i> Pesanan #{{ $k->pesanan_id }}
                            @endif
                        </p>
                        <p class="text-secondary" style="font-size: 0.9rem;">{{ Str::limit($k->pesan, 80) }}</p>
                        
                        <a href="{{ route('user.komplain.show', $k->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Lihat Detail & Balasan</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
