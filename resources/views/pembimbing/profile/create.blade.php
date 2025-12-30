@extends('kerangka.master')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                {{-- Header Card --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center py-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-person-plus-fill text-primary fs-1"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Lengkapi Profil Pembimbing</h3>
                        <p class="text-muted mb-0">Silakan lengkapi data profil Anda untuk melanjutkan</p>
                    </div>
                </div>

                {{-- Form Card --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i>
                            Formulir Data Pembimbing
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        {{-- Tampilkan error validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                                    <div>
                                        <strong class="d-block mb-2">Terdapat kesalahan pada form:</strong>
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('pembimbing.profile.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Section: Data Pribadi --}}
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-person-badge me-2"></i>Data Pribadi
                                </h6>

                                <div class="row">
                                    {{-- Nama --}}
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- NIP --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">NIP</label>
                                        <input type="text" name="nip"
                                            class="form-control @error('nip') is-invalid @enderror"
                                            value="{{ old('nip') }}" placeholder="Masukkan NIP (opsional)">
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Jabatan --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Jabatan</label>
                                        <input type="text" name="jabatan"
                                            class="form-control @error('jabatan') is-invalid @enderror"
                                            value="{{ old('jabatan') }}" placeholder="Masukkan jabatan (opsional)">
                                        @error('jabatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tim --}}
                                    <div class="col-md-12 mb-3">
                                        <label for="tim" class="form-label fw-semibold">Tim Kerja</label>
                                        <select class="form-select @error('tim') is-invalid @enderror" id="tim"
                                            name="tim">
                                            <option value=""disabled selected>-- Pilih Tim Kerja --</option>
                                            <option value="Tim Umum" {{ old('tim') == 'Tim Umum' ? 'selected' : '' }}>
                                                Tim Umum
                                            </option>
                                            <option value="Tim Statistik Sosial"
                                                {{ old('tim') == 'Tim Statistik Sosial' ? 'selected' : '' }}>
                                                Tim Statistik Sosial
                                            </option>
                                            <option value="Tim Statistik Produksi"
                                                {{ old('tim') == 'Tim Statistik Produksi' ? 'selected' : '' }}>
                                                Tim Statistik Produksi
                                            </option>
                                            <option value="Tim Statistik Harga, Distribusi dan Jasa"
                                                {{ old('tim') == 'Tim Statistik Harga, Distribusi dan Jasa' ? 'selected' : '' }}>
                                                Tim Statistik Harga, Distribusi dan Jasa
                                            </option>
                                            <option value="Tim Neraca Wilayah dan Analisis Statistik"
                                                {{ old('tim') == 'Tim Neraca Wilayah dan Analisis Statistik' ? 'selected' : '' }}>
                                                Tim Neraca Wilayah dan Analisis Statistik
                                            </option>
                                            <option value="Tim Metodologi, Pengolahan, Infrastruktur dan Inovasi Digital"
                                                {{ old('tim') == 'Tim Metodologi, Pengolahan, Infrastruktur dan Inovasi Digital' ? 'selected' : '' }}>
                                                Tim Metodologi, Pengolahan, Infrastruktur dan Inovasi Digital
                                            </option>
                                            <option value="Tim Diseminasi dan Pelayanan Statistik"
                                                {{ old('tim') == 'Tim Diseminasi dan Pelayanan Statistik' ? 'selected' : '' }}>
                                                Tim Diseminasi dan Pelayanan Statistik
                                            </option>
                                        </select>
                                        @error('tim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Kontak --}}
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-telephone me-2"></i>Informasi Kontak
                                </h6>

                                <div class="row">
                                    {{-- No Telepon --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">No. Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                            <input type="text" name="no_telp"
                                                class="form-control @error('no_telp') is-invalid @enderror"
                                                value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx">
                                        </div>
                                        @error('no_telp')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Foto --}}
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                    <i class="bi bi-image me-2"></i>Foto Profil
                                </h6>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Upload Foto</label>
                                        <input type="file" name="foto"
                                            class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Format: JPG, PNG, JPEG (Maksimal 2MB)
                                        </small>
                                        @error('foto')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Informasi --}}
                            <div class="alert alert-info border-0 mb-4">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-2 fs-5"></i>
                                    <div>
                                        <strong>Informasi:</strong> Field yang bertanda <span class="text-danger">*</span>
                                        wajib diisi.
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('pembimbing.dashboard') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Simpan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
