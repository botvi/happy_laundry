@extends('template-admin.layout')

@section('content')
    <section class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard-superadmin">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Profil</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Profil Superadmin</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Informasi Profil</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profil-superadmin.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <!-- Foto Profile -->
                                <div class="row mb-4">
                                    <div class="col-12 text-center">
                                        <div class="position-relative d-inline-block">
                                            <div class="avatar avatar-xxl rounded-circle border">
                                                @if($data->foto_profile)
                                                    @php
                                                        $fotoProfile = $data->foto_profile ?? null;
                                                        if ($fotoProfile) {
                                                            // Jika sudah berupa URL lengkap
                                                            if (Str::startsWith($fotoProfile, ['http://', 'https://'])) {
                                                                $srcFoto = $fotoProfile;
                                                            } else {
                                                                // Jika path lokal, gunakan asset()
                                                                $srcFoto = asset('uploads/foto_profile/' . $fotoProfile);
                                                            }
                                                        } else {
                                                            $srcFoto = asset('env/logo.jpg');
                                                        }
                                                    @endphp
                                                    <img src="{{ $srcFoto }}" 
                                                         alt="Foto Profile" 
                                                         class="avatar-img rounded-circle"
                                                         style="width: 120px; height: 120px; object-fit: cover;">
                                                @else
                                                    <div class="avatar-initials rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                                         style="width: 120px; height: 120px; font-size: 3rem; color: white;">
                                                        {{ strtoupper(substr($data->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <label for="foto_profile" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer" 
                                                   style="width: 40px; height: 40px; cursor: pointer;">
                                                <i class="ti ti-camera"></i>
                                            </label>
                                            <input type="file" id="foto_profile" name="foto_profile" class="d-none" accept="image/*">
                                        </div>
                                        <p class="text-muted mt-2 mb-0">Klik ikon kamera untuk mengubah foto</p>
                                    </div>
                                </div>

                                <!-- Informasi Dasar -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $data->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('username') is-invalid @enderror" 
                                               id="username" 
                                               name="username" 
                                               value="{{ old('username', $data->username) }}" 
                                               required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $data->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="no_wa" class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('no_wa') is-invalid @enderror" 
                                               id="no_wa" 
                                               name="no_wa" 
                                               value="{{ old('no_wa', $data->no_wa) }}" 
                                               placeholder="Contoh: 08123456789"
                                               required>
                                        @error('no_wa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Kosongkan jika tidak ingin mengubah">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Minimal 8 karakter</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Konfirmasi password baru">
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="row mt-4">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </section>
@endsection

@section('script')
<script>
    // Preview foto sebelum upload
    document.getElementById('foto_profile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarImg = document.querySelector('.avatar-img');
                if (avatarImg) {
                    avatarImg.src = e.target.result;
                } else {
                    // Jika tidak ada gambar, buat elemen img baru
                    const avatarDiv = document.querySelector('.avatar-initials');
                    if (avatarDiv) {
                        avatarDiv.style.display = 'none';
                        const newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.alt = 'Preview';
                        newImg.className = 'avatar-img rounded-circle';
                        newImg.style.width = '120px';
                        newImg.style.height = '120px';
                        newImg.style.objectFit = 'cover';
                        avatarDiv.parentNode.appendChild(newImg);
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Validasi password confirmation
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const confirmPassword = document.getElementById('password_confirmation');
        
        if (password && confirmPassword.value) {
            if (password !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Password tidak cocok');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
    });

    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password && confirmPassword) {
            if (password !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        }
    });
</script>
@endsection
