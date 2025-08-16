<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Meta tag untuk share: gambar profil dan username -->
    @if ($link && isset($link->data_link['profil_pengguna']))
        @php
            $profile = $link->data_link['profil_pengguna'];
            $username = $profile['username'] ?? 'Username';
            $description =
                $profile['deskripsi'] ??
                'Ini adalah deskripsi singkat tentang pengguna. Tulis sesuatu yang menarik tentang dirimu di sini.';

            // Perbaikan: cek apakah foto_profil sudah berupa URL atau path lokal
            $profileImageRaw = $profile['foto_profil'] ?? null;
            if ($profileImageRaw) {
                // Jika sudah mengandung http atau https, berarti sudah URL lengkap
                if (Str::startsWith($profileImageRaw, ['http://', 'https://'])) {
                    $profileImage = $profileImageRaw;
                } else {
                    // Jika path lokal, gunakan asset()
                    $profileImage = asset($profileImageRaw);
                }
            } else {
                $profileImage = asset('env/logo.jpg');
            }
        @endphp
        
        <title>LinkSkuy - {{ $username }}</title>
        <link rel="shortcut icon" href="{{ $profileImage }}" type="image/x-icon">
        
        <!-- Open Graph Meta Tags -->
        <meta property="og:image" content="{{ $profileImage }}">
        <meta property="og:title" content="{{ $username }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:type" content="profile">
        <meta property="og:site_name" content="LinkSkuy">
        <meta property="og:url" content="{{ request()->url() }}">
        
        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $username }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ $profileImage }}">
        
        <!-- Additional Meta Tags -->
        <meta name="description" content="{{ $description }}">
        <meta name="author" content="{{ $username }}">
        <meta name="keywords" content="LinkSkuy, {{ $username }}, profile, links">
    @else
        <title>LinkSkuy - Profile</title>
        <link rel="shortcut icon" href="{{ asset('env/logo.jpg') }}" type="image/x-icon">
        
        <!-- Default Open Graph Meta Tags -->
        <meta property="og:image" content="{{ asset('env/logo.jpg') }}">
        <meta property="og:title" content="LinkSkuy - Profile">
        <meta property="og:description" content="Ini adalah deskripsi singkat tentang pengguna. Tulis sesuatu yang menarik tentang dirimu di sini.">
        <meta property="og:type" content="profile">
        <meta property="og:site_name" content="LinkSkuy">
        <meta property="og:url" content="{{ request()->url() }}">
        
        <!-- Default Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="LinkSkuy - Profile">
        <meta name="twitter:description" content="Ini adalah deskripsi singkat tentang pengguna. Tulis sesuatu yang menarik tentang dirimu di sini.">
        <meta name="twitter:image" content="{{ asset('env/logo.jpg') }}">
        
        <!-- Default Additional Meta Tags -->
        <meta name="description" content="Ini adalah deskripsi singkat tentang pengguna. Tulis sesuatu yang menarik tentang dirimu di sini.">
        <meta name="keywords" content="LinkSkuy, profile, links">
    @endif
    
    <!-- CSS dan JavaScript -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('web/assets/style.css') }}">
    
    <!-- Additional Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="revisit-after" content="7 days">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body style="
    background: #ffffff;
    background-image: url('{{ asset('linkskuy/assets/images/logo-6-color.png') }}');
    background-repeat: repeat;
    background-size: 50px;
">
    <!-- Content Area - Isi konten di sini sekali saja -->
    <div id="main-content" style="display: none;">
        <!-- Bagikan Button -->
        <button class="template-btn" title="Bagikan"
            style="border: 2px solid #181818; border-radius: 50px; position: fixed; top: 12px; right: 12px; z-index: 1000;">
            <i class="fa-solid fa-arrow-up-from-bracket"></i>
        </button>

        <!-- Konten Utama -->
        <div class="main-content">
            @php
                // Define all available sections
                $allSections = [
                    'profil_pengguna' => 'profil_pengguna',
                    'grid_produk' => 'grid_produk',
                    'tombol_link' => 'tombol_link',
                    'youtube_embeded' => 'youtube_embeded',
                    'sosial_media' => 'sosial_media',
                    'portfolio_project' => 'portfolio_project',
                    'gambar_thumbnail' => 'gambar_thumbnail',
                    'spotify_embed' => 'spotify_embed',
                ];

                // Use order from database or default order
                $displayOrder = !empty($order) ? $order : array_keys($allSections);
            @endphp

            @foreach ($displayOrder as $sectionId)
                @if (isset($allSections[$sectionId]) && !in_array($sectionId, $hidden ?? []))
                    @switch($sectionId)
                        @case('profil_pengguna')
                            <!-- Profil Pengguna -->
                            <section id="profil_pengguna" class="flex flex-col items-center justify-center py-8">
                                @if ($link && isset($link->data_link['profil_pengguna']) && !empty($link->data_link['profil_pengguna']['username']))
                                    @php
                                        $profile = $link->data_link['profil_pengguna'];
                                        $username = $profile['username'];
                                        $description = $profile['deskripsi'] ?? '';
                                        $profileImage = $profile['foto_profil'] ?? '';
                                    @endphp
                                    @if ($profileImage)
                                        <img src="{{ asset($profileImage) }}" alt="Foto Profil"
                                            class="rounded-full mb-4 w-64 h-64 object-cover border-4 border-white shadow-lg">
                                    @endif
                                    @if ($username)
                                        <h2 class="font-bold text-xl mb-3 text-gray-800">{{ $username }}</h4>
                                    @endif
                                    @if ($description)
                                        <p class="text-center text-gray-600 mb-0 max-w-sm" style="font-size: 1.1rem;">{{ $description }}</p>
                                    @endif
                                @endif
                            </section>
                        @break

                        @case('grid_produk')
                            <!-- Grid Produk -->
                            <section id="grid_produk" class="flex flex-col items-center justify-center py-8 px-4">
                                @if (
                                    $link &&
                                        isset($link->data_link['grid_produk']) &&
                                        is_array($link->data_link['grid_produk']) &&
                                        count($link->data_link['grid_produk']) > 0)
                                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Unggulan</h2>

                                    <!-- Search Bar -->
                                    <div class="w-full max-w-md mb-6">
                                        <div class="relative">
                                            <input type="text" id="searchInput" placeholder="Cari produk..."
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            <i
                                                class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 w-full max-w-md" id="productGrid">
                                        @foreach ($link->data_link['grid_produk'] as $product)
                                            @if (isset($product['foto_produk']) && isset($product['link_produk']) && isset($product['harga']))
                                                <a href="{{ $product['link_produk'] }}" class="group block">
                                                    <div
                                                        class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 cursor-pointer relative">
                                                        <img src="{{ asset($product['foto_produk']) }}"
                                                            class="w-full h-full object-cover absolute inset-0" alt="Produk">
                                                        <div
                                                            class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-90 text-white p-3">
                                                            <h5 class="font-bold text-sm mb-1 text-shadow">
                                                                {{ $product['nama_produk'] ?? 'Produk' }}</h5>
                                                            <p class="text-green-300 font-bold text-xs">{{ $product['harga'] }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </section>
                        @break

                        @case('tombol_link')
                            <!-- Tombol -->
                            <section id="tombol_link" class="flex flex-col items-center justify-center py-4">
                                @if (
                                    $link &&
                                        isset($link->data_link['tombol_link']) &&
                                        is_array($link->data_link['tombol_link']) &&
                                        count($link->data_link['tombol_link']) > 0)
                                    <div class="flex flex-col justify-center w-full gap-4">
                                        @foreach ($link->data_link['tombol_link'] as $linkItem)
                                            @if (isset($linkItem['nama_link']) && isset($linkItem['link_tombol']))
                                                <a href="{{ $linkItem['link_tombol'] }}"
                                                    class="template-btn-link w-full max-w-md flex justify-center">
                                                    <div
                                                        class="bg-white/60 backdrop-blur-md hover:bg-white/80 text-black font-bold py-3 px-6 rounded-xl shadow-lg border border-black transition-all duration-300 cursor-pointer w-full text-center">
                                                        {{ $linkItem['nama_link'] }}
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </section>
                        @break

                        @case('youtube_embeded')
                            <!-- Youtube embeded -->
                            <section id="youtube_embeded" class="flex flex-col items-center justify-center py-4">
                                @if (
                                    $link &&
                                        isset($link->data_link['youtube_embeded']) &&
                                        isset($link->data_link['youtube_embeded']['header_youtube']) &&
                                        isset($link->data_link['youtube_embeded']['embeded_youtube']) &&
                                        is_array($link->data_link['youtube_embeded']['embeded_youtube']) &&
                                        count($link->data_link['youtube_embeded']['embeded_youtube']) > 0)
                                    @php
                                        $youtube = $link->data_link['youtube_embeded'];
                                    @endphp
                                    <div class="video-container relative">
                                        <div class="video-info mt-4 text-center">
                                            <h3 class="text-lg font-semibold text-dark-800 mb-2">
                                                {{ $youtube['header_youtube'] }}</h3>
                                            @if (isset($youtube['deskripsi_header']) && !empty($youtube['deskripsi_header']))
                                                <p class="text-dark-600 text-sm">{{ $youtube['deskripsi_header'] }}</p>
                                            @endif
                                        </div>
                                        @foreach ($youtube['embeded_youtube'] as $embed)
                                            @if (!empty($embed))
                                                <div
                                                    class="video-player bg-gray-900 rounded-xl overflow-hidden shadow-2xl mt-2">
                                                    <div class="video-wrapper relative">
                                                        {!! $embed !!}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </section>
                        @break

                        @case('sosial_media')
                            <!-- Tombol Sosial Media -->
                            <section id="sosial_media" class="flex flex-col items-center justify-center py-8 px-4">
                                @if (
                                    $link &&
                                        isset($link->data_link['sosial_media']) &&
                                        is_array($link->data_link['sosial_media']) &&
                                        count($link->data_link['sosial_media']) > 0)
                                    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Sosial Media</h2>

                                    <div class="social-media-grid flex flex-wrap justify-center items-center gap-4 w-full">
                                        <div class="w-full flex justify-center">
                                            @foreach ($link->data_link['sosial_media'] as $social)
                                                @if (isset($social['active']) && $social['active'] && isset($social['link']) && !empty($social['link']))
                                                    <a href="{{ $social['link'] }}"
                                                        class="social-media-btn flex justify-center items-center mx-2">
                                                        @switch(strtolower($social['platform'] ?? ''))
                                                            @case('youtube')
                                                                <i class="fab fa-youtube"></i>
                                                            @break

                                                            @case('facebook')
                                                                <i class="fab fa-facebook-f"></i>
                                                            @break

                                                            @case('instagram')
                                                                <i class="fab fa-instagram"></i>
                                                            @break

                                                            @case('spotify')
                                                                <i class="fab fa-spotify"></i>
                                                            @break

                                                            @case('linkedin')
                                                                <i class="fab fa-linkedin-in"></i>
                                                            @break

                                                            @case('tiktok')
                                                                <i class="fab fa-tiktok"></i>
                                                            @break

                                                            @case('telegram')
                                                                <i class="fab fa-telegram-plane"></i>
                                                            @break

                                                            @case('whatsapp')
                                                                <i class="fab fa-whatsapp"></i>
                                                            @break

                                                            @default
                                                                <i class="fas fa-share-alt"></i>
                                                        @endswitch
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </section>
                        @break

                        @case('portfolio_project')
                            <!-- Project Portfolio -->
                            <section id="portfolio_project" class="flex flex-col items-center justify-center py-8 px-4">
                                @if (
                                    $link &&
                                        isset($link->data_link['portfolio_project']) &&
                                        is_array($link->data_link['portfolio_project']) &&
                                        count($link->data_link['portfolio_project']) > 0)
                                    <div class="flex flex-col gap-6 w-full max-w-md">
                                        <!-- Header Section -->
                                        <div class="text-center mb-6">
                                            <h2 class="text-2xl font-bold text-gray-800 mb-3">Portfolio Project</h2>
                                            <p class="text-gray-600 text-sm leading-relaxed">
                                                Lihat koleksi project terbaru yang telah kami kerjakan dengan teknologi modern
                                            </p>
                                        </div>

                                        @foreach ($link->data_link['portfolio_project'] as $project)
                                            @if (isset($project['gambar_project']) && isset($project['judul_project']) && isset($project['link_project']))
                                                <div class="project-container">
                                                    <div class="project-card">
                                                        <div class="project-wrapper">
                                                            <img src="{{ asset($project['gambar_project']) }}"
                                                                alt="{{ $project['judul_project'] }}" class="project-image">
                                                            <div class="project-overlay">
                                                                <div class="overlay-content">
                                                                    <h4 class="project-title">{{ $project['judul_project'] }}
                                                                    </h4>
                                                                    @if (isset($project['deskripsi_project']) && !empty($project['deskripsi_project']))
                                                                        <p class="project-description">
                                                                            {{ $project['deskripsi_project'] }}</p>
                                                                    @endif
                                                                    <a href="{{ $project['link_project'] }}"
                                                                        class="project-btn text-black">Lihat Project</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </section>
                        @break

                        @case('gambar_thumbnail')
                            <!-- Gambar Thumbnail -->
                            <section id="gambar_thumbnail" class="flex flex-col items-center justify-center py-8">
                                @if (
                                    $link &&
                                        isset($link->data_link['gambar_thumbnail']['gambar_thumbnail']) &&
                                        !empty($link->data_link['gambar_thumbnail']['gambar_thumbnail']))
                                    <img src="{{ asset($link->data_link['gambar_thumbnail']['gambar_thumbnail']) }}"
                                        alt="Thumbnail"
                                        class="mb-4 w-64 h-36 sm:w-80 sm:h-44 md:w-[400px] md:h-56 object-cover border-4 border-white shadow-lg rounded-xl"
                                        style="aspect-ratio: 16/9;">
                                @endif
                            </section>
                        @break

                        @case('spotify_embed')
                            <!-- Spotify Embed -->
                            <section id="spotify_embed"
                                class="flex flex-col items-center justify-center py-8 rounded-xl shadow-lg">
                                @if (
                                    $link &&
                                        isset($link->data_link['spotify_embed']['embeded_spotify']) &&
                                        is_array($link->data_link['spotify_embed']['embeded_spotify']) &&
                                        count($link->data_link['spotify_embed']['embeded_spotify']) > 0)
                                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Spotify</h3>
                                    <div class="flex flex-col gap-6 w-full max-w-xl">
                                        @foreach ($link->data_link['spotify_embed']['embeded_spotify'] as $embed)
                                            @if (!empty($embed))
                                                <div class="spotify-embed">
                                                    {!! $embed !!}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </section>
                        @break
                    @endswitch
                @endif
            @endforeach
        </div>

        <!-- Footer -->
        <div id="footer" class="footer">
            <div class="flex items-center justify-center flex-wrap gap-2">
                <img src="{{ asset('env/hitam.png') }}" alt="copyright" class="w-5 h-5">
                <span class="text-sm">

                    Bergabung dengan {{ $link->user->name }} di <a href="https://linkskuy.link"
                        target="_blank">Linkskuy</a>
                </span>
            </div>
        </div>
    </div>

    <!-- iPhone Mockup - Responsive untuk semua device -->
    <div class="iphone-mockup">
        <div class="iphone-screen">
            <div id="iphone-content"
                @if ($backgroundCustom) @if ($backgroundCustom['type'] === 'image')
                        style="background-image: url('{{ asset($backgroundCustom['image']) }}'); background-position: center; background-size: cover;"
                    @elseif($backgroundCustom['type'] === 'color')
                        @if (isset($backgroundCustom['color_secondary']) && $backgroundCustom['color_secondary'])
                            style="background: linear-gradient({{ $backgroundCustom['color'] }}, {{ $backgroundCustom['color_secondary'] }});"
                        @else
                            style="background-color: {{ $backgroundCustom['color'] }};" @endif
            @elseif($backgroundCustom['type'] === 'gradient')
                style="background: linear-gradient({{ $backgroundCustom['gradient']['direction'] }}, {{ $backgroundCustom['gradient']['color1'] }}, {{ $backgroundCustom['gradient']['color2'] }});"
                @endif
            @else
                style="background-image: url('{{ asset('env/bg.jpg') }}'); background-position: center; background-size: cover;"
                @endif>
            </div>
        </div>
    </div>

    <script src="{{ asset('web/assets/script.js') }}"></script>
</body>

</html>
