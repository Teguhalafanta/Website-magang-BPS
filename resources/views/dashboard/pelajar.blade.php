@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        {{-- Jika user belum mengajukan atau pengajuan ditolak, redirect ke form pengajuan --}}
        @if (
            !auth()->user()->pelajar ||
                auth()->user()->pelajar->status === 'ditolak' ||
                auth()->user()->pelajar->status === null)
            <h3 class="fw-bold bps-title mb-3">
                <i class="bi me-2"></i>Dashboard Peserta
            </h3>

            <div class="row">
                <div class="col-12">
                    <div class="alert bps-alert-info d-flex align-items-center mb-3" role="alert">
                        <div class="col-auto">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <strong>Selamat datang!</strong>
                            @if (auth()->user()->pelajar && auth()->user()->pelajar->status === 'ditolak')
                                Pengajuan magang Anda sebelumnya ditolak.
                                @if (auth()->user()->pelajar->alasan)
                                    <br><strong>Alasan:</strong> {{ auth()->user()->pelajar->alasan }}
                                @endif
                                <br>Silakan klik tombol di bawah untuk mengajukan kembali dengan data yang benar.
                            @else
                                Silakan ajukan magang terlebih dahulu untuk mengakses semua fitur dashboard. Klik tombol
                                "Ajukan Magang" di bawah untuk memulai.
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Ajukan Magang yang besar dan menarik --}}
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <a href="{{ route('pelajar.pengajuan.create') }}" class="btn btn-lg bps-btn-primary w-100 py-4 shadow-lg">
                        <i class="bi bi-file-earmark-text fs-2 d-block mb-2"></i>
                        <h4 class="mb-0">Ajukan Magang Sekarang</h4>
                        <small>Lengkapi formulir pengajuan untuk mengakses dashboard</small>
                    </a>
                </div>
            </div>

            {{-- Menampilkan preview fitur yang terkunci --}}
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="text-muted mb-3">
                        <i class="bi bi-lock-fill me-2"></i>Fitur yang Akan Tersedia Setelah Pengajuan Disetujui
                    </h5>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bps-card-locked shadow">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-calendar-check fs-1 bps-text-accent mb-3"></i>
                            <h5 class="text-muted">Presensi Harian</h5>
                            <p class="text-muted small mb-0">Catat kehadiran setiap hari</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bps-card-locked shadow">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-list-task fs-1 bps-text-accent mb-3"></i>
                            <h5 class="text-muted">Kegiatan Magang</h5>
                            <p class="text-muted small mb-0">Kelola kegiatan harian Anda</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bps-card-locked shadow">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-file-earmark-bar-graph fs-1 bps-text-accent mb-3"></i>
                            <h5 class="text-muted">Laporan & Statistik</h5>
                            <p class="text-muted small mb-0">Lihat progress magang Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jika pengajuan sedang menunggu persetujuan --}}
        @elseif(auth()->user()->pelajar->status === 'menunggu')
            <h3 class="fw-bold bps-title mb-3">
                <i class="bi me-2"></i>Dashboard Peserta
            </h3>

            <div class="row">
                <div class="col-12">
                    <div class="alert bps-alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-hourglass-split fs-4 me-3"></i>
                        <div>
                            <strong>Pengajuan Dalam Proses</strong><br>
                            Pengajuan magang Anda sedang dalam proses review. Silakan tunggu persetujuan dari admin. Anda
                            akan menerima notifikasi melalui email setelah pengajuan diproses.
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card bps-card-locked shadow">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-calendar-check fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Presensi</h5>
                            <p class="text-muted mb-0"><i class="bi bi-lock-fill"></i> Terkunci</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bps-card-locked shadow">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-list-task fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Kegiatan</h5>
                            <p class="text-muted mb-0"><i class="bi bi-lock-fill"></i> Terkunci</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bps-card-locked shadow">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-file-earmark-bar-graph fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Laporan</h5>
                            <p class="text-muted mb-0"><i class="bi bi-lock-fill"></i> Terkunci</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jika pengajuan sudah disetujui, tampilkan dashboard normal --}}
        @else
            @php
                // Cek apakah peserta sudah selesai magang
                $isMagangSelesai = auth()->user()->pelajar->status_magang === 'selesai';
            @endphp

            <h3 class="fw-bold bps-title mb-3">
                <i class="bi me-2"></i>DASHBOARD PESERTA
            </h3>

            {{-- Alert jika magang sudah selesai --}}
            @if ($isMagangSelesai)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert bps-alert-success d-flex align-items-center border-0 shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1"><strong>Selamat! Magang Anda Telah Selesai</strong></h5>
                                <p class="mb-0">
                                    Anda telah menyelesaikan program magang. Data Anda masih dapat dilihat, tetapi tidak
                                    dapat menambahkan presensi atau kegiatan baru.
                                    <br><small class="text-muted">Terima kasih atas partisipasi Anda dalam program magang
                                        ini.</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="container mt-4">
                    <div class="row align-items-center">

                        {{-- Bulatan Presensi --}}
                        <div class="col-md-4 text-center">
                            <a href="{{ route('pelajar.presensi.index', ['today' => true]) }}" class="text-decoration-none">
                                <div class="bps-presence-circle {{ ($jumlahPresensiHariIni ?? 0) > 0 ? 'hadir' : 'belum' }}">
                                    <i class="bi bi-calendar-check fs-1"></i>
                                </div>

                                <h5 class="mt-3 fw-bold bps-text-primary">Presensi Hari Ini</h5>

                                <span class="badge bps-badge {{ ($jumlahPresensiHariIni ?? 0) > 0 ? 'hadir' : 'belum' }} mt-2">
                                    {{ ($jumlahPresensiHariIni ?? 0) > 0 ? 'Sudah Absen' : 'Belum Absen' }}
                                </span>
                            </a>
                        </div>

                        {{-- Statistik Ringkasan Magang --}}
                        <div class="col-md-8">
                            <div class="card bps-card shadow-sm border-0">
                                <div class="card-header bps-card-header">
                                    <h5 class="mb-0">
                                        <i class="bi bi-bar-chart-fill me-2"></i>
                                        Ringkasan Magang Anda
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row text-center">

                                        <div class="col-md-4 mb-3">
                                            <a href="{{ route('pelajar.presensi.index') }}"
                                                class="text-decoration-none text-dark">
                                                <div class="p-3 bps-stat-card rounded bps-hover-card">
                                                    <i class="bi bi-calendar-check bps-text-primary fs-1 mb-2"></i>
                                                    <h4 class="mb-0 fw-bold bps-text-primary">{{ $totalPresensi ?? 0 }}</h4>
                                                    <small class="text-muted">Total Presensi</small>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <a href="{{ route('pelajar.kegiatan.bulanan') }}"
                                                class="text-decoration-none text-dark">
                                                <div class="p-3 bps-stat-card rounded bps-hover-card">
                                                    <i class="bi bi-list-task bps-text-accent fs-1 mb-2"></i>
                                                    <h4 class="mb-0 fw-bold bps-text-primary">{{ $jumlahKegiatan ?? 0 }}</h4>
                                                    <small class="text-muted">Total Kegiatan</small>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <a href="{{ route('pelajar.presensi.index') }}"
                                                class="text-decoration-none text-dark">
                                                <div class="p-3 bps-stat-card rounded bps-hover-card">
                                                    <i class="bi bi-clock-history bps-text-accent fs-1 mb-2"></i>
                                                    <h4 class="mb-0 fw-bold bps-text-primary">
                                                        {{ $hariAktifMagang ?? 0 }}</h4>
                                                    <small class="text-muted">Hari Aktif Magang</small>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card bps-card mt-4 shadow-sm" style="max-width: 420px; font-size: 14px;">
                    <div class="card-header bps-card-header py-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-list-task me-2"></i>
                            Kegiatan Terbaru
                        </h6>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-bordered table-sm mb-0 text-center align-middle bps-table"
                            style="font-size: 13px;">
                            <thead class="bps-table-header text-center">
                                <tr>
                                    <th style="width: 10%">No</th>
                                    <th style="width: 60%">Nama</th>
                                    <th style="width: 30%">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kegiatanTerbaru as $index => $kegiatan)
                                    <tr class="bps-table-row">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-start">{{ $kegiatan->nama_kegiatan }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-2">
                                            <i>Belum ada kegiatan</i>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Total Kegiatan --}}
                <div class="col-md-4 mb-3">
                    @if ($isMagangSelesai)
                        {{-- Card read-only untuk magang selesai - hanya redirect ke index untuk melihat --}}
                        <div class="card text-white bps-card-success shadow position-relative">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-2">
                                        Total Kegiatan
                                        <span class="badge bg-light bps-text-success ms-2" style="font-size: 0.65rem;">
                                            <i class="bi bi-eye-fill"></i> View Only
                                        </span>
                                    </h5>
                                    <h3 class="mb-0 fw-bold">{{ $jumlahKegiatan ?? 0 }}</h3>
                                    <small class="opacity-75">Klik untuk melihat riwayat</small>
                                </div>
                                <i class="bi bi-list-task fs-1 opacity-75"></i>
                            </div>
                            <a href="{{ route('pelajar.kegiatan.index') }}" class="stretched-link"></a>
                        </div>
                    @else
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Custom CSS untuk tema BPS --}}
    <style>
        /* ========== WARNA UTAMA BPS ========== */
        :root {
            --bps-primary: #003d7a;      /* Biru BPS */
            --bps-secondary: #0055a5;    /* Biru Muda */
            --bps-accent: #fdb913;       /* Kuning Emas BPS */
            --bps-light: #e8f1f8;        /* Biru Muda Latar */
            --bps-dark: #002147;         /* Biru Tua */
            --bps-success: #28a745;
            --bps-warning: #ffc107;
            --bps-info: #17a2b8;
        }

        /* ========== TYPOGRAPHY ========== */
        .bps-title {
            color: var(--bps-primary);
            border-bottom: 3px solid var(--bps-accent);
            padding-bottom: 10px;
            display: inline-block;
        }

        .bps-text-primary {
            color: var(--bps-primary) !important;
        }

        .bps-text-accent {
            color: var(--bps-accent) !important;
        }

        .bps-text-success {
            color: var(--bps-success) !important;
        }

        /* ========== CARDS ========== */
        .bps-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 12px;
            border-left: 4px solid var(--bps-accent);
        }

        .bps-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 61, 122, 0.15) !important;
        }

        .bps-card-header {
            background: linear-gradient(135deg, var(--bps-primary) 0%, var(--bps-secondary) 100%);
            color: white;
            border: none;
            font-weight: 600;
        }

        .bps-card-locked {
            opacity: 0.6;
            pointer-events: none;
            border-left: 4px solid #ccc;
        }

        .bps-card-success {
            background: linear-gradient(135deg, var(--bps-success) 0%, #218838 100%);
        }

        /* ========== BUTTONS ========== */
        .bps-btn-primary {
            background: linear-gradient(135deg, var(--bps-primary) 0%, var(--bps-secondary) 100%);
            border: none;
            border-radius: 12px;
            transition: all 0.3s;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .bps-btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(253, 185, 19, 0.3), transparent);
            transition: left 0.5s;
        }

        .bps-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 61, 122, 0.4);
        }

        .bps-btn-primary:hover::before {
            left: 100%;
        }

        /* ========== ALERTS ========== */
        .bps-alert-info {
            background: linear-gradient(135deg, #e8f4f8 0%, #d1e7f5 100%);
            border-left: 4px solid var(--bps-info);
            border-radius: 8px;
            color: #004085;
        }

        .bps-alert-warning {
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            border-left: 4px solid var(--bps-warning);
            border-radius: 8px;
            color: #856404;
        }

        .bps-alert-success {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-left: 4px solid var(--bps-success);
            border-radius: 8px;
            color: #155724;
        }

        /* ========== PRESENCE CIRCLE ========== */
        .bps-presence-circle {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            color: white;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .bps-presence-circle::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: rotate(45deg);
            transition: 0.5s;
        }

        .bps-presence-circle.hadir {
            background: linear-gradient(135deg, var(--bps-success) 0%, #218838 100%);
        }

        .bps-presence-circle.belum {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .bps-presence-circle:hover {
            transform: scale(1.07);
            cursor: pointer;
        }

        .bps-presence-circle:hover::before {
            top: 100%;
            left: 100%;
        }

        /* ========== BADGES ========== */
        .bps-badge {
            font-weight: 600;
            padding: 0.4em 0.8em;
            border-radius: 6px;
        }

        .bps-badge.hadir {
            background: var(--bps-success);
            color: white;
        }

        .bps-badge.belum {
            background: #dc3545;
            color: white;
        }

        /* ========== STAT CARDS ========== */
        .bps-stat-card {
            background: linear-gradient(135deg, var(--bps-light) 0%, #f8fbff 100%);
            border: 2px solid transparent;
            transition: 0.3s;
        }

        .bps-hover-card:hover {
            transform: translateY(-5px);
            background: linear-gradient(135deg, #ffffff 0%, var(--bps-light) 100%);
            box-shadow: 0px 6px 15px rgba(0, 61, 122, 0.15);
            border: 2px solid var(--bps-accent);
        }

        /* ========== TABLE ========== */
        .bps-table {
            border-color: var(--bps-light);
        }

        .bps-table-header {
            background: linear-gradient(135deg, var(--bps-primary) 0%, var(--bps-secondary) 100%);
            color: white;
            font-weight: 600;
        }

        .bps-table-row:hover {
            background-color: var(--bps-light);
            transition: 0.2s;
        }

        /* ========== GENERAL TRANSITIONS ========== */
        .card,
        .alert,
        .btn {
            transition: all 0.3s ease;
        }

        /* ========== STRETCHED LINK ========== */
        .stretched-link::after {
            z-index: 1;
        }

        /* ========== HOVER EFFECTS ========== */
        a .bps-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 61, 122, 0.2) !important;
        }

        .card.position-relative:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 61, 122, 0.15) !important;
        }

        /* ========== ACCENT DECORATION ========== */
        .bps-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            background: var(--bps-accent);
            opacity: 0.1;
            border-radius: 0 12px 0 100%;
        }
    </style>
@endsection