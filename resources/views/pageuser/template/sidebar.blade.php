    <!-- #SIDEBAR -->
    <aside class="sidebar" data-sidebar>
        <div class="sidebar-info">
            <figure class="avatar-box">
                @php
                    use Illuminate\Support\Str;
                    $fotoProfile = Auth::user()->foto_profile ?? null;
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
                <img src="{{ $srcFoto }}" alt="{{ Auth::user()->name ?? 'Linkskuy' }}">
            </figure>

            <div class="info-content">
                @if (Auth::check())
                    <h1 class="name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</h1>
                @else
                    <h1 class="name" title="Linkskuy">Linkskuy</h1>
                @endif
                <div style="margin-top: 10px; display: flex; gap: 8px;">
                    @if (Auth::check())
                        <a class="button-custom-shine" href="{{ url('/profil') }}"
                            style="padding: 6px 16px; border-radius: 6px; background: linear-gradient(135deg, #a0e9afcc, #38b000cc); color: #fff; border: 2px solid rgba(255,255,255,0.6); backdrop-filter: blur(2px);">
                            Profil
                        </a>
                        <a class="button-custom-shine" href="{{ url('/logout') }}"
                            style="padding: 6px 16px; border-radius: 6px; background: linear-gradient(135deg, #ff6b6bcc, #c91818cc); color: #fff; border: 2px solid rgba(255,255,255,0.6); backdrop-filter: blur(2px);">
                            Logout
                        </a>
                    @else
                        <a class="button-custom-shine" href="{{ route('login') }}"
                            style="padding: 6px 16px; border-radius: 6px; background: linear-gradient(135deg, #ffe066cc, #ffd700cc); color: #fff; border: 2px solid rgba(255,255,255,0.6); backdrop-filter: blur(2px);">
                            Login
                        </a>
                        <a class="button-custom-shine" href="{{ route('register') }}"
                            style="padding: 6px 16px; border-radius: 6px; background: linear-gradient(135deg, #6ec1e4cc, #007cf0cc); color: #fff; border: 2px solid rgba(255,255,255,0.6); backdrop-filter: blur(2px);">
                            Register
                        </a>
                    @endif
                </div>
            </div>

            <button class="info_more-btn" data-sidebar-btn>
                <span>Show Contacts</span>
                <ion-icon name="chevron-down"></ion-icon>
            </button>
        </div>

        <div class="sidebar-info_more">
            <div class="separator"></div>

            <ul class="contacts-list">
                @if (Auth::check())
                <li class="contact-item">
                    <div class="icon-box">
                        <ion-icon name="mail-outline"></ion-icon>
                    </div>
                    <div class="contact-info">
                        <p class="contact-title">Email</p>
                        <a href="mailto:{{ Auth::user()->email }}" class="contact-link">{{ Auth::user()->email }}</a>
                    </div>
                </li>
                @if(Auth::user()->no_wa ?? false)
                <li class="contact-item">
                    <div class="icon-box">
                        <ion-icon name="logo-whatsapp"></ion-icon>
                    </div>
                    <div class="contact-info">
                        <p class="contact-title">Whatsapp</p>
                        <a href="https://wa.me/{{ Auth::user()->no_wa }}" class="contact-link">{{ Auth::user()->no_wa }}</a>
                    </div>
                </li>
                @endif
                @else
              
                @endif
            </ul>

            <div class="separator"></div>

            <ul class="social-list">
                <li class="social-item">
                    <a href="https://www.youtube.com/@pandekakode" target="_blank" class="social-link">
                        <ion-icon name="logo-youtube"></ion-icon>
                    </a>
                </li>

                <li class="social-item">
                    <a href="https://www.tiktok.com/@pandekakode" target="_blank" class="social-link">
                        <ion-icon name="logo-tiktok"></ion-icon>
                    </a>
                </li>

                <li class="social-item">
                    <a href="https://www.instagram.com/yoviard_" target="_blank" class="social-link">
                        <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
