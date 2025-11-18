@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-briefcase-fill me-2"></i>Daftar Pengajuan Magang
            </h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="row g-3 mb-2">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Diajukan</p>
                                <h4 class="mb-0 fw-bold">{{ $pengajuans->where('status', 'diajukan')->count() }}</h4>
                            </div>
                            <i class="bi bi-clock-history fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Disetujui</p>
                                <h4 class="mb-0 fw-bold">{{ $pengajuans->where('status', 'disetujui')->count() }}</h4>
                            </div>
                            <i class="bi bi-check-circle fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Ditolak</p>
                                <h4 class="mb-0 fw-bold">{{ $pengajuans->where('status', 'ditolak')->count() }}</h4>
                            </div>
                            <i class="bi bi-x-circle fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 text-muted">
                    Total: <span class="badge bg-primary">{{ $pengajuans->count() }}</span> pengajuan
                </h6>
            </div>

            <div class="card-body p-0">
                @if ($pengajuans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th class="ps-3" style="width: 4%;">No</th>
                                    <th style="width: 20%;">Nama</th>
                                    <th style="width: 23%;">Asal Institusi</th>
                                    <th style="width: 18%;">Tanggal Pengajuan</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 13%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengajuans as $key => $p)
                                    <tr>
                                        <td class="text-center ps-3">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div class="fw-semibold text-truncate" style="font-size: 0.875rem;">
                                                        {{ $p->nama }}
                                                    </div>
                                                    <small class="text-muted text-truncate d-block">
                                                        {{ $p->email }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div>{{ $p->asal_institusi }}</div>
                                            <small class="text-muted">{{ $p->jurusan }}</small>
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}
                                        </td>

                                        <td class="text-center">
                                            @if ($p->status == 'diajukan')
                                                <span class="badge bg-primary">Diajukan</span>
                                            @elseif($p->status == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($p->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $p->id }}" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $p->id }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('admin.pengajuan.destroy', $p->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- MODAL DETAIL --}}
                                    <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-info-circle me-2"></i>Detail Pengajuan -
                                                        {{ $p->nama }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3 mb-4">
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Nama Lengkap</label>
                                                            <p class="fw-semibold mb-0">{{ $p->nama }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Jenis Kelamin</label>
                                                            <p class="mb-0">{{ $p->jenis_kelamin }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Tempat Lahir</label>
                                                            <p class="mb-0">{{ $p->tempat_lahir }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Tanggal Lahir</label>
                                                            <p class="mb-0">
                                                                {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('d/m/Y') : '-' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="text-muted small mb-1">Alamat</label>
                                                            <p class="mb-0">{{ $p->alamat }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">No. Telepon</label>
                                                            <p class="mb-0">{{ $p->telepon }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Email</label>
                                                            <p class="mb-0">{{ $p->email }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">NIM / NISN</label>
                                                            <p class="mb-0">{{ $p->nim_nisn }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Asal Institusi</label>
                                                            <p class="mb-0">{{ $p->asal_institusi }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Fakultas</label>
                                                            <p class="mb-0">{{ $p->fakultas }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Jurusan</label>
                                                            <p class="mb-0">{{ $p->jurusan }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Rencana Mulai</label>
                                                            <p class="mb-0">
                                                                {{ $p->rencana_mulai ? \Carbon\Carbon::parse($p->rencana_mulai)->format('d/m/Y') : '-' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Rencana Selesai</label>
                                                            <p class="mb-0">
                                                                {{ $p->rencana_selesai ? \Carbon\Carbon::parse($p->rencana_selesai)->format('d/m/Y') : '-' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Proposal</label>
                                                            <p class="mb-0">
                                                                @if ($p->proposal)
                                                                    <a href="{{ asset('storage/' . $p->proposal) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-secondary">
                                                                        <i class="bi bi-file-pdf"></i> Lihat Proposal
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">Belum ada</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small mb-1">Surat Pengajuan</label>
                                                            <p class="mb-0">
                                                                @if ($p->surat_pengajuan)
                                                                    <a href="{{ asset('storage/' . $p->surat_pengajuan) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-secondary">
                                                                        <i class="bi bi-file-pdf"></i> Lihat Surat
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">Belum ada</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="text-muted small mb-1">Status</label>
                                                            <p class="mb-0">
                                                                @if ($p->status == 'diajukan')
                                                                    <span class="badge bg-primary">Diajukan</span>
                                                                @elseif($p->status == 'disetujui')
                                                                    <span class="badge bg-success">Disetujui</span>
                                                                @elseif($p->status == 'ditolak')
                                                                    <span class="badge bg-danger">Ditolak</span>
                                                                    @if ($p->alasan)
                                                                        <br><small
                                                                            class="text-muted mt-1 d-block"><strong>Alasan:</strong>
                                                                            {{ $p->alasan }}</small>
                                                                    @endif
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <h6 class="fw-bold mb-3">Update Status</h6>
                                                    <form action="{{ route('admin.pengajuan.updateStatus', $p->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Status <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="status" class="form-select status-select"
                                                                required>
                                                                <option value="" disabled selected>-- Pilih Status --
                                                                </option>
                                                                <option value="disetujui"
                                                                    {{ $p->status == 'disetujui' ? 'selected' : '' }}>✅
                                                                    Disetujui</option>
                                                                <option value="ditolak"
                                                                    {{ $p->status == 'ditolak' ? 'selected' : '' }}>❌
                                                                    Ditolak</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3 upload-surat" style="display: none;">
                                                            <label class="form-label fw-semibold">Upload Surat Penerimaan
                                                                (PDF)
                                                                <span class="text-danger">*</span></label>
                                                            <input type="file" name="surat_penerimaan"
                                                                class="form-control" accept="application/pdf">
                                                            <small class="form-text text-muted">Upload surat penerimaan
                                                                untuk pengajuan yang disetujui</small>
                                                        </div>
                                                        <div class="mb-3 alasan-field">
                                                            <label class="form-label fw-semibold">Alasan (jika
                                                                ditolak)</label>
                                                            <textarea name="alasan" class="form-control" rows="3">{{ $p->alasan }}</textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- MODAL EDIT --}}
                                    <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-pencil-square me-2"></i>Edit Pengajuan -
                                                        {{ $p->nama }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.pengajuan.update', $p->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Nama Lengkap <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="nama" class="form-control"
                                                                    value="{{ $p->nama }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Jenis Kelamin <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="jenis_kelamin" class="form-select" required>
                                                                    <option value="">-- Pilih --</option>
                                                                    <option value="Laki-laki"
                                                                        {{ $p->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                                                        Laki-laki</option>
                                                                    <option value="Perempuan"
                                                                        {{ $p->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                                                        Perempuan</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Tempat Lahir</label>
                                                                <input type="text" name="tempat_lahir"
                                                                    class="form-control" value="{{ $p->tempat_lahir }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Tanggal Lahir</label>
                                                                <input type="date" name="tanggal_lahir"
                                                                    class="form-control" value="{{ $p->tanggal_lahir }}">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label fw-semibold">Alamat</label>
                                                                <textarea name="alamat" class="form-control" rows="2">{{ $p->alamat }}</textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">No. Telepon</label>
                                                                <input type="text" name="telepon" class="form-control"
                                                                    value="{{ $p->telepon }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Email</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    value="{{ $p->email }}" readonly>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">NIM / NISN</label>
                                                                <input type="text" name="nim_nisn"
                                                                    class="form-control" value="{{ $p->nim_nisn }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Asal
                                                                    Institusi</label>
                                                                <input type="text" name="asal_institusi"
                                                                    class="form-control"
                                                                    value="{{ $p->asal_institusi }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Fakultas</label>
                                                                <input type="text" name="fakultas"
                                                                    class="form-control" value="{{ $p->fakultas }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Jurusan</label>
                                                                <input type="text" name="jurusan" class="form-control"
                                                                    value="{{ $p->jurusan }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Rencana Mulai</label>
                                                                <input type="date" name="rencana_mulai"
                                                                    class="form-control" value="{{ $p->rencana_mulai }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Rencana
                                                                    Selesai</label>
                                                                <input type="date" name="rencana_selesai"
                                                                    class="form-control"
                                                                    value="{{ $p->rencana_selesai }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Upload Proposal
                                                                    (PDF/DOC)</label>
                                                                <input type="file" name="proposal"
                                                                    class="form-control" accept=".pdf,.doc,.docx">
                                                                @if ($p->proposal)
                                                                    <small class="form-text text-muted">File saat ini:
                                                                        {{ basename($p->proposal) }}</small>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Upload Surat
                                                                    Pengajuan (PDF/DOC)</label>
                                                                <input type="file" name="surat_pengajuan"
                                                                    class="form-control" accept=".pdf,.doc,.docx">
                                                                @if ($p->surat_pengajuan)
                                                                    <small class="form-text text-muted">File saat ini:
                                                                        {{ basename($p->surat_pengajuan) }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-warning">
                                                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada pengajuan magang</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modals = document.querySelectorAll(".modal");

            modals.forEach(modal => {
                modal.addEventListener("shown.bs.modal", function() {
                    const statusSelect = modal.querySelector(".status-select");
                    const uploadSurat = modal.querySelector(".upload-surat");
                    const alasanField = modal.querySelector(".alasan-field");

                    if (!statusSelect) return;

                    function toggleFields() {
                        if (statusSelect.value === "disetujui") {
                            if (uploadSurat) {
                                uploadSurat.style.display = "block";
                                uploadSurat.querySelector("input").required = true;
                            }
                            if (alasanField) {
                                alasanField.style.display = "none";
                                alasanField.querySelector("textarea").value = "";
                            }
                        } else if (statusSelect.value === "ditolak") {
                            if (uploadSurat) {
                                uploadSurat.style.display = "none";
                                uploadSurat.querySelector("input").required = false;
                            }
                            if (alasanField) {
                                alasanField.style.display = "block";
                            }
                        } else {
                            if (uploadSurat) uploadSurat.style.display = "none";
                            if (alasanField) alasanField.style.display = "block";
                        }
                    }

                    toggleFields();
                    statusSelect.addEventListener("change", toggleFields);
                });
            });
        });
    </script>
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
