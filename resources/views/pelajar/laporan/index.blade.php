@extends('kerangka.master')

@section('content')
    <style>
        /* BPS Color Scheme */
        :root {
            --bps-primary: #003366;
            /* Biru tua BPS */
            --bps-secondary: #0056b3;
            /* Biru medium */
            --bps-accent: #e6f2ff;
            /* Biru muda */
            --bps-success: #28a745;
            /* Hijau */
            --bps-warning: #ffc107;
            /* Kuning */
            --bps-danger: #dc3545;
            /* Merah */
            --bps-light: #f8f9fa;
            /* Abu-abu muda */
            --bps-white: #ffffff;
            /* Putih */
        }

        /* BPS Glassmorphism Style */
        .bps-glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(230, 242, 255, 0.8));
            border-radius: 16px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 2px solid rgba(0, 51, 102, 0.15);
            box-shadow: 0 8px 25px rgba(0, 51, 102, 0.15);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .bps-glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 51, 102, 0.2);
            border-color: rgba(0, 51, 102, 0.3);
        }

        .bps-section-title {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 2rem;
            color: var(--bps-primary);
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
        }

        .bps-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--bps-primary), var(--bps-secondary));
            border-radius: 2px;
        }

        .bps-status-badge {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .bps-btn-primary {
            background: linear-gradient(135deg, var(--bps-primary), var(--bps-secondary));
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 51, 102, 0.3);
        }

        .bps-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 51, 102, 0.4);
            color: white;
        }

        .bps-btn-outline {
            background: transparent;
            border: 2px solid var(--bps-primary);
            color: var(--bps-primary);
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .bps-btn-outline:hover {
            background: var(--bps-primary);
            color: white;
            transform: translateY(-2px);
        }

        .bps-btn-success {
            background: linear-gradient(135deg, var(--bps-success), #34ce57);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
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
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .bps-btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
            color: white;
        }

        .bps-alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .bps-upload-form {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(230, 242, 255, 0.9));
            border-radius: 16px;
            padding: 2rem;
            margin-top: 1.5rem;
            border: 2px dashed rgba(0, 51, 102, 0.3);
            backdrop-filter: blur(10px);
        }

        .bps-rejection-reason {
            background: linear-gradient(135deg, rgba(255, 240, 240, 0.9), rgba(255, 230, 230, 0.8));
            border-left: 5px solid var(--bps-danger);
            padding: 1.25rem;
            border-radius: 12px;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.1);
        }

        .bps-info-box {
            background: linear-gradient(135deg, rgba(230, 242, 255, 0.9), rgba(255, 255, 255, 0.8));
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            border: 2px solid rgba(0, 51, 102, 0.1);
        }

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

        .bps-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .bps-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(0, 51, 102, 0.3), transparent);
            margin: 1.5rem 0;
        }
    </style>

    <div class="container mt-4">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="bps-section-title">
                <i class="fas fa-file-contract bps-icon"></i>Laporan Akhir & Sertifikat
            </h1>
            <p class="text-muted">Kelola laporan akhir magang dan sertifikat penyelesaian</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success bps-alert alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle bps-icon text-success"></i>
                    <strong class="me-2">Sukses!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger bps-alert alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle bps-icon text-danger"></i>
                    <strong class="me-2">Peringatan!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($laporan)
            <!-- Laporan Details Card -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="bps-glass-card p-4 p-md-5">
                        <!-- Card Header -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary rounded-circle p-3 me-3">
                                <i class="fas fa-file-alt text-white fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1 text-primary">Detail Laporan Magang</h4>
                                <p class="text-muted mb-0">Informasi lengkap laporan akhir magang Anda</p>
                            </div>
                        </div>

                        <!-- File Laporan Section -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-primary mb-2">
                                    <i class="fas fa-file-pdf bps-icon"></i>File Laporan Magang
                                </h6>
                                <p class="text-muted mb-0">Dokumen laporan akhir dalam format PDF</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="d-flex justify-content-md-end gap-2">
                                    <a href="{{ route('pelajar.laporan.download', $laporan->id) }}" class="bps-btn-outline"
                                        role="button">
                                        <i class="fas fa-download me-2"></i>Download Laporan
                                    </a>
                                    @if ($laporan->status == 'ditolak')
                                        <button id="uploadUlangBtn" type="button" class="bps-btn-danger"
                                            data-bs-toggle="modal" data-bs-target="#uploadUlangModal"
                                            onclick="(function(){ try{ if(!document.getElementById('uploadUlangModal')) return; if(typeof bootstrap==='undefined'){ return; } }catch(e){} })();">
                                            <i class="fas fa-upload me-2"></i>Upload Ulang
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-primary mb-3">
                                <i class="fas fa-tasks bps-icon"></i>Status Laporan
                            </h6>
                            <div class="d-flex align-items-center">
                                @if ($laporan->status == 'menunggu')
                                    <span class="bps-status-badge bg-warning text-dark">
                                        <i class="fas fa-clock me-2"></i>Menunggu Persetujuan Pembimbing
                                    </span>
                                @elseif($laporan->status == 'disetujui')
                                    <span class="bps-status-badge bg-success text-white">
                                        <i class="fas fa-check-circle me-2"></i>Disetujui — Menunggu Sertifikat
                                    </span>
                                @elseif($laporan->status == 'ditolak')
                                    <span class="bps-status-badge bg-danger text-white">
                                        <i class="fas fa-times-circle me-2"></i>Ditolak — Upload Ulang
                                    </span>
                                @elseif($laporan->status == 'selesai')
                                    <span class="bps-status-badge bg-info text-white">
                                        <i class="fas fa-check-double me-2"></i>Selesai
                                    </span>
                                @endif
                            </div>

                            @if ($laporan->status == 'ditolak')
                                <div class="alert alert-warning bps-alert mt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle bps-icon text-warning"></i>
                                        <div>
                                            <strong class="d-block mb-1">Perhatian!</strong>
                                            Laporan Anda ditolak. Silakan perbaiki sesuai masukan pembimbing dan upload
                                            ulang laporan.
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Sertifikat Section -->
                        @if ($laporan->status == 'selesai' && $laporan->file_sertifikat)
                            <div class="bps-divider"></div>
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="fw-semibold text-primary mb-2">
                                        <i class="fas fa-award bps-icon"></i>Sertifikat Magang
                                    </h6>
                                    <p class="text-muted mb-0">Sertifikat penyelesaian program magang</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <a href="{{ route('pelajar.laporan.downloadSertifikat', $laporan->id) }}"
                                        class="bps-btn-success">
                                        <i class="fas fa-file-download me-2"></i>Download Sertifikat
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="bps-info-box">
                        <div class="mb-4">
                            <i class="fas fa-file-upload text-primary fa-4x mb-3"></i>
                            <h4 class="fw-bold text-primary mb-2">Belum Ada Laporan</h4>
                            <p class="text-muted mb-4">Anda belum mengunggah laporan akhir magang. Upload laporan Anda untuk
                                melanjutkan proses.</p>
                        </div>
                        <a href="{{ route('pelajar.laporan.create') }}" class="bps-btn-primary btn-lg" role="button">
                            <i class="fas fa-upload me-2"></i>Upload Laporan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Upload Ulang -->
    @if ($laporan && $laporan->status == 'ditolak')
        <div class="modal fade" id="uploadUlangModal" tabindex="-1" aria-labelledby="uploadUlangModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bps-modal-content" style="position: relative; z-index: 1055;">
                    <div class="modal-header bps-modal-header">
                        <h5 class="modal-title" id="uploadUlangModalLabel">
                            <i class="fas fa-upload me-2"></i> Upload Laporan Ulang
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <!-- FORM -->
                    <form action="{{ route('pelajar.laporan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label for="file_laporan" class="form-label fw-semibold text-primary mb-3">
                                    <i class="fas fa-file-pdf me-2"></i> File Laporan (PDF)
                                </label>
                                <input type="file"
                                    class="form-control bps-form-control @error('file_laporan') is-invalid @enderror"
                                    id="file_laporan" name="file_laporan" accept=".pdf" required>
                                @error('file_laporan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Maksimal ukuran file: 2MB. Format yang diterima: PDF.
                                </div>
                            </div>

                            <div class="alert alert-info bps-alert">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-lightbulb bps-icon text-info mt-1"></i>
                                    <div>
                                        <strong class="d-block mb-1">Tips Upload Ulang</strong>
                                        Pastikan laporan sudah diperbaiki sesuai dengan masukan dari pembimbing sebelum
                                        mengupload ulang. Perhatikan format dan struktur laporan yang telah ditentukan.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-top-0 bg-light">
                            <button type="button" class="bps-btn-outline" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i> Batal
                            </button>
                            <button type="submit" class="bps-btn-primary" style="pointer-events:auto; z-index:1100;">
                                <i class="fas fa-upload me-2"></i> Upload Laporan Ulang
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

    <script>
        // Fallback programmatic modal opener for 'Upload Ulang' button
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('uploadUlangBtn');
            const modalEl = document.getElementById('uploadUlangModal');
            if (!btn || !modalEl) return;

            function hideModal() {
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
                const bd = document.querySelector('.modal-backdrop');
                if (bd) bd.remove();
            }

            btn.addEventListener('click', function(e) {
                try {
                    // Prefer Bootstrap's JS if present
                    if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                        return;
                    }
                } catch (err) {
                    // fallthrough to manual fallback
                }

                // Manual fallback: toggle classes/styles to show modal
                if (!modalEl.classList.contains('show')) {
                    // move modal to body to avoid stacking/context issues
                    if (modalEl.parentElement !== document.body) {
                        document.body.appendChild(modalEl);
                    }

                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    modalEl.removeAttribute('aria-hidden');
                    modalEl.setAttribute('aria-modal', 'true');
                    modalEl.style.zIndex = 1060; // above typical backdrop
                    document.body.classList.add('modal-open');

                    if (!document.querySelector('.modal-backdrop')) {
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        backdrop.style.zIndex = 1050;
                        document.body.appendChild(backdrop);
                        backdrop.addEventListener('click', hideModal);
                    }

                    // attach close handlers inside modal
                    const closeBtns = modalEl.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
                    closeBtns.forEach(cb => cb.addEventListener('click', hideModal));
                }
            });
        });
    </script>
@endsection
