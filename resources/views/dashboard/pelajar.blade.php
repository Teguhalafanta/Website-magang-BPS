@extends('kerangka.master')

@section('content')
    <div class="container">
        {{-- Jika user belum mengajukan atau pengajuan ditolak, redirect ke form pengajuan --}}
        @if (!auth()->user()->pelajar || auth()->user()->pelajar->status === 'ditolak' || auth()->user()->pelajar->status === null)
            <h2 class="mb-4">Dashboard Pelajar</h2>
            
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Selamat datang!</strong> 
                            @if(auth()->user()->pelajar && auth()->user()->pelajar->status === 'ditolak')
                                Pengajuan magang Anda sebelumnya ditolak.
                                @if (auth()->user()->pelajar->alasan)
                                    <br><strong>Alasan:</strong> {{ auth()->user()->pelajar->alasan }}
                                @endif
                                <br>Silakan klik tombol di bawah untuk mengajukan kembali dengan data yang benar.
                            @else
                                Silakan ajukan magang terlebih dahulu untuk mengakses semua fitur dashboard. Klik tombol "Ajukan Magang" di bawah untuk memulai.
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
                            Pengajuan magang Anda sedang dalam proses review. Silakan tunggu persetujuan dari admin. Anda akan menerima notifikasi melalui email setelah pengajuan diproses.
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
            <h2 class="mb-4">Dashboard Pelajar</h2>

            <div class="row">
                {{-- Presensi Hari Ini --}}
                <div class="col-md-4 mb-3">
                    <a href="{{ route('pelajar.presensi.index', ['today' => true]) }}" class="text-decoration-none">
                        <div class="card bg-warning text-white shadow">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>Presensi Hari Ini</h5>
                                    <h3>{{ $jumlahPresensiHariIni ?? 0 }}</h3>
                                </div>
                                <i class="bi bi-calendar-check fs-2"></i>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Total Kegiatan --}}
                <div class="col-md-4 mb-3">
                    <a href="{{ route('pelajar.kegiatan.index', ['today' => true]) }}" class="text-decoration-none">
                        <div class="card text-white bg-success shadow">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>Total Kegiatan</h5>
                                    <h3>{{ $jumlahKegiatan ?? 0 }}</h3>
                                </div>
                                <i class="bi bi-list-task fs-2"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
       
        @endif
    </div>

    {{-- Custom CSS untuk mempercantik tampilan --}}
    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
        }
        
        a .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
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
    </style>
@endsection