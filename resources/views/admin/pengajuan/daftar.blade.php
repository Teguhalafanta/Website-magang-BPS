@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Pengajuan Magang</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Asal Institusi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $key => $p)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->asal_institusi }}</td>
                        <td>
                            @if ($p->status == 'diajukan')
                                <span class="badge bg-primary">Diajukan</span>
                            @elseif($p->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($p->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <!-- Tombol Detail -->
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#detailModal{{ $p->id }}" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>

                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $p->id }}" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.pengajuan.destroy', $p->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- ================= MODAL DETAIL ================= -->
                    <div class="modal fade custom-modal" id="detailModal{{ $p->id }}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header gradient-header text-light">
                                    <h5 class="modal-title fw-semibold">📄 Detail Pengajuan – {{ $p->nama }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-6"><strong>Nama Lengkap:</strong> {{ $p->nama }}</div>
                                        <div class="col-md-6"><strong>Jenis Kelamin:</strong> {{ $p->jenis_kelamin }}</div>
                                        <div class="col-md-6"><strong>Tempat Lahir:</strong> {{ $p->tempat_lahir }}</div>
                                        <div class="col-md-6"><strong>Tanggal Lahir:</strong>
                                            {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('d/m/Y') : '-' }}
                                        </div>
                                        <div class="col-12"><strong>Alamat:</strong> {{ $p->alamat }}</div>
                                        <div class="col-md-6"><strong>No. Telepon:</strong> {{ $p->telepon }}</div>
                                        <div class="col-md-6"><strong>Email:</strong> {{ $p->email }}</div>
                                        <div class="col-md-6"><strong>NIM / NISN:</strong> {{ $p->nim_nisn }}</div>
                                        <div class="col-md-6"><strong>Asal Institusi:</strong> {{ $p->asal_institusi }}
                                        </div>
                                        <div class="col-md-6"><strong>Fakultas:</strong> {{ $p->fakultas }}</div>
                                        <div class="col-md-6"><strong>Jurusan:</strong> {{ $p->jurusan }}</div>
                                        <div class="col-md-6"><strong>Rencana Mulai:</strong>
                                            {{ $p->rencana_mulai ? \Carbon\Carbon::parse($p->rencana_mulai)->format('d/m/Y') : '-' }}
                                        </div>
                                        <div class="col-md-6"><strong>Rencana Selesai:</strong>
                                            {{ $p->rencana_selesai ? \Carbon\Carbon::parse($p->rencana_selesai)->format('d/m/Y') : '-' }}
                                        </div>
                                        <div class="col-md-6"><strong>Proposal:</strong>
                                            @if ($p->proposal)
                                                <a href="{{ asset('storage/' . $p->proposal) }}" target="_blank"
                                                    class="btn btn-sm btn-secondary">Lihat Proposal</a>
                                            @else
                                                <span class="text-muted">Belum ada</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6"><strong>Surat Pengajuan:</strong>
                                            @if ($p->surat_pengajuan)
                                                <a href="{{ asset('storage/' . $p->surat_pengajuan) }}" target="_blank"
                                                    class="btn btn-sm btn-secondary">Lihat Surat</a>
                                            @else
                                                <span class="text-muted">Belum ada</span>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <strong>Status:</strong>
                                            @if ($p->status == 'diajukan')
                                                <span class="badge bg-primary">Diajukan</span>
                                            @elseif($p->status == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($p->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span><br>
                                                <small><strong>Alasan:</strong> {{ $p->alasan }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <form action="{{ route('admin.pengajuan.updateStatus', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-2">
                                            <label class="form-label fw-bold text-warning">
                                                Status <span class="text-danger">*</span>
                                            </label>
                                            <select name="status" class="form-select status-select" required>
                                                <option value="" disabled selected>-- Pilih Status --</option>
                                                <option value="disetujui"
                                                    {{ $p->status == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                                <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>❌
                                                    Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Alasan (jika ditolak)</label>
                                            <textarea name="alasan" class="form-control">{{ $p->alasan }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan
                                            Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= MODAL EDIT ================= -->
                    <div class="modal fade custom-modal" id="editModal{{ $p->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header gradient-header text-light">
                                    <h5 class="modal-title fw-semibold">✏️ Edit Pengajuan – {{ $p->nama }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.pengajuan.update', $p->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama" class="form-control"
                                                value="{{ $p->nama }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" class="form-select" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="Laki-laki"
                                                    {{ $p->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                                </option>
                                                <option value="Perempuan"
                                                    {{ $p->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" class="form-control"
                                                value="{{ $p->tempat_lahir }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" class="form-control"
                                                value="{{ $p->tanggal_lahir }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="alamat" class="form-control">{{ $p->alamat }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">No. Telepon</label>
                                            <input type="text" name="telepon" class="form-control"
                                                value="{{ $p->telepon }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $p->email }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">NIM / NISN</label>
                                            <input type="text" name="nim_nisn" class="form-control"
                                                value="{{ $p->nim_nisn }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Asal Institusi</label>
                                            <input type="text" name="asal_institusi" class="form-control"
                                                value="{{ $p->asal_institusi }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Fakultas</label>
                                            <input type="text" name="fakultas" class="form-control"
                                                value="{{ $p->fakultas }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Jurusan</label>
                                            <input type="text" name="jurusan" class="form-control"
                                                value="{{ $p->jurusan }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Rencana Mulai</label>
                                            <input type="date" name="rencana_mulai" class="form-control"
                                                value="{{ $p->rencana_mulai }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Rencana Selesai</label>
                                            <input type="date" name="rencana_selesai" class="form-control"
                                                value="{{ $p->rencana_selesai }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Proposal (PDF/DOC)</label>
                                            <input type="file" name="proposal" class="form-control"
                                                accept=".pdf,.doc,.docx">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Surat Pengajuan (PDF/DOC)</label>
                                            <input type="file" name="surat_pengajuan" class="form-control"
                                                accept=".pdf,.doc,.docx">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success rounded-pill px-4">Simpan
                                            Perubahan</button>
                                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                                            data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pengajuan magang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ======= STYLE TAMBAHAN UNTUK MODE GELAP & TERANG ======= --}}
    <style>
        .status-select {
            border: 2px solid #f6c23e;
            /* Kuning-oranye untuk menonjolkan */
            border-radius: .5rem;
            background-color: var(--bs-body-bg);
            background-image:
                linear-gradient(45deg, transparent 50%, #f6c23e 50%),
                linear-gradient(135deg, #f6c23e 50%, transparent 50%),
                linear-gradient(to right, #f6c23e, #f6c23e);
            background-position:
                calc(100% - 20px) calc(1em + 2px),
                calc(100% - 15px) calc(1em + 2px),
                calc(100% - 2.5em) 0.5em;
            background-size:
                5px 5px,
                5px 5px,
                1px 1.5em;
            background-repeat: no-repeat;
            padding-right: 2.5rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .status-select:focus {
            border-color: #e74a3b;
            box-shadow: 0 0 0 .25rem rgba(231, 74, 59, 0.25);
        }

        /* Mode Gelap */
        @media (prefers-color-scheme: dark) {
            .status-select {
                background-color: #3a3a3a;
                border-color: #f6c23e;
                color: #f1f1f1;
            }
        }


        .gradient-header {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .custom-modal .modal-content {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            transition: all 0.3s ease-in-out;
        }

        .custom-modal .form-label {
            font-weight: 500;
        }

        .custom-modal .form-control,
        .custom-modal .form-select {
            border-radius: .5rem;
            transition: border-color .3s ease, box-shadow .3s ease;
        }

        .custom-modal .form-control:focus,
        .custom-modal .form-select:focus {
            border-color: #1cc88a;
            box-shadow: 0 0 0 .2rem rgba(28, 200, 138, 0.25);
        }

        /* Efek hover */
        .custom-modal .modal-content:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        /* Mode gelap otomatis */
        @media (prefers-color-scheme: dark) {
            .custom-modal .modal-content {
                background-color: #2b2b2b;
                color: #f1f1f1;
            }

            .custom-modal .form-label {
                color: #ddd;
            }

            .custom-modal .form-control,
            .custom-modal .form-select {
                background-color: #3a3a3a;
                color: #f1f1f1;
                border-color: #555;
            }

            .table-bordered,
            .table-striped>tbody>tr:nth-of-type(odd) {
                color: #f1f1f1;
            }
        }
    </style>
@endsection
