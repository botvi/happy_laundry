<nav class="navbar">
    <ul class="navbar-list">
      <li class="navbar-item">
        <a href="{{ url('/') }}" class="navbar-link font-custom-standar2 {{ Request::is('/') ? 'active' : '' }}" data-nav-link>Home</a>
      </li>
      <li class="navbar-item">
        <a href="{{ url('/skuy') }}" class="navbar-link font-custom-standar2 {{ Request::is('skuy') ? 'active' : '' }}" data-nav-link>Skuy Buat</a>
      </li>
      <li class="navbar-item">
        <a href="/profil" class="navbar-link font-custom-standar2 {{ Request::is('profil') ? 'active' : '' }}" data-nav-link>Profil</a>
      </li>
      {{-- <li class="navbar-item">
        <a href="/profil" class="navbar-link font-custom-standar2 {{ Request::is('profil') ? 'active' : '' }}" data-nav-link>Profil</a>
      </li> --}}
    </ul>
</nav>