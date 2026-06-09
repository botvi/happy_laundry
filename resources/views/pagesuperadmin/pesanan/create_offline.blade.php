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
                <li class="breadcrumb-item" aria-current="page">Tambah Pesanan Offline</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Tambah Pesanan Offline</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-sm-8">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <form action="{{ route('pesanan.store_offline') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group mb-3">
                  <label class="form-label">Nama Pelanggan</label>
                  <input type="text" name="nama_pelanggan" class="form-control" required placeholder="Masukkan nama pelanggan">
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">No WhatsApp Pelanggan</label>
                  <input type="text" name="no_wa" class="form-control" required placeholder="Contoh: 081234567890">
                  <small class="text-muted">Gunakan format angka saja (misal: 0812xxx atau 62812xxx).</small>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Pilih Paket Laundry</label>
                  <select name="paket_laundry_id" id="paketSelect" class="form-control" required>
                    <option value="" data-satuan="kg">-- Pilih Paket --</option>
                    @foreach($pakets as $paket)
                      <option value="{{ $paket->id }}" data-satuan="{{ $paket->satuan ?? 'kg' }}">
                        {{ $paket->nama_paket }} (Rp {{ number_format($paket->harga_paket_per_kg, 0, ',', '.') }} / {{ $paket->satuan ?? 'kg' }})
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label" id="jumlahLabel">Jumlah / Berat</label>
                  <input type="number" step="0.1" name="jumlah_kilogram" id="jumlahInput" class="form-control" required placeholder="Masukkan jumlah/berat">
                </div>
                
                <div class="form-group mb-3" id="buktiTimbanganGroup">
                  <label class="form-label">Bukti Timbangan (Gambar)</label>
                  <input type="file" name="gambar_bukti_timbangan" class="form-control" accept="image/*">
                  <small class="text-muted">Opsional untuk bukti visual timbangan berat.</small>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Status Pesanan</label>
                  <select name="status_pesanan" id="statusSelect" class="form-control" required>
                    <option value="menunggu_timbangan">Menunggu Timbangan / Dihitung</option>
                    <option value="diproses" selected>Diproses</option>
                    <option value="selesai">Selesai</option>
                  </select>
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Catatan Pelanggan / Catatan Tambahan (Opsional)</label>
                  <textarea name="catatan_pelanggan" class="form-control" rows="3" placeholder="Tulis catatan atau detail pelanggan offline (misal: Nama/No HP pelanggan)"></textarea>
                </div>

                <div class="text-end mt-4">
                  <a href="{{ route('pesanan.index') }}" class="btn btn-secondary">Kembali</a>
                  <button type="submit" class="btn btn-primary">Simpan & Hitung Total</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const paketSelect = document.getElementById('paketSelect');
    const jumlahLabel = document.getElementById('jumlahLabel');
    const jumlahInput = document.getElementById('jumlahInput');
    const buktiTimbanganGroup = document.getElementById('buktiTimbanganGroup');
    const statusSelect = document.getElementById('statusSelect');

    function updateFormFields() {
      const selectedOption = paketSelect.options[paketSelect.selectedIndex];
      if (!selectedOption) return;
      
      const satuan = selectedOption.getAttribute('data-satuan') || 'kg';

      if (satuan === 'helai') {
        jumlahLabel.textContent = 'Jumlah (Helai)';
        jumlahInput.setAttribute('step', '1');
        jumlahInput.placeholder = 'Contoh: 5';
        buktiTimbanganGroup.style.display = 'none';
        
        // Update status Menunggu option text
        statusSelect.options[0].textContent = 'Menunggu Dihitung';
      } else {
        jumlahLabel.textContent = 'Jumlah Berat (Kg)';
        jumlahInput.setAttribute('step', '0.1');
        jumlahInput.placeholder = 'Contoh: 5.4';
        buktiTimbanganGroup.style.display = 'block';
        
        // Update status Menunggu option text
        statusSelect.options[0].textContent = 'Menunggu Timbangan';
      }
    }

    paketSelect.addEventListener('change', updateFormFields);
    updateFormFields(); // Run once initially
  });
</script>
@endsection
