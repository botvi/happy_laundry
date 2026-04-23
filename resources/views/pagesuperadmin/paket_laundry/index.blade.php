@extends('template-admin.layout')

@section('content')
<section class="pc-container">
    <div class="pc-content">
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard-superadmin">Home</a></li>
                <li class="breadcrumb-item" aria-current="page">Paket Laundry</li>
              </ul>
            </div>
            <div class="col-md-12 d-flex justify-content-between align-items-center">
              <div class="page-header-title">
                <h2 class="mb-0">Data Paket Laundry</h2>
              </div>
              <a href="{{ route('paket-laundry.create') }}" class="btn btn-primary">Tambah Paket</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Paket</th>
                      <th>Deskripsi</th>
                      <th>Harga per Kg (Rp)</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($pakets as $paket)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $paket->nama_paket }}</td>
                      <td>{{ $paket->deskripsi }}</td>
                      <td>{{ number_format($paket->harga_paket_per_kg, 0, ',', '.') }}</td>
                      <td>
                        <a href="{{ route('paket-laundry.edit', $paket->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('paket-laundry.destroy', $paket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection