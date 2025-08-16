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
                <li class="breadcrumb-item"><a href="{{ route('dashboard-superadmin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('brand.index') }}">Manajemen Brand</a></li>
                <li class="breadcrumb-item" aria-current="page">Edit Brand</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Edit Brand</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="row justify-content-center">
        <!-- [ form-element ] start -->
        <div class="col-sm-8">
          <!-- Basic Inputs -->
          <div class="card">
            <div class="card-header">
              <h5><i class="fas fa-edit me-2"></i>Form Edit Brand</h5>
            </div>
            <div class="card-body">
              <form action="{{ route('brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Logo Brand Saat Ini</label>
                            @if($brand->logo_brand)
                                <div class="mb-2">
                                    <img src="{{ asset('uploads/logo_brand/' . $brand->logo_brand) }}" 
                                         alt="Logo Brand" 
                                         class="img-thumbnail" 
                                         style="max-width: 150px; max-height: 150px;">
                                </div>
                            @else
                                <div class="text-muted mb-2">Tidak ada logo</div>
                            @endif
                            
                            <label class="form-label">Upload Logo Baru (Opsional)</label>
                            <input type="file" 
                                   name="logo_brand" 
                                   class="form-control @error('logo_brand') is-invalid @enderror" 
                                   accept="image/*">
                            <div class="form-text">
                                Format: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah logo.
                            </div>
                            @error('logo_brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Status Brand <span class="text-danger">*</span></label>
                            <select name="status_brand" 
                                    class="form-select @error('status_brand') is-invalid @enderror" 
                                    required>
                                <option value="">Pilih Status</option>
                                <option value="active" {{ old('status_brand', $brand->status_brand) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status_brand', $brand->status_brand) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status_brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Link Brand <span class="text-danger">*</span></label>
                    <input type="url" 
                           name="link_brand" 
                           class="form-control @error('link_brand') is-invalid @enderror" 
                           placeholder="https://example.com" 
                           value="{{ old('link_brand', $brand->link_brand) }}"
                           required>
                    <div class="form-text">
                        Masukkan URL lengkap brand (contoh: https://example.com)
                    </div>
                    @error('link_brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card-footer text-end">
                
                    <button type="submit" class="btn btn-primary me-3">
                        <i class="fas fa-save"></i> Update
                    </button>
                   
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('script')
<script>
    // Preview image sebelum upload
    document.querySelector('input[name="logo_brand"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validasi ukuran file
            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('Ukuran file terlalu besar. Maksimal 2MB');
                this.value = '';
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipe file tidak didukung. Gunakan format JPEG, PNG, JPG, GIF, atau SVG');
                this.value = '';
                return;
            }
        }
    });
</script>
@endsection