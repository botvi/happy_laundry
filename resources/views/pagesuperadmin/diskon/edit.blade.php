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
                <li class="breadcrumb-item"><a href="{{ route('diskon.index') }}">Pengaturan Diskon</a></li>
                <li class="breadcrumb-item" aria-current="page">Edit</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Edit Aturan Diskon</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <form action="{{ route('diskon.update', $diskon->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label class="form-label">Minimal Berat (KG) <span class="text-danger">*</span></label>
                  <input type="number" step="0.1" name="minimal_berat_kg" class="form-control" value="{{ $diskon->minimal_berat_kg }}" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Tipe Diskon <span class="text-danger">*</span></label>
                  <select name="tipe_diskon" class="form-control" required>
                      <option value="persen" {{ $diskon->tipe_diskon == 'persen' ? 'selected' : '' }}>Persen (%)</option>
                      <option value="nominal" {{ $diskon->tipe_diskon == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Nilai Diskon <span class="text-danger">*</span></label>
                  <input type="number" name="nilai_diskon" class="form-control" value="{{ $diskon->nilai_diskon }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('diskon.index') }}" class="btn btn-secondary">Batal</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
