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
                <li class="breadcrumb-item"><a href="/dashboard-superadmin">Home</a></li>
                  <li class="breadcrumb-item"><a href="javascript: void(0)">Pelanggan</a></li>
                <li class="breadcrumb-item" aria-current="page">Tabel Pelanggan</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Tabel Pelanggan</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="row">
        <!-- Zero config table start -->
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tabel Pelanggan</h5>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="simpletable" class="table table-striped table-bordered nowrap">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Foto</th>
                      <th>Tanggal Bergabung</th>
                      <th>Nama</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>No Whatsapp</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($pelanggan as $e => $item)
                    <tr>
                      <td>{{ $e+1 }}</td>
                      <td>
                        @php
                          $fotoProfile = $item->foto_profile ?? null;
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
                        @if($item)
                          <img src="{{ $srcFoto }}" alt="Foto" width="40" height="40" style="object-fit:cover; border-radius:10%;">
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>{{ $item->created_at->format('d-m-Y') }}</td>
                      <td>{{ $item->name ?? '-' }}</td>
                      <td>{{ $item->username ?? '-' }}</td>
                      <td>{{ $item->email ?? '-' }}</td>
                      <td>{{ $item->no_wa ?? '-' }}</td>
                      <td>
                        <form action="{{ route('manage-pelanggan.destroy', $item->id) }}" method="POST" style="display:inline;" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>No</th>
                      <th>Foto</th>
                      <th>Tanggal Bergabung</th>
                      <th>Nama</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>No Whatsapp</th>
                      <th>Aksi</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Zero config table end -->
      </div>
    </div>
  </section>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sweetalert untuk hapus
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

    });
</script>
<script>
    $(document).ready(function() {
      $('#simpletable').DataTable();
    });
</script>
@endsection