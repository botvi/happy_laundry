<nav class="navbar">
    <ul class="navbar-list">
      <li class="navbar-item">
        <a href="{{ url('/') }}" class="navbar-link font-custom-standar2 {{ Request::is('/') ? 'active' : '' }}" data-nav-link>Home</a>
      </li>
      <li class="navbar-item">
        <a href="{{ url('/skuy') }}" class="navbar-link font-custom-standar2 {{ Request::is('skuy') ? 'active' : '' }}" data-nav-link>Skuy Buat</a>
      </li>
      <li class="navbar-item">
        <a href="#" class="navbar-link font-custom-standar2 {{ Request::is('about-dev') ? 'active' : '' }}" data-nav-link>About Dev</a>
      </li>
    </ul>
</nav>