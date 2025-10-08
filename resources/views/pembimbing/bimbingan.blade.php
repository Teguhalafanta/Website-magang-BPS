@extends('kerangka.master')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-1">Daftar Peserta Bimbingan</h3>
                <p class="text-muted mb-0">Kelola dan lihat detail peserta bimbingan</p>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-dark fw-semibold">Data Peserta</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3" style="width: 60px;">No</th>
                                <th class="py-3">Nama</th>
                                <th class="py-3">Asal Institusi</th>
                                <th class="py-3">Jurusan</th>
                                <th class="text-center py-3" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pelajars as $key => $p)
                                <tr>
                                    <td class="text-center fw-semibold">{{ $key + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                <span class="fw-bold">{{ strtoupper(substr($p->nama, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $p->nama }}</div>
                                                <small class="text-muted">{{ $p->nim_nisn ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $p->asal_institusi }}</td>
                                    <td>
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info px-3 py-2">{{ $p->jurusan }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $p->id }}">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Detail Peserta -->
                                <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1"
                                    aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white border-0">
                                                <div>
                                                    <h5 class="modal-title mb-0" id="detailModalLabel{{ $p->id }}">
                                                        Detail Peserta Bimbingan
                                                    </h5>
                                                    <small class="opacity-75">{{ $p->nama }}</small>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-4">
                                                <!-- Personal Information -->
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                                        <i class="bi bi-person-fill me-2"></i>Informasi Pribadi
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">Nama
                                                                    Lengkap</div>
                                                                <div class="fw-semibold">: {{ $p->nama }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">Jenis
                                                                    Kelamin</div>
                                                                <div class="fw-semibold">: {{ $p->jenis_kelamin }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Tempat Lahir</div>
                                                                <div class="fw-semibold">: {{ $p->tempat_lahir }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Tanggal Lahir</div>
                                                                <div class="fw-semibold">:
                                                                    {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Alamat</div>
                                                                <div class="fw-semibold">: {{ $p->alamat }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Contact Information -->
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                                        <i class="bi bi-telephone-fill me-2"></i>Informasi Kontak
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">No.
                                                                    Telepon</div>
                                                                <div class="fw-semibold">: {{ $p->telepon }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Email</div>
                                                                <div class="fw-semibold">: {{ $p->email }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Academic Information -->
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                                        <i class="bi bi-mortarboard-fill me-2"></i>Informasi Akademik
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">NIM
                                                                    / NISN</div>
                                                                <div class="fw-semibold">: {{ $p->nim_nisn }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Asal Institusi</div>
                                                                <div class="fw-semibold">: {{ $p->asal_institusi }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Fakultas</div>
                                                                <div class="fw-semibold">: {{ $p->fakultas }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Jurusan</div>
                                                                <div class="fw-semibold">: {{ $p->jurusan }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Schedule Information -->
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                                        <i class="bi bi-calendar-event-fill me-2"></i>Jadwal Bimbingan
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Rencana Mulai</div>
                                                                <div class="fw-semibold">: {{ $p->rencana_mulai }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <div class="text-muted me-2" style="min-width: 140px;">
                                                                    Rencana Selesai</div>
                                                                <div class="fw-semibold">: {{ $p->rencana_selesai }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Documents -->
                                                <div>
                                                    <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                                        <i class="bi bi-file-earmark-text-fill me-2"></i>Dokumen
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="card border">
                                                                <div class="card-body p-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div
                                                                            class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                                            <i
                                                                                class="bi bi-file-pdf-fill text-primary fs-4"></i>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <small
                                                                                class="text-muted d-block">Proposal</small>
                                                                            @if ($p->proposal)
                                                                                <a href="{{ asset('storage/' . $p->proposal) }}"
                                                                                    target="_blank"
                                                                                    class="btn btn-sm btn-outline-primary mt-1">
                                                                                    <i class="bi bi-download me-1"></i>
                                                                                    Unduh
                                                                                </a>
                                                                            @else
                                                                                <span class="text-muted small">Belum
                                                                                    tersedia</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card border">
                                                                <div class="card-body p-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div
                                                                            class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                                            <i
                                                                                class="bi bi-file-pdf-fill text-primary fs-4"></i>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <small class="text-muted d-block">Surat
                                                                                Pengajuan</small>
                                                                            @if ($p->surat_pengajuan)
                                                                                <a href="{{ asset('storage/' . $p->surat_pengajuan) }}"
                                                                                    target="_blank"
                                                                                    class="btn btn-sm btn-outline-primary mt-1">
                                                                                    <i class="bi bi-download me-1"></i>
                                                                                    Unduh
                                                                                </a>
                                                                            @else
                                                                                <span class="text-muted small">Belum
                                                                                    tersedia</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer bg-light border-0">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4"
                                                    data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle me-1"></i> Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <p class="mb-0">Belum ada peserta bimbingan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
