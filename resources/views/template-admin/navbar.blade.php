<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header justify-content-center">
            <a href="/dashboard-superadmin" class="b-brand text-primary">
                <img src="{{ asset('env') }}/hitam.png" alt="Logo" style="height: 60px;">
            </a>
        </div>
        @if (Auth::user()->role == 'superadmin')
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="/dashboard-superadmin" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Data Linkskuy</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('whatsapp-api.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-brand-whatsapp"></i></span>
                            <span class="pc-mtext">API Whatsapp</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('manage-testimoni.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-message-circle"></i></span>
                            <span class="pc-mtext">Testimon Pelanggan</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('manage-pelanggan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-user"></i></span>
                            <span class="pc-mtext">Pelanggan</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('brand.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-briefcase"></i></span>
                            <span class="pc-mtext">Manage Brand</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>
