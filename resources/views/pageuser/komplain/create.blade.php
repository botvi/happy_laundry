@extends('template-user')

@section('content')
<div class="container my-5 py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-modern p-4 border-0">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold" style="color: var(--dark);">Ajukan Komplain / Masukan</h3>
                        <p class="text-muted">Sampaikan keluhan atau pertanyaan Anda, kami akan merespons secepatnya.</p>
                    </div>

                    <form action="{{ route('user.komplain.store') }}" method="POST">
                        @csrf
                        
                        @if($pesanan_id)
                            <div class="alert alert-info d-flex align-items-center border-0 rounded-3 shadow-sm mb-4">
                                <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
                                <div>
                                    Komplain ini akan dikaitkan dengan <strong>Pesanan #{{ $pesanan_id }}</strong>.
                                    <input type="hidden" name="pesanan_id" value="{{ $pesanan_id }}">
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-bold">Subjek <span class="text-danger">*</span></label>
                            <input type="text" name="subjek" class="form-control" required placeholder="Contoh: Baju masih ada noda kotor" value="{{ old('subjek') }}">
                            @error('subjek')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Pesan / Keluhan <span class="text-danger">*</span></label>
                            <textarea name="pesan" class="form-control" rows="6" required placeholder="Tuliskan detail komplain atau masukan Anda di sini...">{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('user.komplain.index') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-modern px-5 rounded-pill">Kirim Komplain</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
