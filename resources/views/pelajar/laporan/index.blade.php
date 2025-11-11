@extends('kerangka.master')

@section('content')
    <style>
        /* Glassmorphism Style */
        .glass-card {
            background: rgba(255, 255, 255, 0.28);
            border-radius: 18px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.12);
            transition: 0.3s ease-in-out;
        }

        .glass-card:hover {
            transform: translateY(-3px);
            box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.18);
        }

        .section-title {
            font-weight: 600;
            font-size: 28px;
            margin-bottom: 20px;
            color: #003366;
            text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.25);
        }

        .status-text {
            font-size: 14px;
            font-weight: 600;
        }
        
        .upload-form {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border: 1px dashed rgba(0, 123, 255, 0.4);
        }
        
        .rejection-reason {
            background: rgba(255, 0, 0, 0.08);
            border-left: 4px solid #dc3545;
            padding: 12px 15px;
            border-radius: 4px;
            margin-top: 15px;
        }
        
        .btn-reupload {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-reupload:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
    </style>

    <div class="container mt-4">
        <h3 class="text-center section-title">
            <i class="fas fa-file-alt"></i> Laporan Akhir & Sertifikat
        </h3>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($laporan)
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="glass-card p-4">

                        <h5 class="mb-3 fw-bold text-primary">
                            <i class="far fa-clipboard"></i> Detail Laporan Magang
                        </h5>

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Laporan Magang:</span>
                            <div>
                                <a href="{{ route('pelajar.laporan.download', $laporan->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                @if ($laporan->status == 'ditolak')
                                    <button type="button" class="btn btn-reupload btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#uploadUlangModal">
                                        <i class="fas fa-upload"></i> Upload Ulang
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="fw-semibold">Status:</span><br>
                            @if ($laporan->status == 'menunggu')
                                <span class="badge bg-warning text-dark status-text">
                                    <i class="fas fa-clock"></i> Menunggu Persetujuan Pembimbing
                                </span>
                            @elseif($laporan->status == 'disetujui')
                                <span class="badge bg-success status-text">
                                    <i class="fas fa-check-circle"></i> Disetujui — Menunggu Sertifikat
                                </span>
                            @elseif($laporan->status == 'ditolak')
                                <span class="badge bg-danger status-text">
                                    <i class="fas fa-times-circle"></i> Ditolak — Upload Ulang
                                </span>
                                
                                <!-- Petunjuk Upload Ulang -->
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>Perhatian:</strong> Laporan Anda ditolak. Silakan perbaiki dan upload ulang laporan.
                                </div>
                            @elseif($laporan->status == 'selesai')
                                <span class="badge bg-info status-text">
                                    <i class="fas fa-check-double"></i> Selesai
                                </span>
                            @endif
                        </div>

                        @if ($laporan->status == 'selesai' && $laporan->file_sertifikat)
                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <span class="fw-semibold">Sertifikat:</span>
                                <!-- Gunakan URL langsung untuk download sertifikat -->
                                <a href="{{ url('/pelajar/laporan/' . $laporan->id . '/download-sertifikat') }}"
                                    class="btn btn-success btn-sm">
                                    <i class="fas fa-file-download"></i> Download Sertifikat
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="glass-card p-4 text-center">
                        <i class="fas fa-info-circle fa-3x text-primary mb-3"></i>
                        <p class="fw-semibold">Kamu belum mengunggah laporan akhir.</p>
                        <a href="{{ route('pelajar.laporan.create') }}" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Laporan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Upload Ulang -->
    @if ($laporan && $laporan->status == 'ditolak')
    <div class="modal fade" id="uploadUlangModal" tabindex="-1" aria-labelledby="uploadUlangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadUlangModalLabel">
                        <i class="fas fa-upload me-2"></i>Upload Laporan Ulang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Gunakan URL langsung untuk upload -->
                <form action="{{ url('/pelajar/laporan/upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file_laporan" class="form-label fw-semibold">File Laporan (PDF)</label>
                            <input type="file" class="form-control @error('file_laporan') is-invalid @enderror" 
                                   id="file_laporan" name="file_laporan" accept=".pdf" required>
                            @error('file_laporan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Maksimal ukuran file: 2MB. Format: PDF
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong><i class="fas fa-lightbulb"></i> Tips:</strong> 
                            Pastikan laporan sudah diperbaiki sesuai dengan masukan dari pembimbing sebelum mengupload ulang.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-1"></i> Upload Laporan Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script>
        // Validasi file sebelum upload
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file_laporan');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        // Validasi ukuran file (2MB sesuai controller)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil.');
                            this.value = '';
                            return;
                        }
                        
                        // Validasi tipe file (hanya PDF sesuai controller)
                        if (file.type !== 'application/pdf') {
                            alert('Format file tidak didukung. Hanya file PDF yang diperbolehkan.');
                            this.value = '';
                            return;
                        }
                    }
                });
            }
        });
    </script>
@endsection