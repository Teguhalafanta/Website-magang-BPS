@extends('kerangka.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-primary mb-0">
                <i class="bi bi-journal-text me-2"></i>Daftar Kegiatan Pelajar
            </h4>
        </div>

        {{-- Card Container --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-0 text-muted">
                            Total: <span class="badge bg-primary">{{ $kegiatans->total() }}</span> kegiatan
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.kegiatan.index') }}"
                            class="d-flex justify-content-end">
                            <div class="input-group" style="max-width: 350px;">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari kegiatan atau pelajar..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                                @if (request('search'))
                                    <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if ($kegiatans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 5%;">No</th>
                                    <th style="width: 15%;">Pelajar</th>
                                    <th style="width: 10%;">Tanggal</th>
                                    <th style="width: 20%;">Nama Kegiatan</th>
                                    <th style="width: 12%;">Status</th>
                                    <th style="width: 10%;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kegiatans as $index => $kegiatan)
                                    <tr>
                                        <td class="ps-4">{{ $kegiatans->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div class="fw-semibold text-truncate" style="font-size: 0.875rem;">
                                                        {{ $kegiatan->pelajar->nama ?? 'N/A' }}
                                                    </div>
                                                    <small
                                                        class="text-muted text-truncate d-block">{{ $kegiatan->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d M Y') }}
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 250px;"
                                                title="{{ $kegiatan->nama_kegiatan }}">
                                                {{ $kegiatan->nama_kegiatan }}
                                            </div>
                                        </td>

                                        <td>
                                            @php
                                                $statusClass = match ($kegiatan->status_penyelesaian) {
                                                    'Selesai' => 'success',
                                                    'Dalam Proses' => 'warning',
                                                    'Belum Dimulai' => 'secondary',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}" style="font-size: 0.75rem;">
                                                {{ $kegiatan->status_penyelesaian ?? 'Belum Dimulai' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $kegiatan->id }}" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Detail --}}
                                    <div class="modal fade" id="detailModal{{ $kegiatan->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-info-circle me-2"></i>Detail Kegiatan
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Pelajar</label>
                                                            <p class="fw-semibold mb-0">
                                                                {{ $kegiatan->user->name ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Tanggal</label>
                                                            <p class="fw-semibold mb-0">
                                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d F Y') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="mb-3">
                                                        <label class="text-muted small mb-1">Nama Kegiatan</label>
                                                        <p class="fw-semibold mb-0">{{ $kegiatan->nama_kegiatan }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small mb-1">Deskripsi</label>
                                                        <p class="mb-0" style="white-space: pre-wrap;">
                                                            {{ $kegiatan->deskripsi ?? '-' }}</p>
                                                    </div>
                                                    <hr>
                                                    <div class="row mb-3">
                                                        <div class="col-md-4">
                                                            <label class="text-muted small mb-1">Status</label>
                                                            <p class="mb-0">
                                                                <span class="badge bg-{{ $statusClass }}">
                                                                    {{ $kegiatan->status_penyelesaian ?? 'Belum Dimulai' }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Pemberi Tugas</label>
                                                            <p class="mb-0">{{ $kegiatan->pemberi_tugas ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Tim Kerja</label>
                                                            <p class="mb-0">{{ $kegiatan->tim_kerja ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                    <td class="text-center align-middle">
                                                        @if ($kegiatan->bukti_dukung)
                                                            <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}"
                                                                target="_blank"
                                                                class="text-decoration-none text-primary fw-semibold">
                                                                Lihat Bukti
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>

                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Dibuat</label>
                                                            <p class="mb-0 small">
                                                                {{ $kegiatan->created_at->format('d M Y, H:i') }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Terakhir Diupdate</label>
                                                            <p class="mb-0 small">
                                                                {{ $kegiatan->updated_at->format('d M Y, H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted small mb-2 mb-md-0">
                                Menampilkan {{ $kegiatans->firstItem() }} - {{ $kegiatans->lastItem() }} dari
                                {{ $kegiatans->total() }} kegiatan
                            </div>
                            <div>
                                {{ $kegiatans->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">
                            @if (request('search'))
                                Tidak ada kegiatan yang sesuai dengan pencarian "<strong>{{ request('search') }}</strong>"
                            @else
                                Belum ada kegiatan yang terdaftar
                            @endif
                        </p>
                        @if (request('search'))
                            <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Semua Kegiatan
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table> :not(caption)>*>* {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }

        .avatar-sm {
            flex-shrink: 0;
        }

        .modal-body label {
            font-weight: 600;
        }
    </style>
@endpush
