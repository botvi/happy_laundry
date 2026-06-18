@extends('template-user')

@extends('template-user')

@section('content')
<div class="container my-5 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="text-center mb-4 pb-2">
                <h2 class="fw-bold" style="color: var(--dark);">Profil <span class="gradient-text">Saya</span></h2>
                <p class="text-muted">Kelola informasi data diri dan akun Anda</p>
            </div>
            
            <div class="card card-modern border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-5 pb-3 border-bottom">
                        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle mb-3 shadow-md" style="width: 120px; height: 120px; font-size: 3rem; background: var(--gradient);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h3 class="fw-bold mb-1" style="color: var(--dark);">{{ $user->name }}</h3>
                        <p class="text-muted mb-0"><i class="bi bi-envelope-at me-2"></i>{{ $user->email }}</p>
                    </div>

                    <form action="{{ route('user.profil.update') }}" method="POST">
                        @csrf
                        
                        <h5 class="fw-bold mb-4" style="color: var(--dark);"><i class="bi bi-person-lines-fill me-2 gradient-text"></i> Informasi Pribadi</h5>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0" value="{{ $user->name }}" required>
                                </div>
                            </div>
                           
                            <div class="col-md-12">
                                <label class="form-label">No WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-muted"></i></span>
                                    <input type="text" name="no_wa" class="form-control border-start-0 ps-0" value="{{ $user->no_wa }}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap untuk keperluan antar jemput" required>{{ $pelanggan->alamat ?? '' }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">
                        
                        <h5 class="fw-bold mb-4" style="color: var(--dark);"><i class="bi bi-shield-lock me-2 gradient-text"></i> Keamanan Akun</h5>

                        <div class="mb-5">
                            <label class="form-label">Password Baru <span class="text-muted fw-normal">(Kosongkan jika tidak ingin mengubah)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Masukkan password baru">
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-modern px-5 py-3 shadow-md w-100 w-md-auto d-inline-flex justify-content-center align-items-center">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
