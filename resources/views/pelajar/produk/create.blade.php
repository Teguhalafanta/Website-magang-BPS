@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <div class="row mb-4">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pelajar.produk.index') }}"
                                class="text-decoration-none">Produk Magang</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Upload Produk</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary">Upload Produk Magang</h3>
                <p class="text-muted">Unggah hasil karya dan produk magang Anda</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Form Upload Produk</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('pelajar.produk.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1"></i>Nama Produk
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama_produk" value="{{ old('nama_produk') }}"
                                    class="form-control form-control-lg @error('nama_produk') is-invalid @enderror"
                                    placeholder="Contoh: Aplikasi Sistem Inventory" required>
                                @error('nama_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-file-text me-1"></i>Deskripsi
                                </label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="5"
                                    placeholder="Jelaskan tentang produk ini: fitur, teknologi yang digunakan, tujuan pembuatan, dll...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opsional - Berikan deskripsi lengkap tentang produk Anda</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-paperclip me-1"></i>File Produk
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="file_produk"
                                    class="form-control @error('file_produk') is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar,.mp4" required>
                                @error('file_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Format yang didukung:</strong> PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR, MP4
                                    <span class="text-primary">• Maksimal 20MB</span>
                                </div>
                            </div>

                            <div class="alert alert-info border-0 d-flex align-items-start">
                                <i class="bi bi-exclamation-circle me-2 mt-1"></i>
                                <div class="small">
                                    <strong>Catatan Penting:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Pastikan file yang diupload adalah hasil karya sendiri</li>
                                        <li>Periksa kembali file sebelum mengupload</li>
                                        <li>Gunakan nama file yang deskriptif</li>
                                    </ul>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-upload me-2"></i>Upload Produk
                                </button>
                                <a href="{{ route('pelajar.produk.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-2"></i>Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tips Card --}}
                <div class="card border-0 bg-light mt-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-lightbulb text-warning me-2"></i>Tips Upload Produk:
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <small class="text-muted">Beri nama produk yang jelas dan mudah dipahami</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <small class="text-muted">Sertakan deskripsi detail untuk dokumentasi</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <small class="text-muted">Kompres file jika ukuran terlalu besar</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <small class="text-muted">Pastikan file tidak rusak atau corrupt</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
