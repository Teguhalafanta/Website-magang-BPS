@extends('kerangka.master')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pelajar.produk.index') }}"
                                class="text-decoration-none">Produk Magang</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Produk</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary">Edit Produk Magang</h3>
                <p class="text-muted">Perbarui informasi produk magang Anda</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Form Edit Produk</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('pelajar.produk.update', $produk->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1"></i>Nama Produk
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama_produk"
                                    value="{{ old('nama_produk', $produk->nama_produk) }}"
                                    class="form-control form-control-lg @error('nama_produk') is-invalid @enderror"
                                    placeholder="Masukkan nama produk" required>
                                @error('nama_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-file-text me-1"></i>Deskripsi
                                </label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="5"
                                    placeholder="Jelaskan detail tentang produk ini...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opsional - Berikan deskripsi singkat tentang produk</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-check me-1"></i>File Saat Ini
                                </label>
                                <div class="alert alert-light border d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="bi bi-paperclip text-primary me-2"></i>
                                        <span class="text-muted">File tersimpan</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $produk->file_produk) }}"
                                        class="btn btn-success btn-sm shadow-sm" target="_blank">
                                        <i class="bi bi-download me-1"></i>Download File
                                    </a>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-cloud-upload me-1"></i>Upload File Baru
                                </label>
                                <input type="file" name="file_produk"
                                    class="form-control @error('file_produk') is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                                @error('file_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Opsional - Biarkan kosong jika tidak ingin mengubah file. Format: PDF, DOC, DOCX, PPT,
                                    PPTX, ZIP, RAR
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-check-circle me-2"></i>Update Produk
                                </button>
                                <a href="{{ route('pelajar.produk.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="card border-0 bg-light mt-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-2">
                            <i class="bi bi-lightbulb text-warning me-2"></i>Tips:
                        </h6>
                        <ul class="mb-0 small text-muted">
                            <li>Pastikan nama produk jelas dan deskriptif</li>
                            <li>Tambahkan deskripsi untuk memperjelas konteks produk</li>
                            <li>File baru hanya perlu diupload jika ada pembaruan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
