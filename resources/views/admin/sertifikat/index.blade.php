@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-award-fill me-2"></i>Kelola Sertifikat Peserta Magang
            </h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="row g-3 mb-2">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Total Peserta</p>
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
                                <p class="fw-bold mb-1 small text-uppercase">Sertifikat Terkirim</p>
                                <h4 class="mb-0 fw-bold">{{ $laporans->where('file_sertifikat', '!=', null)->count() }}</h4>
                            </div>
                            <i class="bi bi-award-fill fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Menunggu Sertifikat</p>
                                <h4 class="mb-0 fw-bold">{{ $laporans->where('file_sertifikat', null)->count() }}</h4>
                            </div>
                            <i class="bi bi-clock-fill fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 text-muted">
                    Total: <span class="badge bg-primary">{{ count($laporans) }}</span> peserta
                </h6>
            </div>

            <div class="card-body p-0">
                @if (count($laporans) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width: 35%;">Nama Peserta</th>
                                    <th style="width: 30%;">Laporan Akhir</th>
                                    <th style="width: 35%;">Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporans as $item)
                                    <tr>
                                        {{-- Nama Peserta --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div class="fw-semibold text-truncate" style="font-size: 0.875rem;">
                                                        {{ $item->user->pelajar->nama ?? $item->user->name }}
                                                    </div>
                                                    <small class="text-muted text-truncate d-block">
                                                        {{ $item->user->email ?? 'Email tidak tersedia' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Laporan Akhir --}}
                                        <td class="text-center">
                                            <a href="{{ route('laporan.download', $item->id) }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>Lihat Laporan
                                            </a>
                                        </td>

                                        {{-- Sertifikat --}}
                                        <td class="text-center">
                                            @if ($item->file_sertifikat)
                                                {{-- Sudah Upload Sertifikat --}}
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ asset('storage/' . $item->file_sertifikat) }}"
                                                        class="btn btn-sm btn-primary" target="_blank">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-info text-white"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $item->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>

                                                {{-- Modal Detail Sertifikat --}}
                                                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">
                                                                    <i class="bi bi-award me-2"></i>Detail Sertifikat
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-12 mb-3">
                                                                        <label class="form-label fw-bold">Nama
                                                                            Peserta:</label>
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
                                                                    <i class="bi bi-download me-1"></i>Download Sertifikat
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
                                                    <div class="d-flex flex-column gap-2 align-items-center">
                                                        <div class="input-group input-group-sm" style="max-width: 300px;">
                                                            <input type="file" name="file_sertifikat"
                                                                class="form-control form-control-sm"
                                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required
                                                                onchange="previewFileName(this, {{ $item->id }})">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="bi bi-upload me-1"></i>Kirim
                                                            </button>
                                                        </div>
                                                        <small class="text-muted">
                                                            PDF, DOC, JPG, PNG (Max: 5MB)
                                                        </small>
                                                        <div class="file-preview small text-muted"
                                                            id="preview-{{ $item->id }}" style="display: none;">
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada laporan yang disetujui pembimbing</p>
                        <small class="text-muted">Laporan yang telah disetujui akan muncul di sini</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

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
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                preview.textContent = 'File: ' + file.name + ' (' + fileSize + ' MB)';
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endsection

@push('styles')
    <style>
        .table> :not(caption)>*>* {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }

        .avatar-sm {
            flex-shrink: 0;
        }

        .upload-form .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
@endpush
