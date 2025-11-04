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
        box-shadow: 0px 8px 20px rgba(0,0,0,0.12);
        transition: 0.3s ease-in-out;
    }
    .glass-card:hover {
        transform: translateY(-3px);
        box-shadow: 0px 12px 30px rgba(0,0,0,0.18);
    }
    .section-title {
        font-weight: 600;
        font-size: 28px;
        margin-bottom: 20px;
        color: #003366;
        text-shadow: 0px 1px 2px rgba(0,0,0,0.25);
    }
    .status-text {
        font-size: 14px;
        font-weight: 600;
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
                        <a href="{{ route('pelajar.laporan.download', $laporan->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
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
                        @elseif($laporan->status == 'selesai')
                            <span class="badge bg-info status-text">
                                <i class="fas fa-check-double"></i> Selesai
                            </span>
                        @endif
                    </div>

                    @if ($laporan->status == 'selesai')
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="fw-semibold">Sertifikat:</span>
                            <a href="{{ asset('storage/' . $laporan->file_sertifikat) }}" class="btn btn-success btn-sm">
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
@endsection
