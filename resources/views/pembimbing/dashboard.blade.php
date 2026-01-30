@extends('kerangka.master')

@section('content')
<div class="container py-4">

    {{-- JIKA PROFIL PEMBIMBING BELUM ADA --}}
    @if (!Auth::user()->pembimbing)

    <div class="alert alert-warning border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div class="flex-grow:1">
                <h5 class="fw-bold mb-1">Profil Pembimbing Belum Lengkap</h5>
                <p class="mb-0">
                    Anda wajib melengkapi data profil pembimbing sebelum dapat
                    mengakses dashboard dan fitur bimbingan.
                </p>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('pembimbing.profile.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Lengkapi Profil Sekarang
            </a>
        </div>
    </div>

    @else
    {{-- Welcome Card --}}
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-opacity-10">
        <div class="card-body py-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="bg-primary rounded-circle p-3">
                        <i class="bi bi-user-tie text-white fs-4"></i>
                    </div>
                </div>
                <div class="col">
                    <h3 class="fw-bold mb-1 text-dark">Dashboard Pembimbing</h3>
                    <p class="text-muted mb-0">
                        Selamat datang kembali,
                        <strong class="text-primary">{{ optional(Auth::user()->pembimbing)->nama ?? Auth::user()->username }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Total Pelajar --}}
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('pembimbing.bimbingan') }}" class="text-decoration-none">
                <div class="card text-white bg-primary shadow-sm border-0 h-100 clickable-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-uppercase fw-semibold mb-2 opacity-75 small">Total Pelajar</p>
                                <h2 class="fw-bold mb-0 display-6">{{ $totalMahasiswa ?? 0 }}</h2>
                                <p class="mb-0 small opacity-75">Peserta Bimbingan</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-users text-white fs-3"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-white bg-opacity-10 py-3">
                        <span class="text-white text-decoration-none small fw-semibold d-flex align-items-center">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-right ms-auto"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card 2: Kegiatan --}}
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('pembimbing.kegiatan') }}" class="text-decoration-none">
                <div class="card text-white bg-success shadow-sm border-0 h-100 clickable-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-uppercase fw-semibold mb-2 opacity-75 small">Total Kegiatan</p>
                                <h2 class="fw-bold mb-0 display-6">{{ $jumlahKegiatan ?? 0 }}</h2>
                                <p class="mb-0 small opacity-75">Aktivitas Bimbingan</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-tasks text-white fs-3"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-white bg-opacity-10 py-3">
                        <span class="text-white text-decoration-none small fw-semibold d-flex align-items-center">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-right ms-auto"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card 3: Presensi Hari Ini --}}
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('pembimbing.presensi') }}" class="text-decoration-none">
                <div class="card text-white bg-warning shadow-sm border-0 h-100 clickable-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-uppercase fw-semibold mb-2 opacity-75 small">Presensi Hari Ini</p>
                                <h2 class="fw-bold mb-0 display-6">{{ $presensiHariIni ?? 0 }}</h2>
                                <p class="mb-0 small opacity-75">Kehadiran Pelajar</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-tasks text-white fs-3"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-white bg-opacity-10 py-3">
                        <span class="text-white text-decoration-none small fw-semibold d-flex align-items-center">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-right ms-auto"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card 4: Laporan Menunggu --}}
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('pembimbing.laporan') }}" class="text-decoration-none">
                <div class="card text-white bg-info shadow-sm border-0 h-100 clickable-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-uppercase fw-semibold mb-2 opacity-75 small">Laporan Menunggu</p>
                                <h2 class="fw-bold mb-0 display-6">{{ $laporanMenunggu ?? 0 }}</h2>
                                <p class="mb-0 small opacity-75">Perlu Verifikasi</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-file-alt text-white fs-3"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-white bg-opacity-10 py-3">
                        <span class="text-white text-decoration-none small fw-semibold d-flex align-items-center">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-right ms-auto"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Content --}}
    <div class="row g-3">
        {{-- Laporan Kegiatan Terbaru --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-white">
                            <i class="bi bi-clipboard-list me-2"></i>
                            Laporan Kegiatan Terbaru
                        </h5>
                        <a href="{{ route('pembimbing.kegiatan') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-eye me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if (isset($laporanTerbaru) && $laporanTerbaru->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-info">
                                <tr class="text-center">
                                    <th class="py-3 px-3 text-dark fw-semibold">Pelajar</th>
                                    <th class="py-3 px-3 text-dark fw-semibold">Tanggal</th>
                                    <th class="py-3 px-3 text-dark fw-semibold">Judul</th>
                                    <th class="py-3 px-3 text-dark fw-semibold">Status</th>
                                    <th class="py-3 px-3 text-dark fw-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanTerbaru as $laporan)
                                <tr class="text-center">
                                    <td class="py-3 px-3 fw-medium text-start">
                                        {{ $laporan->pelajar->nama ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3 text-muted">
                                        {{ $laporan->waktu ?? \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-3 text-start">
                                        {{ Str::limit($laporan->topik ?? '-', 35) }}
                                    </td>
                                    <td class="py-3 px-3">
                                        @if ($laporan->status == 'Belum Dimulai')
                                        <span class="badge bg-warning text-dark">Belum Dimulai</span>
                                        @elseif($laporan->status == 'Dalam Proses')
                                        <span class="badge bg-info text-white">Dalam Proses</span>
                                        @elseif($laporan->status == 'Selesai')
                                        <span class="badge bg-success">Selesai</span>
                                        @else
                                        <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <a href="{{ route('pembimbing.kegiatan') }}"
                                            class="btn btn-sm btn-outline-primary" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fa-3x d-block mb-3 opacity-50"></i>
                        <p class="mb-0 fw-semibold">Belum ada laporan kegiatan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions & Information --}}
        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white border-0 py-3">
                    <h5 class="fw-bold mb-0 text-white">
                        <i class="bi bi-bolt me-2"></i>
                        Akses Cepat
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="{{ route('pembimbing.bimbingan') }}"
                            class="btn btn-outline-primary text-start py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="bi bi-users text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Kelola Pelajar</div>
                                    <small class="text-muted">Lihat daftar peserta bimbingan</small>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('pembimbing.kegiatan') }}" class="btn btn-outline-success text-start py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                    <i class="bi bi-tasks text-success"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Kelola Kegiatan</div>
                                    <small class="text-muted">Pantau aktivitas bimbingan</small>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('pembimbing.presensi') }}" class="btn btn-outline-warning text-start py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                    <i class="bi text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Kelola Presensi</div>
                                    <small class="text-muted">Monitor kehadiran pelajar</small>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('pembimbing.laporan') }}" class="btn btn-outline-info text-start py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                    <i class="bi bi-file-alt text-info"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Verifikasi Laporan</div>
                                    <small class="text-muted">Tinjau laporan akhir</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Information --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary border-bottom py-3">
                    <h5 class="fw-bold mb-0 text-light">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        Informasi Detail
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 mb-3" role="alert">
                        <div class="d-flex">
                            <i class="bi bi-lightbulb text-info me-3 fs-5"></i>
                            <div>
                                <strong class="d-block mb-1">Tips Bimbingan</strong>
                                <small>Periksa laporan kegiatan pelajar secara berkala untuk memastikan progres
                                    bimbingan berjalan lancar.</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 mb-0" role="alert">
                        <div class="d-flex">
                            <i class="bi bi-clock text-warning me-3 fs-5"></i>
                            <div>
                                <strong class="d-block mb-1">Pengingat</strong>
                                <small>Verifikasi laporan akhir tepat waktu untuk kelancaran administrasi.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

{{-- Custom CSS untuk Hover Effect --}}
@section('styles')
<style>
    .clickable-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
    }

    .clickable-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    .clickable-card:active {
        transform: translateY(-2px);
    }

    .clickable-card .card-body,
    .clickable-card .card-footer {
        transition: all 0.3s ease;
    }

    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }

    .table th {
        font-weight: 600;
        border: none;
    }

    .table td {
        border-color: #f8f9fa;
    }

    .btn-outline-primary,
    .btn-outline-success,
    .btn-outline-warning,
    .btn-outline-info {
        border: 1px solid;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }

    .btn-outline-success:hover {
        background-color: #198754;
        color: white;
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: black;
    }

    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }

    .alert {
        border-radius: 0.5rem;
    }
</style>
@endsection