@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        {{-- Main Card --}}
        <div class="card shadow-sm border-0" style="max-width: 700px; margin: 0 auto;">
            {{-- Header --}}
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text"></i> Detail Kegiatan
                    </h5>
                    <a href="{{ route('pelajar.kegiatan.harian') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body p-4">
                {{-- Status Badge --}}
                <div class="text-end mb-3">
                    @if ($kegiatan->status_penyelesaian === 'Selesai')
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-check-circle-fill"></i> Selesai
                        </span>
                    @elseif ($kegiatan->status_penyelesaian === 'Dalam Proses')
                        <span class="badge bg-warning text-dark px-3 py-2">
                            <i class="bi bi-clock-history"></i> Dalam Proses
                        </span>
                    @else
                        <span class="badge bg-secondary px-3 py-2">
                            <i class="bi bi-circle"></i> Belum Dimulai
                        </span>
                    @endif
                </div>

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted mb-1">
                        <i class="bi bi-calendar-event text-primary"></i> Tanggal
                    </label>
                    <input type="text" class="form-control"
                        value="{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}" readonly>
                </div>

                {{-- Nama Kegiatan --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted mb-1">
                        <i class="bi bi-pencil-square text-success"></i> Nama Kegiatan
                    </label>
                    <input type="text" class="form-control" value="{{ $kegiatan->nama_kegiatan }}" readonly>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted mb-1">
                        <i class="bi bi-card-text text-info"></i> Deskripsi
                    </label>
                    <textarea class="form-control" rows="4" readonly>{{ $kegiatan->deskripsi ?? 'Tidak ada deskripsi' }}</textarea>
                </div>

                {{-- Row untuk Pemberi Tugas & Tim Kerja --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted mb-1">
                            <i class="bi bi-person-badge text-warning"></i> Pemberi Tugas
                        </label>
                        <input type="text" class="form-control" value="{{ $kegiatan->pemberi_tugas ?? '-' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted mb-1">
                            <i class="bi bi-people-fill text-success"></i> Tim Kerja
                        </label>
                        <input type="text" class="form-control" value="{{ $kegiatan->tim_kerja ?? '-' }}" readonly>
                    </div>
                </div>

                {{-- Status Penyelesaian --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted mb-1">
                        <i class="bi bi-flag text-danger"></i> Status Penyelesaian
                    </label>
                    <input type="text" class="form-control" value="{{ $kegiatan->status_penyelesaian }}" readonly>
                </div>

                {{-- Bukti Dukung --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted mb-1">
                        <i class="bi bi-paperclip text-primary"></i> Bukti Dukung
                    </label>
                    @if ($kegiatan->bukti_dukung)
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ basename($kegiatan->bukti_dukung) }}"
                                readonly>
                            <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}" target="_blank"
                                class="btn btn-primary">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                        </div>
                    @else
                        <input type="text" class="form-control" value="Tidak ada bukti dukung" readonly>
                    @endif
                </div>

                {{-- Timestamp Info --}}
                <div class="alert alert-light border mb-0">
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> Dibuat: {{ $kegiatan->created_at->translatedFormat('d M Y, H:i') }}
                        @if ($kegiatan->created_at != $kegiatan->updated_at)
                            <br>
                            <i class="bi bi-arrow-clockwise"></i> Diperbarui: {{ $kegiatan->updated_at->diffForHumans() }}
                        @endif
                    </small>
                </div>
            </div>

            {{-- Footer --}}
            <div class="card-footer bg-light border-0 py-3">
                <div class="d-flex justify-content-end gap-2">
                    @php
                        $isMagangSelesai =
                            auth()->user()->pelajar && auth()->user()->pelajar->status_magang === 'selesai';
                    @endphp

                    <a href="{{ route('pelajar.kegiatan.harian') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>

                    @if (!$isMagangSelesai)
                        <a href="{{ route('pelajar.kegiatan.edit', $kegiatan->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Custom CSS --}}
    <style>
        .form-control:read-only {
            background-color: #f8f9fa;
            cursor: default;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: none;
        }

        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-label i {
            width: 20px;
        }

        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .badge {
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
