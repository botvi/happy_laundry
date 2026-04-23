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
                            <li class="breadcrumb-item" aria-current="page">Data Pelanggan</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Data Pelanggan</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No. WA</th>
                                <th>Alamat</th>
                                <th>Total Pesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggans as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->no_wa ?? '-' }}</td>
                                <td>{{ $user->pelanggan->alamat ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $user->pelanggan ? $user->pelanggan->pesanan()->count() : 0 }} Pesanan
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('pelanggan.show', $user->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                    <form action="{{ route('pelanggan.destroy', $user->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus pelanggan ini? Semua data pesanannya juga akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pelanggan terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection