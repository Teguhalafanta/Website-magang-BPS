@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <h3 class="fw-bold bps-title mb-3">
            <i class="bi bi-list-check me-2"></i>Kegiatan Harian
        </h3>
        <p class="bps-subtitle">Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>

        @php
            $pelajar = auth()->user()->pelajar;
            $tanggalMulai = \Carbon\Carbon::parse($pelajar->rencana_mulai ?? now());
            $tanggalSelesai = \Carbon\Carbon::parse($pelajar->rencana_selesai ?? now());
            $magangBelumDimulai = now()->lt($tanggalMulai);
        @endphp

        {{-- ALERT SUCCESS/ERROR --}}
        @if (session('success'))
            <div class="alert bps-alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert bps-alert-danger alert-dismissible fade show">
                <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($magangBelumDimulai)
            <div class="alert bps-alert-light border mb-3">
                <i class="bi bi-calendar-event me-2"></i>
                Magang belum dimulai. Anda dapat menambah kegiatan mulai
                <strong>{{ $tanggalMulai->format('d F Y') }}</strong>.
            </div>
        @endif

        @if (!$isMagangSelesai && !$magangBelumDimulai)
            <div class="mb-3 text-start">
                <button type="button" class="btn bps-btn-primary" data-bs-toggle="modal"
                    data-bs-target="#tambahKegiatanModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Kegiatan
                </button>
            </div>
        @endif

        <table class="table table-bordered bps-table">
            <thead class="bps-table-header">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Bukti Dukung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatans as $index => $kegiatan)
                    <tr class="bps-table-row">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ Str::limit($kegiatan->deskripsi, 50) }}</td>
                        <td class="text-center">
                            @if ($kegiatan->status_penyelesaian === 'Selesai')
                                <span class="badge bps-badge-success">Selesai</span>
                            @elseif ($kegiatan->status_penyelesaian === 'Dalam Proses')
                                <span class="badge bps-badge-warning">Proses</span>
                            @elseif ($kegiatan->status_penyelesaian === 'Belum Dimulai')
                                <span class="badge bps-badge-secondary">Belum Dimulai</span>
                            @else
                                <span class="badge bps-badge-dark">Tidak Diketahui</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($kegiatan->bukti_dukung)
                                <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}" target="_blank"
                                    class="btn btn-sm bps-btn-info">Lihat</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- Tombol Detail dengan Modal --}}
                            <button class="btn btn-sm bps-btn-info" data-bs-toggle="modal"
                                data-bs-target="#detailModal{{ $kegiatan->id }}">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Detail untuk setiap kegiatan --}}
                    <div class="modal fade" id="detailModal{{ $kegiatan->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bps-modal">
                                <div class="modal-header bps-modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-file-earmark-text"></i> Detail Kegiatan
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Status Badge --}}
                                    <div class="text-end mb-3">
                                        @if ($kegiatan->status_penyelesaian === 'Selesai')
                                            <span class="badge bps-badge-success px-3 py-2">
                                                <i class="bi bi-check-circle-fill"></i> Selesai
                                            </span>
                                        @elseif ($kegiatan->status_penyelesaian === 'Dalam Proses')
                                            <span class="badge bps-badge-warning px-3 py-2">
                                                <i class="bi bi-clock-history"></i> Dalam Proses
                                            </span>
                                        @else
                                            <span class="badge bps-badge-secondary px-3 py-2">
                                                <i class="bi bi-circle"></i> Belum Dimulai
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Tanggal --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold bps-label mb-1">
                                            <i class="bi bi-calendar-event bps-icon-primary"></i> Tanggal
                                        </label>
                                        <input type="text" class="form-control bps-input"
                                            value="{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}"
                                            readonly>
                                    </div>

                                    {{-- Nama Kegiatan --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold bps-label mb-1">
                                            <i class="bi bi-pencil-square bps-icon-accent"></i> Nama Kegiatan
                                        </label>
                                        <input type="text" class="form-control bps-input"
                                            value="{{ $kegiatan->nama_kegiatan }}" readonly>
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold bps-label mb-1">
                                            <i class="bi bi-card-text bps-icon-info"></i> Deskripsi
                                        </label>
                                        <textarea class="form-control bps-input" rows="3" readonly>{{ $kegiatan->deskripsi ?? 'Tidak ada deskripsi' }}</textarea>
                                    </div>

                                    {{-- Pemberi Tugas & Tim Kerja --}}
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-bold bps-label mb-1">
                                                <i class="bi bi-person-badge bps-icon-warning"></i> Pemberi Tugas
                                            </label>
                                            <input type="text" class="form-control bps-input"
                                                value="{{ $kegiatan->pemberi_tugas ?? '-' }}" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-bold bps-label mb-1">
                                                <i class="bi bi-people-fill bps-icon-accent"></i> Tim Kerja
                                            </label>
                                            <input type="text" class="form-control bps-input"
                                                value="{{ $kegiatan->tim_kerja ?? '-' }}" readonly>
                                        </div>
                                    </div>

                                    {{-- Bukti Dukung --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold bps-label mb-1">
                                            <i class="bi bi-paperclip bps-icon-primary"></i> Bukti Dukung
                                        </label>
                                        @if ($kegiatan->bukti_dukung)
                                            <div class="input-group">
                                                <input type="text" class="form-control bps-input"
                                                    value="{{ basename($kegiatan->bukti_dukung) }}" readonly>
                                                <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}"
                                                    target="_blank" class="btn bps-btn-primary btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <input type="text" class="form-control bps-input" value="Tidak ada"
                                                readonly>
                                        @endif
                                    </div>

                                    {{-- Info Timestamp --}}
                                    <div class="alert bps-alert-light border mb-0">
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $kegiatan->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer bps-modal-footer">
                                    <button type="button" class="btn bps-btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle"></i> Tutup
                                    </button>
                                    @if (!$isMagangSelesai)
                                        <a href="{{ route('pelajar.kegiatan.edit', $kegiatan->id) }}"
                                            class="btn bps-btn-warning">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center bps-empty-state">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                            Belum ada kegiatan hari ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah Kegiatan - HANYA RENDER JIKA MAGANG BELUM SELESAI --}}
    @if (!$isMagangSelesai && !$magangBelumDimulai)
        <div class="modal fade" id="tambahKegiatanModal" tabindex="-1" aria-labelledby="tambahKegiatanModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('pelajar.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content bps-modal">
                        <div class="modal-header bps-modal-header">
                            <h5 class="modal-title" id="tambahKegiatanModalLabel">
                                <i class="bi bi-plus-circle"></i> Tambah Kegiatan Baru
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label bps-label">Tanggal <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control bps-input" id="tanggal" name="tanggal"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama_kegiatan" class="form-label bps-label">Nama Kegiatan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control bps-input" id="nama_kegiatan"
                                    name="nama_kegiatan" placeholder="Contoh: Membuat laporan bulanan" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label bps-label">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control bps-input" id="deskripsi" name="deskripsi" rows="3"
                                    placeholder="Detail kegiatan yang dilakukan" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="pemberi_tugas" class="form-label bps-label">Pemberi Tugas</label>
                                    <input type="text" class="form-control bps-input" id="pemberi_tugas"
                                        name="pemberi_tugas" placeholder="Nama pemberi tugas">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="tim_kerja" class="form-label bps-label">Tim Kerja</label>
                                    <select class="form-select bps-input" id="tim_kerja" name="tim_kerja">
                                        <option value="">-- Pilih Tim --</option>
                                        <option value="Tim Umum">Tim Umum</option>
                                        <option value="Tim Statistik Sosial">Tim Statistik Sosial</option>
                                        <option value="Tim Statistik Produksi">Tim Statistik Produksi</option>
                                        <option value="Tim Statistik Harga, Distribusi dan Jasa">Tim Statistik Harga,
                                            Distribusi dan Jasa</option>
                                        <option value="Tim Neraca Wilayah dan Analisis Statistik">Tim Neraca Wilayah dan
                                            Analisis Statistik</option>
                                        <option value="Tim Metodologi, Pengolahan, Infrastruktur dan Inovasi Digital">Tim
                                            Metodologi, Pengolahan, Infrastruktur dan Inovasi Digital</option>
                                        <option value="Tim Diseminasi dan Pelayanan Statistik">Tim Diseminasi dan Pelayanan
                                            Statistik</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label bps-label">Status Penyelesaian <span
                                        class="text-danger">*</span></label>
                                <select class="form-select bps-input" id="status" name="status" required>
                                    <option value="Belum Dimulai">Belum Dimulai</option>
                                    <option value="Dalam Proses">Dalam Proses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label bps-label">Bukti Dukung (Opsional)</label>
                                <input type="file" name="bukti_dukung" class="form-control bps-input"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)</small>
                            </div>
                        </div>

                        <div class="modal-footer bps-modal-footer">
                            <button type="button" class="btn bps-btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Batal
                            </button>
                            <button type="submit" class="btn bps-btn-primary">
                                <i class="bi bi-save"></i> Simpan Kegiatan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Custom CSS dengan Tema BPS --}}
    <style>
        /* ========== WARNA UTAMA BPS ========== */
        :root {
            --bps-primary: #003d7a;
            --bps-secondary: #0055a5;
            --bps-accent: #fdb913;
            --bps-light: #e8f1f8;
            --bps-dark: #002147;
            --bps-success: #28a745;
            --bps-warning: #ffc107;
            --bps-danger: #dc3545;
            --bps-info: #17a2b8;
        }

        /* ========== TITLE & SUBTITLE ========== */
        .bps-title {
            color: var(--bps-primary);
            border-bottom: 3px solid var(--bps-accent);
            padding-bottom: 10px;
            display: inline-block;
        }

        .bps-subtitle {
            color: var(--bps-dark);
            font-weight: 500;
        }

        /* ========== ALERTS ========== */
        .bps-alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid var(--bps-success);
            color: #155724;
        }

        .bps-alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left: 4px solid var(--bps-danger);
            color: #721c24;
        }

        .bps-alert-light {
            background: linear-gradient(135deg, var(--bps-light) 0%, #f8fbff 100%);
            border-left: 3px solid var(--bps-accent);
        }

        /* ========== BUTTONS ========== */
        .bps-btn-primary {
            background: linear-gradient(135deg, var(--bps-primary) 0%, var(--bps-secondary) 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0, 61, 122, 0.2);
        }

        .bps-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 61, 122, 0.3);
            background: linear-gradient(135deg, var(--bps-secondary) 0%, var(--bps-primary) 100%);
        }

        .bps-btn-info {
            background: var(--bps-info);
            border: none;
            color: white;
            transition: all 0.3s;
        }

        .bps-btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
        }

        .bps-btn-secondary {
            background: #6c757d;
            border: none;
            color: white;
            transition: all 0.3s;
        }

        .bps-btn-secondary:hover {
            background: #5a6268;
        }

        .bps-btn-warning {
            background: linear-gradient(135deg, var(--bps-warning) 0%, #e0a800 100%);
            border: none;
            color: #212529;
            font-weight: 600;
            transition: all 0.3s;
        }

        .bps-btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        }

        /* ========== TABLE ========== */
        .bps-table {
            border: 2px solid var(--bps-light);
            border-radius: 8px;
            overflow: hidden;
        }

        .bps-table-header {
            background: linear-gradient(135deg, var(--bps-primary) 0%, var(--bps-secondary) 100%);
            color: white;
            font-weight: 600;
        }

        .bps-table-header th {
            border-color: rgba(255, 255, 255, 0.2);
            padding: 12px;
        }

        .bps-table-row {
            transition: all 0.2s;
        }

        .bps-table-row:hover {
            background: linear-gradient(90deg, var(--bps-light) 0%, #ffffff 100%);
        }

        .bps-empty-state {
            padding: 40px 20px;
            color: #6c757d;
        }

        /* ========== BADGES ========== */
        .bps-badge-success {
            background: linear-gradient(135deg, var(--bps-success) 0%, #218838 100%);
            color: white;
            font-weight: 600;
            padding: 0.4em 0.8em;
        }

        .bps-badge-warning {
            background: linear-gradient(135deg, var(--bps-warning) 0%, #e0a800 100%);
            color: #212529;
            font-weight: 600;
            padding: 0.4em 0.8em;
        }

        .bps-badge-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            font-weight: 600;
            padding: 0.4em 0.8em;
        }

        .bps-badge-dark {
            background: linear-gradient(135deg, var(--bps-dark) 0%, #001a33 100%);
            color: white;
            font-weight: 600;
            padding: 0.4em 0.8em;
        }

        /* ========== MODAL ========== */
        .bps-modal {
            border-radius: 12px;
            border: none;
            border-left: 4px solid var(--bps-accent);
        }

        .bps-modal-header {
            background: linear-gradient(135deg, var(--bps-primary) 0%, var(--bps-secondary) 100%);
            color: white;
            border-bottom: none;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .bps-modal-footer {
            border-top: 2px solid var(--bps-light);
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        /* ========== FORM ELEMENTS ========== */
        .bps-input {
            border: 2px solid var(--bps-light);
            transition: all 0.3s;
        }

        .bps-input:focus {
            border-color: var(--bps-accent);
            box-shadow: 0 0 0 0.2rem rgba(253, 185, 19, 0.25);
        }

        .bps-input:read-only {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            cursor: default;
            border-color: #dee2e6;
        }

        .bps-label {
            color: var(--bps-dark);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .bps-label i {
            width: 20px;
        }

        /* ========== ICON COLORS ========== */
        .bps-icon-primary {
            color: var(--bps-primary);
        }

        .bps-icon-accent {
            color: var(--bps-accent);
        }

        .bps-icon-info {
            color: var(--bps-info);
        }

        .bps-icon-warning {
            color: var(--bps-warning);
        }

        /* ========== TRANSITIONS ========== */
        .btn,
        .badge,
        .form-control,
        .form-select {
            transition: all 0.3s ease;
        }
    </style>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
