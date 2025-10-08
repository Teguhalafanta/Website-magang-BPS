@extends('kerangka.master')

@section('content')
    <div class="container-fluid py-4">
        {{-- Welcome --}}
        <div class="card shadow-sm border-0 mb-4 bg-body-tertiary">
            <div class="card-body">
                <h4 class="fw-bold mb-1">Dashboard Pembimbing 👋</h4>
                <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong></p>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-primary bg-gradient shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-uppercase fw-semibold opacity-75">Total Mahasiswa</small>
                                <h2 class="fw-bold mb-0">{{ $totalMahasiswa ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-people-fill fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-transparent">
                        <a href="{{ route('pembimbing.bimbingan') }}" class="text-white text-decoration-none fw-semibold">
                            Lihat Detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-danger bg-gradient shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-uppercase fw-semibold opacity-75">Laporan Menunggu</small>
                                <h2 class="fw-bold mb-0">{{ $laporanMenunggu ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-journal-text fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-transparent">
                        <a href="{{ route('pembimbing.kegiatan') }}" class="text-white text-decoration-none fw-semibold">
                            Lihat Detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-info bg-gradient shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-uppercase fw-semibold opacity-75">Presensi Hari Ini</small>
                                <h2 class="fw-bold mb-0">{{ $presensiHariIni ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-clipboard-check fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-transparent">
                        <a href="{{ route('pembimbing.presensi') }}" class="text-white text-decoration-none fw-semibold">
                            Lihat Detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-warning bg-gradient shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-uppercase fw-semibold opacity-75">Belum Dinilai</small>
                                <h2 class="fw-bold mb-0">{{ $belumDinilai ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-star-fill fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-transparent">
                        <a href="{{ route('pembimbing.penilaian') }}" class="text-white text-decoration-none fw-semibold">
                            Lihat Detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="row g-4">
            {{-- Laporan Kegiatan Terbaru --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-body-tertiary d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-clipboard-data me-2"></i>Laporan Kegiatan Terbaru</h5>
                        <a href="{{ route('pembimbing.kegiatan') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        @if (isset($laporanTerbaru) && $laporanTerbaru->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Tanggal</th>
                                            <th>Judul</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($laporanTerbaru as $laporan)
                                            <tr>
                                                <td class="fw-semibold">{{ $laporan->mahasiswa->name ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}</td>
                                                <td>{{ Str::limit($laporan->judul, 35) }}</td>
                                                <td>
                                                    @if ($laporan->status == 'menunggu')
                                                        <span
                                                            class="badge bg-warning-subtle text-warning fw-semibold">Menunggu</span>
                                                    @elseif($laporan->status == 'disetujui')
                                                        <span
                                                            class="badge bg-success-subtle text-success fw-semibold">Disetujui</span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger-subtle text-danger fw-semibold">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('pembimbing.kegiatan') }}"
                                                        class="btn btn-sm btn-outline-primary">
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
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mb-0">Belum ada laporan kegiatan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Mahasiswa Bimbingan --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-body-tertiary d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Mahasiswa Bimbingan</h5>
                        <a href="{{ route('pembimbing.bimbingan') }}" class="btn btn-sm btn-primary">Semua</a>
                    </div>
                    <div class="card-body">
                        @if (isset($mahasiswaBimbingan) && $mahasiswaBimbingan->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($mahasiswaBimbingan as $mhs)
                                    <div class="list-group-item border-0 d-flex align-items-center px-0">
                                        <div class="bg-primary-subtle text-primary d-flex align-items-center justify-content-center rounded-circle me-3"
                                            style="width:45px;height:45px;">
                                            <i class="bi bi-person-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $mhs->name }}</div>
                                            <small class="text-muted">{{ $mhs->nim ?? '-' }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-people fs-1"></i>
                                <p class="mb-0">Belum ada mahasiswa bimbingan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
