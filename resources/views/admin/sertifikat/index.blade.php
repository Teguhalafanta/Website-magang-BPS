@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-2 text-primary">
                    <i class="bi bi-award-fill me-2"></i>Kelola Sertifikat Peserta Magang
                </h3>
                <p class="text-muted mb-0">Manajemen sertifikat dan laporan akhir peserta magang</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary fs-6">Total: {{ count($laporans) }} Peserta</span>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="row g-3 mb-2">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 small opacity-75">Total Peserta</p>
                                <h4 class="mb-0 fw-bold">{{ count($laporans) }}</h4>
                            </div>
                            <i class="bi bi-people-fill fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 small opacity-75">Sertifikat Terkirim</p>
                                <h4 class="mb-0 fw-bold">{{ $laporans->where('file_sertifikat', '!=', null)->count() }}</h4>
                            </div>
                            <i class="bi bi-award-fill fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-dark">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 small opacity-75">Menunggu Sertifikat</p>
                                <h4 class="mb-0 fw-bold">{{ $laporans->where('file_sertifikat', null)->count() }}</h4>
                            </div>
                            <i class="bi bi-clock-fill fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 text-primary fw-semibold">
                    <i class="bi bi-list-ul me-2"></i>Daftar Peserta Magang
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light" style="background-color: #e6f0ff !important;">
                            <tr>
                                <th class="py-3 px-4 text-center" style="color: #003d7a; border-bottom: 2px solid #0054a6;">
                                    <i class="bi bi-person-fill me-2"></i>Nama Pelajar
                                </th>
                                <th class="py-3 px-4 text-center" style="color: #003d7a; border-bottom: 2px solid #0054a6;">
                                    <i class="bi bi-file-text-fill me-2"></i>Laporan Akhir
                                </th>
                                <th class="py-3 px-4 text-center" style="color: #003d7a; border-bottom: 2px solid #0054a6;">
                                    <i class="bi bi-award-fill me-2"></i>Sertifikat
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporans as $item)
                                <tr class="align-middle">
                                    {{-- Nama Pelajar --}}
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>
                                                <strong
                                                    class="d-block">{{ $item->user->pelajar->nama ?? $item->user->name }}</strong>
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope me-1"></i>
                                                    {{ $item->user->email ?? 'Email tidak tersedia' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Laporan Akhir --}}
                                    <td class="px-4 text-center">
                                        <a href="{{ route('laporan.download', $item->id) }}" target="_blank"
                                            class="btn btn-outline-primary btn-sm px-3 py-2">
                                            <i class="bi bi-eye me-1"></i> Lihat Laporan
                                        </a>
                                    </td>

                                    {{-- Sertifikat --}}
                                    <td class="px-4 text-center">
                                        @if ($item->file_sertifikat)
                                            {{-- Sudah Upload Sertifikat --}}
                                            <div class="d-flex flex-column align-items-center gap-2">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ asset('storage/' . $item->file_sertifikat) }}"
                                                        class="btn btn-primary" target="_blank">
                                                        <i class="bi bi-download me-1"></i> Download
                                                    </a>
                                                    <button type="button" class="btn btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $item->id }}">
                                                        <i class="bi bi-info-circle"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Modal Detail Sertifikat --}}
                                            <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white"
                                                            style="background-color: #0054a6;">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-award me-2"></i>Detail Sertifikat
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label fw-bold">Nama Peserta:</label>
                                                                    <p class="mb-0">
                                                                        {{ $item->user->pelajar->nama ?? $item->user->name }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label fw-bold">File
                                                                        Sertifikat:</label>
                                                                    <p class="mb-0 text-success">
                                                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                                                                        {{ basename($item->file_sertifikat) }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label fw-bold">Tanggal
                                                                        Upload:</label>
                                                                    <p class="mb-0 text-muted">
                                                                        {{ $item->updated_at->format('d F Y H:i') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="{{ asset('storage/' . $item->file_sertifikat) }}"
                                                                class="btn btn-primary" target="_blank">
                                                                <i class="bi bi-download me-1"></i> Download Sertifikat
                                                            </a>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Form Upload Sertifikat --}}
                                            <form action="{{ route('admin.sertifikat.upload', $item->id) }}"
                                                method="POST" enctype="multipart/form-data" class="upload-form">
                                                @csrf
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="input-group input-group-sm">
                                                        <input type="file" name="file_sertifikat"
                                                            class="form-control form-control-sm"
                                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required
                                                            onchange="previewFileName(this, {{ $item->id }})">
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="bi bi-upload me-1"></i> Kirim
                                                        </button>
                                                    </div>
                                                    <small class="text-muted text-center">
                                                        Format: PDF, DOC, JPG, PNG (Max: 5MB)
                                                    </small>
                                                    <div class="file-preview small text-muted text-center"
                                                        id="preview-{{ $item->id }}" style="display: none;"></div>
                                                </div>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                            <h5>Belum ada laporan yang disetujui pembimbing.</h5>
                                            <p class="mb-0">Laporan yang telah disetujui akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Information Cards --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-primary">
                            <i class="bi bi-info-circle-fill me-2"></i>Informasi Penting
                        </h6>
                        <p class="card-text small mb-0">
                            Pastikan laporan telah disetujui oleh pembimbing sebelum mengupload sertifikat.
                            Sertifikat yang diupload harus dalam format PDF, DOC, JPG, atau PNG dengan ukuran maksimal 5 MB.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-primary">
                            <i class="bi bi-clock-fill me-2"></i>Statistik Terkini
                        </h6>
                        <p class="card-text small mb-0">
                            Total peserta: <strong>{{ count($laporans) }}</strong> |
                            Sertifikat terkirim: <strong
                                class="text-success">{{ $laporans->where('file_sertifikat', '!=', null)->count() }}</strong>
                            |
                            Menunggu: <strong
                                class="text-warning">{{ $laporans->where('file_sertifikat', null)->count() }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 0.5rem;
        }

        .table th {
            font-weight: 600;
            border-bottom: 2px solid #0054a6;
        }

        .btn-primary {
            background-color: #0054a6;
            border-color: #0054a6;
        }

        .btn-primary:hover {
            background-color: #003d7a;
            border-color: #003d7a;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .upload-form .form-control:focus {
            border-color: #0054a6;
            box-shadow: 0 0 0 0.2rem rgba(0, 84, 166, 0.25);
        }

        .badge {
            font-size: 0.75em;
            font-weight: 500;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }, 5000);
            });

            // File input validation
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    const allowedTypes = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                        'image/jpg',
                        'image/png'
                    ];

                    if (file && file.size > maxSize) {
                        alert('File terlalu besar. Maksimal 5MB.');
                        this.value = '';
                    }

                    if (file && !allowedTypes.includes(file.type)) {
                        alert('Hanya file PDF, DOC, JPG, dan PNG yang diizinkan.');
                        this.value = '';
                    }
                });
            });
        });

        function previewFileName(input, itemId) {
            const preview = document.getElementById('preview-' + itemId);
            if (input.files.length > 0) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                preview.textContent = 'File: ' + file.name + ' (' + fileSize + ' MB)';
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endsection
