<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Linkskuy - Skuy Buat</title>

  <!-- favicon -->
  <link rel="shortcut icon" href="{{ asset('linkskuy') }}/assets/images/logo.ico" type="image/x-icon">

  <!-- custom css link -->
  <link rel="stylesheet" href="{{ asset('linkskuy') }}/assets/css/style.css">
  <link rel="stylesheet" href="{{ asset('linkskuy') }}/assets/css/skuystyle.css">

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
          <h2 class="h2 article-title font-custom">BUAT LINKMU!</h2>
        </header>

        <section class="about-text" style="margin-bottom: 50px;">
          <div style="display: flex; align-items: center; gap: 20px;">
            <p class="font-custom-standar" style="margin: 0; text-align: justify;">
              Cukup ketikkan username-mu, lalu cek apakah tersedia atau tidak. Setelah itu, kamu bisa langsung generate
              link untuk bio-mu nanti dengan mudah!
            </p>
          </div>
          <!-- Form cek link -->
          <form action="{{ route('skuy.store') }}" method="POST" style="margin-top: 35px; display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap;">
            @csrf
            <div style="display: flex; align-items: center; border: 1.5px solid #22c44d; border-radius: 6px; overflow: hidden; background: #fff;">
              <span class="font-custom-standar" style="font-size: 1.1rem; background: #f5f5f5; padding: 8px 10px; color: #222; border-right: 1.5px solid #22c44d;">
                linkskuy.link/
              </span>
              <input type="text" id="username-linkskuy" name="nama_link" placeholder="nama_linkmu" required
                style="padding: 8px 14px; border: none; outline: none; font-size: 1rem; font-family: inherit; min-width: 150px; background: transparent;">
            </div>
            <button type="submit" class="button-custom-shine"
              style="padding: 8px 18px; background-color: #22c44d; color: #fff; border-radius: 6px; border: 2px solid #222; font-size: 1rem; font-family: inherit; cursor: pointer; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.2s, color 0.2s, box-shadow 0.2s;">
             Skuy Buat!
            </button>
          </form>
        </section>


        <!-- Punya kamu link keren ini ? -->
        <section class="testimonials" style="margin-bottom: 50px;">
          <h3 class="h3 testimonials-title font-custom">Punyamu ? Pantesan keren!</h3>

          <ul class="testimonials-list has-scrollbar">
            @if(Auth::check())
              @if(isset($links) && count($links) > 0)
                @php
                  $adaLink = false;
                @endphp
                @foreach ($links as $link)
                  @if($link->nama_link)
                    @php $adaLink = true; @endphp
                    <li class="testimonials-item">
                      <div class="content-card link-card" data-testimonials-item style="overflow: hidden;">
                        <div class="" data-testimonials-text style="width: 100%;">
                          <a href="{{ route('preview', $link->nama_link) }}" class="font-custom-standar2" target="_blank"
                            style="color: #5e7c5e; background: #edca06; display: block; transform: rotate(-2.5deg); font-weight: 600; box-shadow: 0 2px 8px rgba(34,196,77,0.13); text-decoration: none; width: 100%; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: 2px 8px;">
                            {{ route('preview', $link->nama_link) }}
                          </a>
                        </div>
                        <div class="link-actions" style="display: flex; justify-content: center; align-items: center; gap: 12px;">
                          <a class="button-custom-shine edit-btn" href="{{ route('editor', ['kode_unik' => $link->kode_unik, 'nama_link' => $link->nama_link]) }}" target="_blank" style="text-decoration: none;">
                            Skuy Edit!
                          </a>
                          <button class="button-custom-shine hapus-btn" onclick="confirmDelete({{ $link->id }}, '{{ $link->nama_link }}')">
                            Hapus !
                          </button>
                        </div>
                      </div>
                    </li>
                  @endif
                @endforeach
                @if(!$adaLink)
                  <li class="testimonials-item">
                    <div class="content-card link-card" data-testimonials-item style="overflow: hidden;">
                      <div class="" data-testimonials-text style="width: 100%;">
                        <span class="font-custom-standar2" 
                          style="color: #b91c1c; background: #fff3cd; display: block; font-weight: 600; text-align: center; width: 100%; max-width: 100%; padding: 8px;">
                          Belum ada link yang kamu buat. Yuk buat link pertamamu!
                        </span>
                      </div>
                    </div>
                  </li>
                @endif
              @else
                <li class="testimonials-item">
                  <div class="content-card link-card" data-testimonials-item style="overflow: hidden;">
                    <div class="" data-testimonials-text style="width: 100%;">
                      <span class="font-custom-standar2" 
                        style="color: #b91c1c; background: #fff3cd; display: block; font-weight: 600; text-align: center; width: 100%; max-width: 100%; padding: 8px;">
                        Belum ada link yang kamu buat. Yuk buat link pertamamu!
                      </span>
                    </div>
                  </div>
                </li>
              @endif
            @else
              <li class="testimonials-item">
                <div class="content-card link-card" data-testimonials-item style="overflow: hidden;">
                  <div class="" data-testimonials-text style="width: 100%;">
                    <span class="font-custom-standar2" 
                      style="color: #b91c1c; background: #fff3cd; display: block; font-weight: 600; text-align: center; width: 100%; max-width: 100%; padding: 8px;">
                      Kamu belum login. Silakan login dulu untuk melihat dan mengedit link milikmu.
                    </span>
                  </div>
                  <div class="link-actions">
                    <a href="{{ route('login') }}" class="button-custom-shine edit-btn" style="text-decoration: none;">
                      Login Dulu
                    </a>
                  </div>
                </div>
              </li>
            @endif
          </ul>
        </section>

      

        <!-- testimonials -->
        <section class="clients">
          <h3 class="h3 clients-title font-custom">Ada pesan atau kesan buat gue ?</h3>

          <div class="testimonial-form-container">
            <form class="testimonial-form" action="{{ route('testimoni.store') }}" method="POST">
              @csrf
              <div class="form-group">
                <textarea 
                  class="testimonial-textarea" 
                  placeholder="Tulis pesan lo tentang gue di sini, bro! (minimal 255 karakter) 😊"
                  rows="4"
                  required
                  name="pesan"
                  id="pesanTestimoni"
                  oninput="updateCharCount()"
                ></textarea>
                <div style="margin-top: 6px; text-align: right;">
                  <span id="charCount" style="font-size: 0.95em; color: #888;">
                    0 / 255 karakter
                  </span>
                </div>
              </div>
              
              <div class="form-actions">
                <button type="submit" class="submit-testimonial-btn button-custom-shine">
                  Kirim Testimoni
                </button>
              </div>
            </form>
          </div>

          <script>
            function updateCharCount() {
              var textarea = document.getElementById('pesanTestimoni');
              var charCount = textarea.value.length;
              var minChar = 255;
              var charCountSpan = document.getElementById('charCount');
              charCountSpan.textContent = charCount + ' / ' + minChar + ' karakter';
              if(charCount < minChar) {
                charCountSpan.style.color = '#b91c1c';
              } else {
                charCountSpan.style.color = '#22c44d';
              }
            }
            // Inisialisasi saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
              updateCharCount();
            });
          </script>

        </section>
      </article>
    </div>
  </main>

  <!-- custom js link -->
  <script src="{{ asset('linkskuy') }}/assets/js/script.js"></script>
  @include('sweetalert::alert')


  <!-- sweetalert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Script untuk konfirmasi hapus -->
  <script>
    function confirmDelete(linkId, linkName) {
      Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Yakin lu mau hapus link "${linkName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yakin, Hapus!',
        cancelButtonText: 'Gak Jadi deh',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect ke route hapus
          window.location.href = '{{ route("skuy.destroy", "") }}/' + linkId;
        }
      });
    }
  </script>

  <!-- ionicon link -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>