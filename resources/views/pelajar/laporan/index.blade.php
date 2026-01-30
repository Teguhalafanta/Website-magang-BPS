@extends('kerangka.master')

@section('content')
<div class="container py-2">
    <!-- Header -->
    <div class="text-center mb-4">
        <h3 class="fw-bold text-bps-gradient mb-2">LAPORAN AKHIR & SERTIFIKAT</h3>
        <p class="text-muted">Kelola laporan akhir magang dan sertifikat penyelesaian</p>
    </div>

    {{-- Alert Notifikasi --}}
    @if (session('success'))
    <div class="alert alert-success bps-alert alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger bps-alert alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar Kiri - Status & Aksi -->
        <div class="col-lg-4">
            @if ($laporan)
            {{-- Card Status Laporan --}}
            <div class="card bps-glass-card h-100">
                <div class="card-header bps-card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Status Laporan
                    </h5>
                </div>
                <div class="card-body p-4">
                    {{-- Status Badge --}}
                    <div class="text-center mb-4">
                        @if ($laporan->status == 'menunggu')
                        <div class="bps-status-waiting">
                            <i class="bi bi-clock-fill fs-1 mb-3"></i>
                            <h5 class="fw-bold">Menunggu Persetujuan</h5>
                            <p class="text-muted mb-0">Laporan sedang ditinjau pembimbing</p>
                        </div>
                        @elseif($laporan->status == 'disetujui')
                        <div class="bps-status-approved">
                            <i class="bi bi-check-circle-fill fs-1 mb-3 text-success"></i>
                            <h5 class="fw-bold text-success">Disetujui</h5>
                            <p class="text-muted mb-0">Menunggu sertifikat</p>
                        </div>
                        @elseif($laporan->status == 'ditolak')
                        <div class="bps-status-rejected">
                            <i class="bi bi-x-circle-fill fs-1 mb-3 text-danger"></i>
                            <h5 class="fw-bold text-danger">Ditolak</h5>
                            <p class="text-muted mb-0">Perlu upload ulang</p>
                        </div>
                        @elseif($laporan->status == 'selesai')
                        <div class="bps-status-completed">
                            <i class="bi bi-award-fill fs-1 mb-3 text-bps-primary"></i>
                            <h5 class="fw-bold text-bps-primary">Selesai</h5>
                            <p class="text-muted mb-0">Magang telah diselesaikan</p>
                        </div>
                        @endif
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('pelajar.laporan.download', $laporan->id) }}" class="btn bps-btn-outline">
                            <i class="bi bi-download me-2"></i>Download Laporan
                        </a>

                        @if ($laporan->status == 'ditolak')
                        <button type="button" class="btn bps-btn-danger" data-bs-toggle="modal" data-bs-target="#uploadUlangModal">
                            <i class="bi bi-upload me-2"></i>Upload Ulang
                        </button>
                        @endif

                        @if ($laporan->status == 'selesai' && $laporan->file_sertifikat)
                        <a href="{{ route('pelajar.laporan.downloadSertifikat', $laporan->id) }}" class="btn bps-btn-success">
                            <i class="bi bi-file-earmark-arrow-down me-2"></i>Download Sertifikat
                        </a>
                        @endif
                    </div>

                    {{-- Info Tambahan --}}
                    @if ($laporan->status == 'ditolak')
                    <div class="alert alert-warning bps-alert mt-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle me-2 mt-1"></i>
                            <div>
                                <strong>Perhatian!</strong><br>
                                Laporan Anda ditolak. Silakan perbaiki sesuai masukan pembimbing.
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            {{-- Empty State --}}
            <div class="card bps-glass-card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <i class="bi bi-file-earmark-plus text-bps-primary fs-1 mb-3"></i>
                        <h4 class="fw-bold text-bps-primary mb-2">Belum Ada Laporan</h4>
                        <p class="text-muted">Upload laporan akhir magang untuk melanjutkan proses</p>
                    </div>
                    <a href="{{ route('pelajar.laporan.create') }}" class="btn bps-btn-primary btn-lg">
                        <i class="bi bi-upload me-2"></i>Upload Laporan
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Konten Utama - Detail Laporan -->
        <div class="col-lg-8">
            @if ($laporan)
            {{-- Detail Laporan --}}
            <div class="card bps-glass-card">
                <div class="card-header bps-card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Detail Laporan
                    </h5>
                </div>
                <div class="card-body p-4">
                    {{-- Informasi File --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="bps-info-item">
                                <div class="info-label">Tanggal Upload</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($laporan->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Status --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-bps-primary mb-3">Progress Laporan</h6>
                        <div class="bps-progress-tracker">
                            <div class="progress-step {{ $laporan->status == 'menunggu' || $laporan->status == 'disetujui' || $laporan->status == 'selesai' ? 'completed' : '' }} {{ $laporan->status == 'menunggu' ? 'current' : '' }}">
                                <div class="step-icon">1</div>
                                <div class="step-label">Upload</div>
                            </div>
                            <div class="progress-step {{ $laporan->status == 'disetujui' || $laporan->status == 'selesai' ? 'completed' : '' }} {{ $laporan->status == 'disetujui' ? 'current' : '' }}">
                                <div class="step-icon">2</div>
                                <div class="step-label">Review</div>
                            </div>
                            <div class="progress-step {{ $laporan->status == 'selesai' ? 'completed' : '' }} {{ $laporan->status == 'selesai' ? 'current' : '' }}">
                                <div class="step-icon">3</div>
                                <div class="step-label">Selesai</div>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Status --}}
                    <div>
                        <h6 class="fw-semibold text-bps-primary mb-3">Riwayat Status</h6>
                        <div class="bps-timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Laporan Diupload</div>
                                    <div class="timeline-time">{{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y H:i') }}</div>
                                </div>
                            </div>

                            @if ($laporan->status == 'disetujui' || $laporan->status == 'selesai')
                            <div class="timeline-item">
                                <div class="timeline-marker success"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Laporan Disetujui</div>
                                    <div class="timeline-time">{{ \Carbon\Carbon::parse($laporan->updated_at)->translatedFormat('d F Y H:i') }}</div>
                                </div>
                            </div>
                            @endif

                            @if ($laporan->status == 'selesai' && $laporan->file_sertifikat)
                            <div class="timeline-item">
                                <div class="timeline-marker primary"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Sertifikat Tersedia</div>
                                    <div class="timeline-time">Sertifikat dapat diunduh</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @else
            {{-- Panduan Upload --}}
            <div class="card bps-glass-card">
                <div class="card-header bps-card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Panduan Upload Laporan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="bps-guide-item">
                                <div class="guide-icon">
                                    <i class="bi bi-file-pdf"></i>
                                </div>
                                <div class="guide-content">
                                    <h6>Format File</h6>
                                    <p class="mb-0">Gunakan format PDF dengan ukuran maksimal 10MB</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bps-guide-item">
                                <div class="guide-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="guide-content">
                                    <h6>Struktur Laporan</h6>
                                    <p class="mb-0">Ikuti struktur laporan yang telah ditentukan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bps-guide-item">
                                <div class="guide-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="guide-content">
                                    <h6>Proses Review</h6>
                                    <p class="mb-0">Laporan akan direview oleh pembimbing dalam 3-5 hari</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bps-guide-item">
                                <div class="guide-icon">
                                    <i class="bi bi-award"></i>
                                </div>
                                <div class="guide-content">
                                    <h6>Sertifikat</h6>
                                    <p class="mb-0">Sertifikat tersedia setelah laporan disetujui</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Upload Ulang -->
@if ($laporan && $laporan->status == 'ditolak')
<div class="modal fade" id="uploadUlangModal" tabindex="-1" aria-labelledby="uploadUlangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bps-modal-content">
            <div class="modal-header bps-modal-header">
                <h5 class="modal-title" id="uploadUlangModalLabel">
                    <i class="bi bi-upload me-2"></i> Upload Laporan Ulang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('pelajar.laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="file_laporan" class="form-label fw-semibold text-bps-primary mb-3">
                            <i class="bi bi-file-pdf me-2"></i> File Laporan (PDF)
                        </label>
                        <input type="file" class="form-control bps-form-control @error('file_laporan') is-invalid @enderror"
                            id="file_laporan" name="file_laporan" accept=".pdf" required>
                        @error('file_laporan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Maksimal ukuran file: 2MB. Format yang diterima: PDF.
                        </div>
                    </div>

                    <div class="alert alert-info bps-alert">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-lightbulb me-2 mt-1"></i>
                            <div>
                                <strong class="d-block mb-1">Tips Upload Ulang</strong>
                                Pastikan laporan sudah diperbaiki sesuai dengan masukan dari pembimbing sebelum mengupload ulang.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn bps-btn-outline" data-bs-dismiss="modal">
                        <i class="bi bi-x me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn bps-btn-primary">
                        <i class="bi bi-upload me-2"></i> Upload Laporan Ulang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    /* BPS Color Scheme */
    :root {
        --bps-primary: #003366;
        --bps-secondary: #0056b3;
        --bps-accent: #e6f2ff;
        --bps-success: #28a745;
        --bps-warning: #ffc107;
        --bps-danger: #dc3545;
        --bps-info: #17a2b8;
        --bps-light: #f8f9fa;
        --bps-white: #ffffff;
    }

    /* Typography */
    .text-bps-gradient {
        background: linear-gradient(135deg, var(--bps-primary), var(--bps-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Cards */
    .bps-glass-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(230, 242, 255, 0.9));
        border-radius: 16px;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 2px solid rgba(0, 51, 102, 0.1);
        box-shadow: 0 8px 25px rgba(0, 51, 102, 0.1);
        transition: all 0.3s ease;
    }

    .bps-glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(0, 51, 102, 0.15);
    }

    .bps-card-header {
        background: linear-gradient(135deg, var(--bps-primary), var(--bps-secondary));
        color: white;
        border-radius: 16px 16px 0 0 !important;
        border: none;
        padding: 1.25rem 1.5rem;
    }

    /* Buttons */
    .bps-btn-primary {
        background: linear-gradient(135deg, var(--bps-primary), var(--bps-secondary));
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 51, 102, 0.3);
    }

    .bps-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 51, 102, 0.4);
        color: white;
    }

    .bps-btn-success {
        background: linear-gradient(135deg, var(--bps-success), #34ce57);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .bps-btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .bps-btn-danger {
        background: linear-gradient(135deg, var(--bps-danger), #e52535);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .bps-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        color: white;
    }

    .bps-btn-outline {
        background: transparent;
        border: 2px solid var(--bps-primary);
        color: var(--bps-primary);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .bps-btn-outline:hover {
        background: var(--bps-primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Status Styles */
    .bps-status-waiting,
    .bps-status-approved,
    .bps-status-rejected,
    .bps-status-completed {
        padding: 2rem 1rem;
        border-radius: 12px;
        text-align: center;
    }

    .bps-status-waiting {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
        border: 2px solid rgba(255, 193, 7, 0.2);
    }

    .bps-status-approved {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
        border: 2px solid rgba(40, 167, 69, 0.2);
    }

    .bps-status-rejected {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
        border: 2px solid rgba(220, 53, 69, 0.2);
    }

    .bps-status-completed {
        background: linear-gradient(135deg, rgba(0, 51, 102, 0.1), rgba(0, 51, 102, 0.05));
        border: 2px solid rgba(0, 51, 102, 0.2);
    }

    /* Info Items */
    .bps-info-item {
        padding: 1rem;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 8px;
        border: 1px solid rgba(0, 51, 102, 0.1);
    }

    .info-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--bps-primary);
    }

    /* Progress Tracker */
    .bps-progress-tracker {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 2rem 0;
        position: relative;
    }

    .bps-progress-tracker::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6c757d;
    }

    .progress-step.completed .step-icon {
        background: linear-gradient(135deg, var(--bps-success), #34ce57);
        color: white;
    }

    .progress-step.current .step-icon {
        background: linear-gradient(135deg, var(--bps-primary), var(--bps-secondary));
        color: white;
        box-shadow: 0 4px 15px rgba(0, 51, 102, 0.3);
    }

    /* Timeline */
    .bps-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .bps-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6c757d;
        border: 2px solid white;
    }

    .timeline-marker.success {
        background: var(--bps-success);
    }

    .timeline-marker.primary {
        background: var(--bps-primary);
    }

    .timeline-content {
        padding-left: 1rem;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--bps-primary);
        margin-bottom: 0.25rem;
    }

    .timeline-time {
        font-size: 0.875rem;
        color: #6c757d;
    }

    /* Guide Items */
    .bps-guide-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 8px;
        border: 1px solid rgba(0, 51, 102, 0.1);
    }

    .guide-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--bps-primary), var(--bps-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .guide-content h6 {
        font-weight: 600;
        color: var(--bps-primary);
        margin-bottom: 0.25rem;
    }

    .guide-content p {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    /* Alerts */
    .bps-alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    /* Modal */
    .bps-modal-header {
        background: linear-gradient(135deg, var(--bps-primary), var(--bps-secondary));
        color: white;
        border-radius: 16px 16px 0 0;
        border: none;
    }

    .bps-modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 50px rgba(0, 51, 102, 0.2);
        overflow: hidden;
    }

    .bps-form-control {
        border: 2px solid rgba(0, 51, 102, 0.2);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .bps-form-control:focus {
        border-color: var(--bps-primary);
        box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
    }
</style>

<script>
    // Validasi file sebelum upload
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file_laporan');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Validasi ukuran file (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil.');
                        this.value = '';
                        return;
                    }

                    // Validasi tipe file (hanya PDF)
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

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection