@extends('template-user')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-modern p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3 shadow" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h2 class="fw-bold">{{ $user->name }}</h2>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>

                    <form action="{{ route('user.profil.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly disabled>
                            <small class="text-muted">Email tidak dapat diubah.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No WhatsApp</label>
                            <input type="text" name="no_wa" class="form-control" value="{{ $user->no_wa }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ $pelanggan->alamat ?? '' }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-modern px-5">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
