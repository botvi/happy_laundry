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
                <li class="breadcrumb-item" aria-current="page">Pengaturan Diskon</li>
              </ul>
            </div>
            <div class="col-md-12 d-flex justify-content-between align-items-center">
              <div class="page-header-title">
                <h2 class="mb-0">Data Aturan Diskon</h2>
              </div>
              <a href="{{ route('diskon.create') }}" class="btn btn-primary">Tambah Diskon</a>
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
                      <th>Minimal Jumlah / Berat</th>
                      <th>Satuan</th>
                      <th>Tipe Diskon</th>
                      <th>Nilai Diskon</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($diskons as $diskon)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>>= {{ (float)$diskon->minimal_berat_kg }}</td>
                      <td>
                        @if(($diskon->satuan ?? 'kg') == 'kg')
                          <span class="badge bg-light-primary text-primary">Kg</span>
                        @else
                          <span class="badge bg-light-success text-success">Helai</span>
                        @endif
                      </td>
                      <td>{{ ucfirst($diskon->tipe_diskon) }}</td>
                      <td>
                          @if($diskon->tipe_diskon == 'persen')
                              {{ $diskon->nilai_diskon }}%
                          @else
                              Rp {{ number_format($diskon->nilai_diskon, 0, ',', '.') }}
                          @endif
                      </td>
                      <td>
                        <a href="{{ route('diskon.edit', $diskon->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('diskon.destroy', $diskon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus aturan diskon ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                      </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada aturan diskon yang ditambahkan.</td>
                    </tr>
                    @endforelse
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
