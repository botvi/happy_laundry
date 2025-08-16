<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkskuy - Profil</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('linkskuy') }}/assets/images/logo.ico" type="image/x-icon">

    <!-- custom css link -->
    <link rel="stylesheet" href="{{ asset('linkskuy') }}/assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('linkskuy') }}/assets/css/profilstyle.css">

    <!-- google font link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- #MAIN -->
    <main>
        @include('pageuser.template.sidebar')

        <!-- #main-content -->
        <div class="main-content">
            <!-- #NAVBAR -->
            @include('pageuser.template.navbar')
            <article class="home active" data-page="home">
                <header>
                    <h2 class="h2 article-title font-custom">PROFILMU!</h2>
                </header>

                <section class="about-text" style="margin-bottom: 50px;">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <p class="font-custom-standar" style="margin: 0; text-align: justify;">
                            Welcome to profil lo, bestie! Di sini lo bisa atur-atur info akun lo biar makin kece dan
                            pastinya aman.
                            Jangan pernah spill password atau data sensi ke siapa pun ya, bro/sis! Username yang lo
                            bikin bakal dipake
                            buat akses profil lo, jadi usahain yang unik, gampang diinget, tapi tetep anti mainstream.
                            Pokoknya, keep
                            akun lo rapet, jangan sampe bocor ke orang-orang random. Stay safe, stay keren!
                        </p>
                    </div>
                </section>

                <!-- Profil Form Section -->
                <section class="profile-section">
                    <div class="profile-container">
                        <!-- Form Profil -->
                        <div class="profile-form-container">
                            <form class="profile-form" id="profileForm" action="{{ route('profil.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!-- Foto Profil -->
                                <div class="form-group">
                                    <div class="profile-photo-input-container">
                                        <div class="profile-photo-preview" id="profile-photo-preview"
                                            onclick="document.getElementById('profile-photo').click()">
                                            @php
                                                use Illuminate\Support\Str;
                                                $fotoProfile = $data->foto_profile ?? auth()->user()->foto_profile ?? null;
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
                                            <img src="{{ $srcFoto }}" alt="Foto Profil"
                                                class="profile-photo-img" id="profile-photo-img">
                                            <div class="photo-upload-overlay">
                                                <ion-icon name="camera-outline"></ion-icon>
                                                <span>Klik untuk ganti foto</span>
                                            </div>
                                        </div>

                                        <input type="file" id="profile-photo" name="foto_profile" accept="image/*"
                                            style="display: none !important;" onchange="handlePhotoChange(this)">

                                        <small class="form-help">
                                            Klik foto untuk mengganti. Format: JPG, PNG, GIF (Max: 2MB)
                                        </small>
                                    </div>
                                </div>

                                <!-- Nama Lengkap -->
                                <div class="form-group">
                                    <label for="fullname" class="form-label">
                                        <ion-icon name="person-outline"></ion-icon>
                                        Nama Lengkap
                                    </label>
                                    <input type="text" id="fullname" name="name" value="{{ $data->name ?? auth()->user()->name ?? '' }}" class="form-input"
                                        placeholder="Masukkan nama lengkap Anda" required>
                                </div>

                                <!-- Username -->
                                <div class="form-group">
                                    <label for="username" class="form-label">
                                        <ion-icon name="at-outline"></ion-icon>
                                        Username
                                    </label>
                                    <input type="text" id="username" name="username" value="{{ $data->username ?? auth()->user()->username ?? '' }}" class="form-input"
                                        placeholder="Masukkan username unik" required>
                                    <small class="form-help">
                                        Username akan digunakan untuk login dan URL profil Anda
                                    </small>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <ion-icon name="mail-outline"></ion-icon>
                                        Email
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ $data->email ?? auth()->user()->email ?? '' }}" class="form-input"
                                        placeholder="Masukkan alamat email" required>
                                </div>

                                <!-- No HP -->
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <ion-icon name="logo-whatsapp"></ion-icon>
                                        No Whatsapp
                                    </label>
                                    <input type="tel" id="phone" name="no_wa" value="{{ $data->no_wa ?? auth()->user()->no_wa ?? '' }}" class="form-input"
                                        placeholder="Masukkan nomor Whatsapp" required>
                                </div>

                                <!-- Action Buttons -->
                                <div class="form-actions">
                                    <button type="submit" class="save-profile-btn button-custom-shine">
                                        <ion-icon name="save-outline"></ion-icon>
                                        <span>Simpan Perubahan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </article>
        </div>
    </main>

    <!-- custom js link -->
    <script src="{{ asset('linkskuy') }}/assets/js/script.js"></script>
    
    <!-- JavaScript sederhana hanya untuk preview foto -->
    <script>
        // Fungsi untuk preview foto saat dipilih
        function handlePhotoChange(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validasi ukuran file (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                
                // Validasi tipe file
                if (!file.type.startsWith('image/')) {
                    alert('Pilih file gambar yang valid! Format: JPG, PNG, GIF');
                    input.value = '';
                    return;
                }
                
                // Preview foto
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-photo-img').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    
    @include('sweetalert::alert')

    <!-- ionicon link -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
