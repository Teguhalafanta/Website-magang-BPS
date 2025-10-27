@extends('kerangka.master')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}"
                                class="text-decoration-none">Kelola Produk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Produk</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-primary">
                    <i class="bi bi-pencil-square me-2"></i>Edit Produk Magang
                </h3>
                <p class="text-muted">Ubah informasi produk magang pelajar sebagai administrator</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                {{-- Product Info Card --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Pelajar:</small>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                        style="width: 30px; height: 30px;">
                                        <strong class="small">{{ substr($produk->pelajar->nama ?? 'T', 0, 1) }}</strong>
                                    </div>
                                    <span class="fw-semibold">{{ $produk->pelajar->nama ?? 'Tidak diketahui' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Tanggal Upload:</small>
                                <div class="mt-1">
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $produk->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Form Card --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-gear me-2"></i>Form Edit Produk
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST">
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
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="6"
                                    placeholder="Jelaskan detail tentang produk ini...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Opsional - Berikan deskripsi lengkap tentang produk
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-check me-1"></i>File Produk
                                </label>
                                <div class="alert alert-light border d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-paperclip text-primary fs-4 me-3"></i>
                                        <div>
                                            <div class="fw-semibold text-dark">File Tersimpan</div>
                                            <small class="text-muted">
                                                {{ basename($produk->file_produk) }}
                                            </small>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $produk->file_produk) }}"
                                        class="btn btn-success btn-sm shadow-sm" target="_blank">
                                        <i class="bi bi-download me-1"></i>Download
                                    </a>
                                </div>
                                <div class="alert alert-warning border-0 d-flex align-items-start">
                                    <i class="bi bi-exclamation-triangle me-2 mt-1"></i>
                                    <small>
                                        <strong>Catatan:</strong> File produk tidak dapat diubah melalui form ini.
                                        Pelajar harus mengupload ulang jika ingin mengganti file.
                                    </small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                </button>
                                <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Admin Info Card --}}
                <div class="card border-0 bg-light mt-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-2">
                            <i class="bi bi-shield-check text-success me-2"></i>Hak Akses Admin:
                        </h6>
                        <ul class="mb-0 small text-muted">
                            <li>Dapat mengubah nama dan deskripsi produk</li>
                            <li>Tidak dapat mengubah file produk (hanya pelajar yang bisa)</li>
                            <li>Perubahan akan tercatat dalam sistem</li>
                            <li>Pastikan perubahan sesuai dengan kebijakan institusi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
