@extends('template-user')

@section('content')
<div class="container my-5 py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <a href="{{ route('user.komplain.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm border">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Komplain
                </a>
            </div>

            <div class="card card-modern p-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <h4 class="fw-bold mb-0" style="color: var(--dark);">Detail Komplain</h4>
                        @if($komplain->status == 'menunggu')
                            <span class="badge bg-warning rounded-pill px-3 py-2 text-dark fs-6">Menunggu</span>
                        @elseif($komplain->status == 'diproses')
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">Diproses</span>
                        @else
                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6">Selesai</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <p class="text-muted small mb-1">Subjek:</p>
                        <h5 class="fw-bold">{{ $komplain->subjek }}</h5>
                    </div>

                    <div class="row mb-4 bg-light p-3 rounded-4 mx-0">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <p class="text-muted small mb-1"><i class="bi bi-calendar3 me-1"></i> Tanggal Dikirim</p>
                            <span class="fw-semibold">{{ $komplain->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1"><i class="bi bi-tag me-1"></i> Terkait Pesanan</p>
                            @if($komplain->pesanan_id)
                                <span class="fw-semibold">Pesanan #{{ $komplain->pesanan_id }}</span>
                            @else
                                <span class="text-muted fst-italic">Tidak ada</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-5">
                        <p class="text-muted small fw-bold mb-2">Pesan Anda:</p>
                        <div class="p-3 bg-white border rounded-3" style="font-size: 1rem;">
                            {!! nl2br(e($komplain->pesan)) !!}
                        </div>
                    </div>

                    <div>
                        <p class="text-muted small fw-bold mb-2">Balasan / Tanggapan Admin:</p>
                        @if($komplain->balasan)
                            <div class="p-4 rounded-4" style="background-color: rgba(255, 117, 85, 0.08); border-left: 4px solid var(--primary);">
                                <h6 class="fw-bold" style="color: var(--primary);"><i class="bi bi-headset me-2"></i> Customer Service</h6>
                                <p class="mb-0 mt-2" style="font-size: 1rem; color: var(--dark);">
                                    {!! nl2br(e($komplain->balasan)) !!}
                                </p>
                            </div>
                        @else
                            <div class="p-4 rounded-4 text-center border bg-light text-muted">
                                <i class="bi bi-clock-history fs-3 mb-2 d-block"></i>
                                Komplain Anda sedang kami tinjau. Mohon kesediaannya menunggu balasan dari tim kami.
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
