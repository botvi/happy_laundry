<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Linkskuy - Home</title>

  <!-- favicon -->
  <link rel="shortcut icon" href="{{ asset('linkskuy') }}/assets/images/logo.ico" type="image/x-icon">

  <!-- custom css link -->
  <link rel="stylesheet" href="{{ asset('linkskuy') }}/assets/css/style.css">

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
          <h2 class="h2 article-title font-custom">LINKSKUY !</h2>
        </header>

        <section class="about-text" style="margin-bottom: 50px;">
          <div style="display: flex; align-items: center; gap: 20px;">
            <p class="font-custom-standar" style="margin: 0; text-align: justify;">
              Linkskuy adalah web pembuat link bio super kece dan kekinian, pas banget buat anak Gen Z yang pengen
              tampil beda! Dengan Linkskuy, semua link pentingmu bisa dikumpulin dalam satu halaman dengan gaya yang
              super keren dan fitur yang up to date. Web ini gampang banget dipakai, bikin profil onlinemu makin
              estetik, unik, dan pastinya makin eksis di dunia maya!
            </p>
          </div>
          <div class="font-custom-secondary" style="text-align: center;">
            <a class="button-custom-shine" href="{{ url('/skuy') }}"
              style="margin-top: 20px; display: inline-block; padding: 10px 25px; background-color: #22c44d; color: #fff; border-radius: 8px; border: 2px solid #222; font-size: 1rem; font-family: inherit; cursor: pointer; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.2s, color 0.2s, box-shadow 0.2s; transform: rotate(-2deg);"
              onmouseover="this.style.backgroundColor='#222';this.style.color='#fff';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.15)';this.style.transform='scale(1.05)';"
              onmouseout="this.style.backgroundColor='#22c44d';this.style.color='#fff';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.07)';this.style.transform='rotate(-2deg)';">
              Skuy Jadi Keren !
            </a>
          </div>
        </section>

        <!-- testimonials -->
        <section class="testimonials" style="margin-bottom: 50px;">
          <h3 class="h3 testimonials-title font-custom">Apa Nih Kata Mereka ?</h3>

          <ul class="testimonials-list has-scrollbar">
            @foreach ($testimonis as $testimoni)
            <li class="testimonials-item">
              <div class="content-card" data-testimonials-item>
                @php
                  $fotoProfile = $testimoni->user->foto_profile ?? null;
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
                <figure class="testimonials-avatar-box">
                  <img src="{{ $srcFoto }}" alt="{{ $testimoni->user->name }}" width="60"
                    data-testimonials-avatar>
                </figure>

                <h4 class="h4 testimonials-item-title font-custom-standar" data-testimonials-title>{{ $testimoni->user->name }}</h4>

                <div class="testimonials-text" data-testimonials-text>
                  <p class="font-custom-standar">
                    {{ $testimoni->pesan }}
                  </p>
                </div>
              </div>
            </li>
            @endforeach
          </ul>
        </section>

        <!-- testimonials modal -->
        @if(isset($testimonis) && count($testimonis) > 0)
        @php
          // Ambil testimoni pertama untuk modal default
          $modalTestimoni = $testimonis[0];
          $fotoProfile = $modalTestimoni->user->foto_profile ?? null;
          if ($fotoProfile) {
              if (Str::startsWith($fotoProfile, ['http://', 'https://'])) {
                  $srcFoto = $fotoProfile;
              } else {
                  $srcFoto = asset('uploads/foto_profile/' . $fotoProfile);
              }
          } else {
              $srcFoto = asset('env/logo.jpg');
          }
        @endphp
        <div class="modal-container" data-modal-container>
          <div class="overlay" data-overlay></div>

          <section class="testimonials-modal">
            <button class="modal-close-btn" data-modal-close-btn>
              <ion-icon name="close-outline"></ion-icon>
            </button>

            <div class="modal-img-wrapper">
              <figure class="modal-avatar-box">
                <img src="{{ $srcFoto }}" alt="{{ $modalTestimoni->user->name }}" width="80" data-modal-img>
              </figure>

              <img src="{{ asset('linkskuy') }}/assets/images/icon-quote.svg" alt="quote icon">
            </div>

            <div class="modal-content">
              <h4 class="h3 modal-title font-custom-standar" data-modal-title>{{ $modalTestimoni->user->name }}</h4>

              <time datetime="{{ $modalTestimoni->created_at->format('Y-m-d') }}" class="font-custom-standar" >{{ $modalTestimoni->created_at->format('d M Y') }}</time>

              <div data-modal-text>
                <p class="font-custom-standar">
                  {{ $modalTestimoni->pesan }}
                </p>
              </div>
            </div>
          </section>
        </div>
        @else
        <div class="modal-container" data-modal-container>
          <div class="overlay" data-overlay></div>
          <section class="testimonials-modal">
            <button class="modal-close-btn" data-modal-close-btn>
              <ion-icon name="close-outline"></ion-icon>
            </button>
            <div class="modal-img-wrapper">
              <figure class="modal-avatar-box">
                <img src="{{ asset('env/logo.jpg') }}" alt="Tidak ada testimoni" width="80" data-modal-img>
              </figure>
              <img src="{{ asset('linkskuy') }}/assets/images/icon-quote.svg" alt="quote icon">
            </div>
            <div class="modal-content">
              <h4 class="h3 modal-title font-custom-standar" data-modal-title>Tidak Ada Testimoni</h4>
              <div data-modal-text>
                <p class="font-custom-standar">
                  Belum ada testimoni yang tersedia.
                </p>
              </div>
            </div>
          </section>
        </div>
        @endif

        <!-- clients -->
        <section class="clients">
          <h3 class="h3 clients-title font-custom">Bestie Brand</h3>

          <ul class="clients-list has-scrollbar">
            @foreach ($brands as $brand)
            <li class="clients-item">
              <a href="{{ $brand->link_brand }}" target="_blank">
                  <img src="{{ asset('uploads/logo_brand/' . $brand->logo_brand) }}" alt="client logo" style="width: 150px; height: 150px; object-fit: contain;">
                </a>
            </li>
            @endforeach

           
          </ul>
        </section>
      </article>
    </div>
  </main>

  <!-- custom js link -->
  <script src="{{ asset('linkskuy') }}/assets/js/script.js"></script>
  @include('sweetalert::alert')

  <!-- ionicon link -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>