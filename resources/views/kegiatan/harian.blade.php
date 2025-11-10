@extends('kerangka.master')

@section('content')
    <div class="container">
        <h3>Kegiatan Harian</h3>
        <p>Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>

        {{-- ALERT SUCCESS/ERROR --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (!$isMagangSelesai)
            <div class="mb-3 text-start">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Kegiatan
                </button>
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
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
                    <tr class="text-center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ Str::limit($kegiatan->deskripsi, 50) }}</td>
                        <td>
                            @if ($kegiatan->status_penyelesaian === 'Selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif ($kegiatan->status_penyelesaian === 'Dalam Proses')
                                <span class="badge bg-warning text-dark">Proses</span>
                            @elseif ($kegiatan->status_penyelesaian === 'Belum Dimulai')
                                <span class="badge bg-secondary">Belum Dimulai</span>
                            @else
                                <span class="badge bg-dark">Tidak Diketahui</span>
                            @endif
                        </td>
                        <td>
                            @if ($kegiatan->bukti_dukung)
                                <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}" target="_blank"
                                    class="btn btn-sm btn-primary">Lihat</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            {{-- Tombol Detail dengan Modal --}}
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#detailModal{{ $kegiatan->id }}">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Detail untuk setiap kegiatan --}}
                    <div class="modal fade" id="detailModal{{ $kegiatan->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
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
                                            value="{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}"
                                            readonly>
                                    </div>

                                    {{-- Nama Kegiatan --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted mb-1">
                                            <i class="bi bi-pencil-square text-success"></i> Nama Kegiatan
                                        </label>
                                        <input type="text" class="form-control" value="{{ $kegiatan->nama_kegiatan }}"
                                            readonly>
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted mb-1">
                                            <i class="bi bi-card-text text-info"></i> Deskripsi
                                        </label>
                                        <textarea class="form-control" rows="3" readonly>{{ $kegiatan->deskripsi ?? 'Tidak ada deskripsi' }}</textarea>
                                    </div>

                                    {{-- Pemberi Tugas & Tim Kerja --}}
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-bold text-muted mb-1">
                                                <i class="bi bi-person-badge text-warning"></i> Pemberi Tugas
                                            </label>
                                            <input type="text" class="form-control"
                                                value="{{ $kegiatan->pemberi_tugas ?? '-' }}" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-bold text-muted mb-1">
                                                <i class="bi bi-people-fill text-success"></i> Tim Kerja
                                            </label>
                                            <input type="text" class="form-control"
                                                value="{{ $kegiatan->tim_kerja ?? '-' }}" readonly>
                                        </div>
                                    </div>

                                    {{-- Bukti Dukung --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted mb-1">
                                            <i class="bi bi-paperclip text-primary"></i> Bukti Dukung
                                        </label>
                                        @if ($kegiatan->bukti_dukung)
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    value="{{ basename($kegiatan->bukti_dukung) }}" readonly>
                                                <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <input type="text" class="form-control" value="Tidak ada" readonly>
                                        @endif
                                    </div>

                                    {{-- Info Timestamp --}}
                                    <div class="alert alert-light border mb-0">
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $kegiatan->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle"></i> Tutup
                                    </button>
                                    @if (!$isMagangSelesai)
                                        <a href="{{ route('pelajar.kegiatan.edit', $kegiatan->id) }}"
                                            class="btn btn-warning">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada kegiatan hari ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah Kegiatan - HANYA RENDER JIKA MAGANG BELUM SELESAI --}}
    @if (!$isMagangSelesai)
        <div class="modal fade" id="tambahKegiatanModal" tabindex="-1" aria-labelledby="tambahKegiatanModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('pelajar.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="tambahKegiatanModalLabel">
                                <i class="bi bi-plus-circle"></i> Tambah Kegiatan Baru
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan"
                                    placeholder="Contoh: Membuat laporan bulanan" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                    placeholder="Detail kegiatan yang dilakukan" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="pemberi_tugas" class="form-label">Pemberi Tugas</label>
                                    <input type="text" class="form-control" id="pemberi_tugas" name="pemberi_tugas"
                                        placeholder="Nama pemberi tugas">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="tim_kerja" class="form-label">Tim Kerja</label>
                                    <select class="form-select" id="tim_kerja" name="tim_kerja">
                                        <option value="">-- Pilih Tim --</option>
                                        <option value="TIM UMUM">TIM UMUM</option>
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
                                <label for="status" class="form-label">Status Penyelesaian <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Belum Dimulai">Belum Dimulai</option>
                                    <option value="Dalam Proses">Dalam Proses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bukti Dukung (Opsional)</label>
                                <input type="file" name="bukti_dukung" class="form-control"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Kegiatan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Custom CSS --}}
    <style>
        .form-control:read-only {
            background-color: #f8f9fa;
            cursor: default;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
        }

        .modal-header {
            border-bottom: none;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }

        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-label i {
            width: 20px;
        }

        .badge {
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
