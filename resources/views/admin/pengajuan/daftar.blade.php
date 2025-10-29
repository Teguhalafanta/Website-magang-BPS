@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Daftar Pengajuan Magang</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th style="width: 25%">Nama</th>
                                <th style="width: 30%">Asal Institusi</th>
                                <th class="text-center" style="width: 15%">Status</th>
                                <th class="text-center" style="width: 25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuans as $key => $p)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="fw-semibold">{{ $p->nama }}</td>
                                    <td>{{ $p->asal_institusi }}</td>
                                    <td class="text-center">
                                        @if ($p->status == 'diajukan')
                                            <span class="badge bg-primary rounded-pill">Diajukan</span>
                                        @elseif($p->status == 'disetujui')
                                            <span class="badge bg-success rounded-pill">Disetujui</span>
                                        @elseif($p->status == 'ditolak')
                                            <span class="badge bg-danger rounded-pill">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $p->id }}" title="Lihat Detail">
                                                <i class="bi bi-eye"></i> Detail
                                            </button>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $p->id }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.pengajuan.destroy', $p->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL DETAIL -->
                                <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-file-earmark-text me-2"></i>Detail Pengajuan -
                                                    {{ $p->nama }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3 mb-4">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Nama
                                                            Lengkap</label>
                                                        <p class="mb-0">{{ $p->nama }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Jenis
                                                            Kelamin</label>
                                                        <p class="mb-0">{{ $p->jenis_kelamin }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Tempat
                                                            Lahir</label>
                                                        <p class="mb-0">{{ $p->tempat_lahir }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Tanggal
                                                            Lahir</label>
                                                        <p class="mb-0">
                                                            {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('d/m/Y') : '-' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold text-muted small">Alamat</label>
                                                        <p class="mb-0">{{ $p->alamat }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">No.
                                                            Telepon</label>
                                                        <p class="mb-0">{{ $p->telepon }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Email</label>
                                                        <p class="mb-0">{{ $p->email }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">NIM /
                                                            NISN</label>
                                                        <p class="mb-0">{{ $p->nim_nisn }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Asal
                                                            Institusi</label>
                                                        <p class="mb-0">{{ $p->asal_institusi }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Fakultas</label>
                                                        <p class="mb-0">{{ $p->fakultas }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Jurusan</label>
                                                        <p class="mb-0">{{ $p->jurusan }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Rencana
                                                            Mulai</label>
                                                        <p class="mb-0">
                                                            {{ $p->rencana_mulai ? \Carbon\Carbon::parse($p->rencana_mulai)->format('d/m/Y') : '-' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Rencana
                                                            Selesai</label>
                                                        <p class="mb-0">
                                                            {{ $p->rencana_selesai ? \Carbon\Carbon::parse($p->rencana_selesai)->format('d/m/Y') : '-' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Proposal</label>
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
                                                        <label class="form-label fw-bold text-muted small">Surat
                                                            Pengajuan</label>
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
                                                        <label class="form-label fw-bold text-muted small">Status</label>
                                                        <p class="mb-0">
                                                            @if ($p->status == 'diajukan')
                                                                <span class="badge bg-primary rounded-pill">Diajukan</span>
                                                            @elseif($p->status == 'disetujui')
                                                                <span
                                                                    class="badge bg-success rounded-pill">Disetujui</span>
                                                            @elseif($p->status == 'ditolak')
                                                                <span class="badge bg-danger rounded-pill">Ditolak</span>
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
                                                        <label class="form-label fw-bold">Status <span
                                                                class="text-danger">*</span></label>
                                                        <select name="status" class="form-select status-select" required>
                                                            <option value="" disabled selected>-- Pilih Status --
                                                            </option>
                                                            <option value="disetujui"
                                                                {{ $p->status == 'disetujui' ? 'selected' : '' }}>✅
                                                                Disetujui</option>
                                                            <option value="ditolak"
                                                                {{ $p->status == 'ditolak' ? 'selected' : '' }}>❌ Ditolak
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 upload-surat" style="display: none;">
                                                        <label class="form-label fw-bold">Upload Surat Penerimaan (PDF)
                                                            <span class="text-danger">*</span></label>
                                                        <input type="file" name="surat_penerimaan"
                                                            class="form-control" accept="application/pdf">
                                                        <small class="form-text text-muted">Upload surat penerimaan untuk
                                                            pengajuan yang disetujui</small>
                                                    </div>
                                                    <div class="mb-3 alasan-field">
                                                        <label class="form-label fw-bold">Alasan (jika ditolak)</label>
                                                        <textarea name="alasan" class="form-control" rows="3">{{ $p->alasan }}</textarea>
                                                    </div>
                                                    <div class="d-grid gap-2">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL EDIT -->
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
                                            <form action="{{ route('admin.pengajuan.update', $p->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Nama Lengkap <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="nama" class="form-control"
                                                                value="{{ $p->nama }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Jenis Kelamin <span
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
                                                            <label class="form-label fw-bold">Tempat Lahir</label>
                                                            <input type="text" name="tempat_lahir"
                                                                class="form-control" value="{{ $p->tempat_lahir }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Tanggal Lahir</label>
                                                            <input type="date" name="tanggal_lahir"
                                                                class="form-control" value="{{ $p->tanggal_lahir }}">
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label fw-bold">Alamat</label>
                                                            <textarea name="alamat" class="form-control" rows="2">{{ $p->alamat }}</textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">No. Telepon</label>
                                                            <input type="text" name="telepon" class="form-control"
                                                                value="{{ $p->telepon }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Email</label>
                                                            <input type="email" name="email" class="form-control"
                                                                value="{{ $p->email }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">NIM / NISN</label>
                                                            <input type="text" name="nim_nisn" class="form-control"
                                                                value="{{ $p->nim_nisn }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Asal Institusi</label>
                                                            <input type="text" name="asal_institusi"
                                                                class="form-control" value="{{ $p->asal_institusi }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Fakultas</label>
                                                            <input type="text" name="fakultas" class="form-control"
                                                                value="{{ $p->fakultas }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Jurusan</label>
                                                            <input type="text" name="jurusan" class="form-control"
                                                                value="{{ $p->jurusan }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Rencana Mulai</label>
                                                            <input type="date" name="rencana_mulai"
                                                                class="form-control" value="{{ $p->rencana_mulai }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Rencana Selesai</label>
                                                            <input type="date" name="rencana_selesai"
                                                                class="form-control" value="{{ $p->rencana_selesai }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Upload Proposal
                                                                (PDF/DOC)</label>
                                                            <input type="file" name="proposal" class="form-control"
                                                                accept=".pdf,.doc,.docx">
                                                            @if ($p->proposal)
                                                                <small class="form-text text-muted">File saat ini:
                                                                    {{ basename($p->proposal) }}</small>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Upload Surat Pengajuan
                                                                (PDF/DOC)</label>
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
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">Belum ada pengajuan magang</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
