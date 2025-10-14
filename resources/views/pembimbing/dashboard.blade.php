@extends('kerangka.master')

@section('content')
    <div class="container-fluid py-4">
        {{-- Welcome --}}
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body py-3">
                <h4 class="fw-bold mb-1 text-dark">Dashboard Pembimbing</h4>
                <p class="text-muted mb-0">
                    Selamat datang kembali,
                    <strong>{{ Auth::user()->pembimbing->nama ?? Auth::user()->username }}</strong>
                </p>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row g-3 mb-4">
            {{-- Card 1: Total Mahasiswa --}}
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('pembimbing.bimbingan') }}" class="text-decoration-none">
                    <div class="card text-white bg-primary shadow-sm border-0 h-100 clickable-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-uppercase fw-semibold mb-2 opacity-75" style="font-size: 0.75rem;">Total
                                        Mahasiswa</p>
                                    <h2 class="fw-bold mb-0 display-6">{{ $totalMahasiswa ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-people-fill opacity-50" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <div class="card-footer border-0 bg-white bg-opacity-10 py-2">
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
                    <div class="card text-white bg-danger shadow-sm border-0 h-100 clickable-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-uppercase fw-semibold mb-2 opacity-75" style="font-size: 0.75rem;">
                                        Kegiatan
                                    </p>
                                    <h2 class="fw-bold mb-0 display-6">{{ $jumlahKegiatan ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-journal-text opacity-50" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <div class="card-footer border-0 bg-white bg-opacity-10 py-2">
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
                    <div class="card text-white bg-info shadow-sm border-0 h-100 clickable-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-uppercase fw-semibold mb-2 opacity-75" style="font-size: 0.75rem;">
                                        Presensi
                                        Hari Ini</p>
                                    <h2 class="fw-bold mb-0 display-6">{{ $presensiHariIni ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-clipboard-check opacity-50" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <div class="card-footer border-0 bg-white bg-opacity-10 py-2">
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
        <div class="row g-4">
            {{-- Laporan Kegiatan Terbaru --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center py-3">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-clipboard-data me-2 text-primary"></i>
                            Laporan Kegiatan Terbaru
                        </h5>
                        <a href="{{ route('pembimbing.kegiatan') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if (isset($laporanTerbaru) && $laporanTerbaru->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-semibold text-muted small">Mahasiswa</th>
                                            <th class="fw-semibold text-muted small">Tanggal</th>
                                            <th class="fw-semibold text-muted small">Judul</th>
                                            <th class="fw-semibold text-muted small">Status</th>
                                            <th class="fw-semibold text-muted small text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($laporanTerbaru as $laporan)
                                            <tr>
                                                <td class="fw-semibold text-dark">{{ $laporan->mahasiswa->nama ?? '-' }}
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $laporan->waktu ?? \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ Str::limit($laporan->topik ?? '-', 35) }}</td>
                                                <td>
                                                    @if ($laporan->status == 'Belum Dimulai')
                                                        <span class="badge bg-warning text-dark">Belum Dimulai</span>
                                                    @elseif($laporan->status == 'Dalam Proses')
                                                        <span class="badge bg-info">Dalam Proses</span>
                                                    @elseif($laporan->status == 'Selesai')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('pembimbing.kegiatan') }}"
                                                        class="btn btn-sm btn-outline-primary" title="Lihat Detail">
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
                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                <p class="mb-0 fw-semibold">Belum ada laporan kegiatan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar bisa ditambahkan di col-lg-4 --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-info-circle me-2 text-primary"></i>
                            Informasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3" role="alert">
                            <i class="bi bi-lightbulb me-2"></i>
                            <strong>Tips:</strong> Periksa laporan kegiatan mahasiswa secara berkala untuk memastikan
                            progres bimbingan berjalan lancar.
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('pembimbing.bimbingan') }}" class="btn btn-outline-primary">
                                <i class="bi bi-people me-2"></i>Kelola Mahasiswa
                            </a>
                            <a href="{{ route('pembimbing.kegiatan') }}" class="btn btn-outline-danger">
                                <i class="bi bi-journal-text me-2"></i>Kelola Kegiatan
                            </a>
                            <a href="{{ route('pembimbing.presensi') }}" class="btn btn-outline-info">
                                <i class="bi bi-clipboard-check me-2"></i>Kelola Presensi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom CSS untuk Hover Effect --}}
    <style>
        .clickable-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .clickable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2) !important;
        }

        .clickable-card:active {
            transform: translateY(-2px);
        }

        /* Smooth transition for all cards */
        .clickable-card .card-body,
        .clickable-card .card-footer {
            transition: all 0.3s ease;
        }

        /* Optional: Add subtle scale effect on hover */
        .clickable-card:hover .bi {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }
    </style>
@endsection
