@extends('kerangka.master')

@section('content')
    <div class="container">
        {{-- Jika user belum mengajukan atau pengajuan ditolak, redirect ke form pengajuan --}}
        @if (
            !auth()->user()->pelajar ||
                auth()->user()->pelajar->status === 'ditolak' ||
                auth()->user()->pelajar->status === null)
            <h2 class="mb-4">Dashboard Pelajar</h2>

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                        <div>
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
                    <a href="{{ route('pelajar.pengajuan.create') }}" class="btn btn-lg btn-info w-100 py-4 shadow-lg">
                        <i class="bi bi-file-earmark-text fs-2 d-block mb-2"></i>
                        <h4 class="mb-0">Ajukan Magang Sekarang</h4>
                        <small>Lengkapi formulir pengajuan untuk mengakses dashboard</small>
                    </a>
                </div>
            </div>

            {{-- Menampilkan preview fitur yang terkunci --}}
            <div class="row mt-5">
                <div class="col-12">
                    <h5 class="text-muted mb-3">
                        <i class="bi bi-lock-fill me-2"></i>Fitur yang Akan Tersedia Setelah Pengajuan Disetujui
                    </h5>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow" style="opacity: 0.6; pointer-events: none;">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-calendar-check fs-1 text-warning mb-3"></i>
                            <h5 class="text-muted">Presensi Harian</h5>
                            <p class="text-muted small mb-0">Catat kehadiran setiap hari</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow" style="opacity: 0.6; pointer-events: none;">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-list-task fs-1 text-success mb-3"></i>
                            <h5 class="text-muted">Kegiatan Magang</h5>
                            <p class="text-muted small mb-0">Kelola kegiatan harian Anda</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow" style="opacity: 0.6; pointer-events: none;">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-file-earmark-bar-graph fs-1 text-primary mb-3"></i>
                            <h5 class="text-muted">Laporan & Statistik</h5>
                            <p class="text-muted small mb-0">Lihat progress magang Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jika pengajuan sedang menunggu persetujuan --}}
        @elseif(auth()->user()->pelajar->status === 'menunggu')
            <h2 class="mb-4">Dashboard Pelajar</h2>

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
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
                    <div class="card shadow" style="opacity: 0.6; pointer-events: none;">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-calendar-check fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Presensi</h5>
                            <p class="text-muted mb-0"><i class="bi bi-lock-fill"></i> Terkunci</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow" style="opacity: 0.6; pointer-events: none;">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-list-task fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Kegiatan</h5>
                            <p class="text-muted mb-0"><i class="bi bi-lock-fill"></i> Terkunci</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow" style="opacity: 0.6; pointer-events: none;">
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
                // Cek apakah pelajar sudah selesai magang
                $isMagangSelesai = auth()->user()->pelajar->status_magang === 'selesai';
            @endphp

            <h2 class="mb-4">Dashboard Pelajar</h2>

            {{-- Alert jika magang sudah selesai --}}
            @if ($isMagangSelesai)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-success d-flex align-items-center border-0 shadow-sm" role="alert"
                            style="border-left: 4px solid #28a745 !important;">
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
                                <div class="presence-circle {{ ($jumlahPresensiHariIni ?? 0) > 0 ? 'hadir' : 'belum' }}">
                                    <i class="bi bi-calendar-check fs-1"></i>
                                </div>

                                <h5 class="mt-3 fw-bold">Presensi Hari Ini</h5>

                                <span
                                    class="badge {{ ($jumlahPresensiHariIni ?? 0) > 0 ? 'bg-success' : 'bg-danger' }} mt-2">
                                    {{ ($jumlahPresensiHariIni ?? 0) > 0 ? 'Sudah Absen' : 'Belum Absen' }}
                                </span>
                            </a>
                        </div>

                        {{-- Statistik Ringkasan Magang --}}
                        <div class="col-md-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="bi bi-bar-chart-fill me-2"></i>
                                        Ringkasan Magang Anda
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">

                                        <div class="col-md-4 mb-3">
                                            <a href="{{ route('pelajar.presensi.index') }}"
                                                class="text-decoration-none text-dark">
                                                <div class="p-3 bg-light rounded hover-card">
                                                    <i class="bi bi-calendar-check text-primary fs-1 mb-2"></i>
                                                    <h4 class="mb-0 fw-bold">{{ $totalPresensi ?? 0 }}</h4>
                                                    <small class="text-muted">Total Presensi</small>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <a href="{{ route('pelajar.kegiatan.bulanan') }}"
                                                class="text-decoration-none text-dark">
                                                <div class="p-3 bg-light rounded hover-card">
                                                    <i class="bi bi-list-task text-success fs-1 mb-2"></i>
                                                    <h4 class="mb-0 fw-bold">{{ $jumlahKegiatan ?? 0 }}</h4>
                                                    <small class="text-muted">Total Kegiatan</small>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 bg-light rounded hover-card">
                                                <i class="bi bi-clock-history text-warning fs-1 mb-2"></i>
                                                <h4 class="mb-0 fw-bold">
                                                    {{ auth()->user()->pelajar->durasi_magang ?? '-' }}</h4>
                                                <small class="text-muted">Durasi (hari)</small>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <<div class="card mt-4 shadow-sm" style="max-width: 420px; font-size: 14px;">
                    <div
                        class="card-header bg-secondary text-white py-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-list-task me-2"></i> Kegiatan Terbaru</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-sm mb-0 text-center align-middle"
                            style="font-size: 13px;">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 10%">No</th>
                                    <th style="width: 60%">Nama</th>
                                    <th style="width: 30%">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kegiatanTerbaru as $index => $kegiatan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-start">{{ $kegiatan->nama_kegiatan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
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
            <style>
                .presence-circle {
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
                }

                .presence-circle.hadir {
                    background: #28a745;
                }

                .presence-circle.belum {
                    background: #dc3545;
                }

                .presence-circle:hover {
                    transform: scale(1.07);
                    cursor: pointer;
                }

                .hover-card {
                    transition: 0.3s;
                }

                .hover-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
                }
            </style>


            {{-- Total Kegiatan --}}
            <div class="col-md-4 mb-3">
                @if ($isMagangSelesai)
                    {{-- Card read-only untuk magang selesai - hanya redirect ke index untuk melihat --}}
                    <div class="card text-white bg-success shadow position-relative">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-2">
                                    Total Kegiatan
                                    <span class="badge bg-light text-success ms-2" style="font-size: 0.65rem;">
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
    <style>
        .hover-card {
            transition: 0.3s;
            cursor: pointer;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            background: #e9f3ff !important;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
        }
    </style>
    @endif
    </div>

    {{-- Custom CSS untuk mempercantik tampilan --}}
    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 12px;
        }

        a .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
        }

        /* Styling khusus untuk card read-only */
        .card.position-relative:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15) !important;
        }

        .alert {
            border-left: 4px solid currentColor;
            border-radius: 8px;
        }

        .btn-info {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            border: none;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .btn-info:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 188, 212, 0.4);
        }

        /* Badge styling */
        .badge {
            font-weight: 600;
            padding: 0.35em 0.65em;
        }

        /* Info card styling */
        .bg-light {
            background-color: #f8f9fa !important;
        }

        /* Smooth transitions */
        .card,
        .alert,
        .btn {
            transition: all 0.3s ease;
        }

        /* Hover effect untuk stretched-link card */
        .stretched-link::after {
            z-index: 1;
        }
    </style>
@endsection
