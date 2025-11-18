@extends('kerangka.master')

@section('title', 'Profile')

@section('content')
    <div class="container-fluid py-4">
        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-2" style="color: #0054a6;">
                    <i class="bi bi-person-badge-fill me-2"></i>Profil {{ ucfirst($user->role) }}
                </h3>
                <p class="text-muted mb-0">Kelola informasi profil dan data pribadi Anda</p>
            </div>
        </div>

        {{-- Main Profile Card --}}
        <div class="row">
            {{-- Sidebar Foto Profil --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center py-4">
                        {{-- Foto Profil --}}
                        <div class="position-relative d-inline-block">
                            @if ($user->foto && Storage::disk('public')->exists($user->foto))
                                <img src="{{ Storage::url($user->foto) }}" alt="Foto Profil"
                                    class="rounded-circle border shadow-sm mb-3"
                                    style="width: 180px; height: 180px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Default Foto"
                                    class="rounded-circle border shadow-sm mb-3"
                                    style="width: 180px; height: 180px; object-fit: cover;">
                            @endif
                            <div class="position-absolute bottom-0 end-0">
                                <button class="btn btn-primary btn-sm rounded-circle shadow" data-bs-toggle="modal"
                                    data-bs-target="#ubahFotoModal" style="width: 40px; height: 40px;">
                                    <i class="bi bi-camera"></i>
                                </button>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-1" style="color: #0054a6;">
                            {{ $user->role === 'pelajar' ? optional($user->pelajar)->nama : $user->username }}
                        </h5>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        {{-- Status Badge untuk Pelajar --}}
                        @if ($user->role === 'pelajar')
                            @php
                                $pelajar = $user->pelajar;
                                $status = $pelajar->statusMagangOtomatis ?? 'belum ditentukan';
                                $badgeClass = $pelajar->badgeClass ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }} fs-6 px-3 py-2">
                                <i class="bi bi-info-circle me-1"></i>
                                {{ ucfirst($status) }}
                            </span>
                        @endif

                        <hr class="my-4">
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="col-md-8">
                {{-- Data Pribadi Card --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i>Data Pribadi
                            </h5>
                            <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#editPribadiModal">
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($user->role === 'pelajar')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Nama Lengkap</label>
                                    <p class="fw-semibold mb-0">{{ optional($user->pelajar)->nama ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">NIM / NISN</label>
                                    <p class="fw-semibold mb-0">{{ optional($user->pelajar)->nim_nisn ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Fakultas</label>
                                    <p class="fw-semibold mb-0">{{ optional($user->pelajar)->fakultas ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Jurusan</label>
                                    <p class="fw-semibold mb-0">{{ optional($user->pelajar)->jurusan ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Asal Institusi</label>
                                    <p class="fw-semibold mb-0">{{ optional($user->pelajar)->asal_institusi ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Telepon</label>
                                    <p class="fw-semibold mb-0">{{ optional($user->pelajar)->telepon ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="text-muted small mb-1">Email</label>
                                    <p class="fw-semibold mb-0">{{ $user->email }}</p>
                                </div>
                            </div>
                        @else
                            <div class="row g-3">
                                <div class="col-md-9">
                                    <label class="text-muted small mb-1">Nama</label>
                                    <p class="fw-semibold mb-0">{{ $user->username ?? '-' }}</p>
                                </div>
                                <div class="col-md-9">
                                    <label class="text-muted small mb-1">Email</label>
                                    <p class="fw-semibold mb-0">{{ $user->email ?? '-' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Informasi Magang Card (Hanya untuk Pelajar) --}}
                @if ($user->role === 'pelajar')
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0 text-primary">
                                    <i class="bi bi-briefcase-fill me-2"></i>Informasi Magang
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Tanggal Mulai</label>
                                    <p class="fw-semibold mb-0">
                                        {{ optional($user->pelajar)->rencana_mulai ? \Carbon\Carbon::parse($user->pelajar->rencana_mulai)->format('d F Y') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Tanggal Selesai</label>
                                    <p class="fw-semibold mb-0">
                                        {{ optional($user->pelajar)->rencana_selesai ? \Carbon\Carbon::parse($user->pelajar->rencana_selesai)->format('d F Y') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Pembimbing / Mentor</label>
                                    <p class="fw-semibold mb-0">
                                        {{ optional(optional($user->pelajar)->pembimbing)->nama ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Status Magang</label>
                                    <p class="mb-0">
                                        @php
                                            $pelajar = $user->pelajar;
                                            $status = $pelajar->statusMagangOtomatis ?? 'belum ditentukan';
                                            $badgeClass = $pelajar->badgeClass ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ==================== MODALS ==================== --}}

    {{-- Modal Edit Data Pribadi --}}
    <div class="modal fade" id="editPribadiModal" tabindex="-1" aria-labelledby="editPribadiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0054a6, #003d7a);">
                    <h5 class="modal-title fw-bold" id="editPribadiLabel">
                        <i class="bi bi-person-gear me-2"></i>Edit Data Pribadi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" class="w-100">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            {{-- Field Nama --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person text-primary"></i>
                                    </span>
                                    <input type="text" name="nama" class="form-control border-start-0"
                                        value="{{ $user->role === 'pelajar' ? optional($user->pelajar)->nama : $user->username }}"
                                        required>
                                </div>
                            </div>

                            {{-- Field khusus Pelajar --}}
                            @if ($user->role === 'pelajar')
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIM / NISN</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-card-text text-primary"></i>
                                        </span>
                                        <input type="text" name="nim_nisn" class="form-control border-start-0"
                                            value="{{ optional($user->pelajar)->nim_nisn }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fakultas</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-building text-primary"></i>
                                        </span>
                                        <input type="text" name="fakultas" class="form-control border-start-0"
                                            value="{{ optional($user->pelajar)->fakultas }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jurusan</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-book text-primary"></i>
                                        </span>
                                        <input type="text" name="jurusan" class="form-control border-start-0"
                                            value="{{ optional($user->pelajar)->jurusan }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Asal Institusi</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-bank text-primary"></i>
                                        </span>
                                        <input type="text" name="asal_institusi" class="form-control border-start-0"
                                            value="{{ optional($user->pelajar)->asal_institusi }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-telephone text-primary"></i>
                                        </span>
                                        <input type="text" name="telepon" class="form-control border-start-0"
                                            value="{{ optional($user->pelajar)->telepon }}">
                                    </div>
                                </div>
                            @endif

                            {{-- Email (readonly) --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 bg-light"
                                        value="{{ $user->email }}" readonly>
                                </div>
                                <small class="form-text text-muted">Email tidak dapat diubah</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Informasi Magang --}}
    <div class="modal fade" id="editMagangModal" tabindex="-1" aria-labelledby="editMagangLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0054a6, #003d7a);">
                    <h5 class="modal-title fw-bold" id="editMagangLabel">
                        <i class="bi bi-briefcase me-2"></i>Edit Informasi Magang
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('magang.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tanggal Mulai</label>
                                <input type="date" name="rencana_mulai" class="form-control"
                                    value="{{ optional($user->pelajar)->rencana_mulai }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tanggal Selesai</label>
                                <input type="date" name="rencana_selesai" class="form-control"
                                    value="{{ optional($user->pelajar)->rencana_selesai }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Ubah Foto --}}
    <div class="modal fade" id="ubahFotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0054a6, #003d7a);">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-camera me-2"></i>Ubah Foto Profil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.updateFoto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Foto</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required
                                onchange="previewImage(this)">
                            <div class="form-text">Format: JPG, PNG, JPEG (Maks. 2MB)</div>
                        </div>
                        <div class="text-center">
                            <img id="imagePreview" src="#" alt="Preview" class="rounded shadow-sm mt-2"
                                style="max-width: 200px; display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i>Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 0.5rem;
        }

        .btn-primary {
            background-color: #0054a6;
            border-color: #0054a6;
        }

        .btn-primary:hover {
            background-color: #003d7a;
            border-color: #003d7a;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .modal-header {
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .badge {
            font-size: 0.75em;
            font-weight: 500;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Auto-dismiss alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
        });

        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endsection
