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
                <li class="breadcrumb-item"><a href="{{ route('pesanan.index') }}">Pesanan</a></li>
                <li class="breadcrumb-item" aria-current="page">Detail</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Detail Pesanan</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-sm-8">
          <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Nama Pelanggan</th>
                        <td>: {{ $pesanan->pelanggan->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Paket Laundry</th>
                        <td>: {{ $pesanan->paketLaundry->nama_paket ?? '-' }} (Rp {{ number_format($pesanan->paketLaundry->harga_paket_per_kg ?? 0, 0, ',', '.') }} / kg)</td>
                    </tr>
                    <tr>
                        <th>Dijemput</th>
                        <td>: {{ $pesanan->dijemput == 'ya' ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                    <tr>
                        <th>Diantar</th>
                        <td>: {{ $pesanan->diantar == 'ya' ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                    <tr>
                        <th>Ongkir Antar Jemput</th>
                        <td>: Rp {{ number_format($pesanan->ongkir_antar_jemput ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Berat</th>
                        <td>: {{ $pesanan->jumlah_kilogram ?? 0 }} Kg</td>
                    </tr>
                    <tr>
                        <th>Total Harga Keseluruhan</th>
                        <td>: <strong>Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: {{ strtoupper($pesanan->status_pesanan) }}</td>
                    </tr>
                    <tr>
                        <th>Bukti Timbangan</th>
                        <td>: 
                            @if($pesanan->gambar_bukti_timbangan)
                                <br><img src="{{ asset('uploads/bukti/'.$pesanan->gambar_bukti_timbangan) }}" alt="Bukti Timbangan" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 10px;">
                            @else
                                Belum ada bukti.
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="text-end mt-4">
                  <a href="{{ route('pesanan.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
