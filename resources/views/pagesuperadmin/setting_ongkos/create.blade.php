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
                <li class="breadcrumb-item"><a href="/dashboard-asisten">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Data Elemen</a></li>
                <li class="breadcrumb-item" aria-current="page">Form Tambah Data Elemen</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Form Tambah Data Elemen</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="row justify-content-center">
        <!-- [ form-element ] start -->
        <div class="col-sm-6">
          <!-- Basic Inputs -->
          <div class="card">
            <div class="card-header">
              <h5>Form Tambah Data Elemen</h5>
            </div>
            <div class="card-body">
              <form action="{{ route('dataelemen.store') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label class="form-label">Tag Elemen</label>
                  <input type="text" name="tag_elemen" class="form-control" placeholder="Tag Elemen" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Nama Elemen</label>
                  <input type="text" name="nama_elemen" class="form-control" placeholder="Nama Elemen" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Deskripsi</label>
                  <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Icon Elemen</label>
                  <input type="text" name="icon_elemen" class="form-control" placeholder="Icon Elemen" required>
                </div>
                <div class="form-group">
                  <label class="form-label">HTML Elemen</label>
                  <textarea name="html_elemen" class="form-control" placeholder="HTML Elemen" required></textarea>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary me-2">Submit</button>
                  <button type="reset" class="btn btn-light">Reset</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection