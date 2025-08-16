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
                <li class="breadcrumb-item"><a href="javascript: void(0)">Manage Brand</a></li>
                <li class="breadcrumb-item" aria-current="page">Tabel Manage Brand</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Tabel Manage Brand</h2>
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
                <h5 class="mb-0">Tabel Manage Brand</h5>
                <a href="{{ route('brand.create') }}" class="btn btn-primary">Tambah Brand</a>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="simpletable" class="table table-striped table-bordered nowrap">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Logo Brand</th>
                      <th>Link Brand</th>
                      <th>Status Brand</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($brands as $e => $item)
                    <tr>
                      <td>{{ $e+1 }}</td>
                      <td>
                        @if($item->logo_brand)
                          <img src="{{ asset('uploads/logo_brand/' . $item->logo_brand) }}" alt="Logo Brand" class="img-fluid" style="max-width: 100px;">
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>{{ $item->link_brand }}</td>
                      <td>
                        @if($item->status_brand == 'active')
                          <span class="badge bg-success">Aktif</span>
                        @else
                          <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('brand.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('brand.destroy', $item->id) }}" method="POST" style="display:inline;" class="delete-form">
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
                      <th>Logo Brand</th>
                      <th>Link Brand</th>
                      <th>Status Brand</th>
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