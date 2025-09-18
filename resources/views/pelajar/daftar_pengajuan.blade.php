@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Pengajuan Magang</h2>

        <div class="mb-3">
            <a href="{{ route('pelajar.pengajuan.create') }}" class="btn btn-primary">
                + Tambah Pengajuan
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Asal Institusi</th>
                    <th>Jurusan</th>
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
                        <td>{{ $p->jurusan }}</td>
                        <td>
                            @if ($p->status == 'diajukan')
                                <span class="badge bg-warning">Diajukan</span>
                            @elseif($p->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($p->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>

                        <!-- aksi -->
                        <td>
                            <!-- Tombol detail -->
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#detailModal{{ $p->id }}">
                                Detail
                            </button>

                            <!-- Tombol edit -->
                            <button class="btn btn-warning btn-sm"
                                @if ($p->status == 'disetujui') disabled @else data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}" @endif>
                                Edit
                            </button>

                            <!-- Tombol hapus -->
                            <form action="{{ route('pelajar.pengajuan.destroy', $p->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    @if ($p->status == 'disetujui') disabled @endif>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Detail -->
                    <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1"
                        aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="detailModalLabel{{ $p->id }}">
                                        Detail Pengajuan - {{ $p->nama }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Nama Lengkap:</strong>
                                                    {{ $p->nama }}</li>
                                                <li class="list-group-item"><strong>Jenis Kelamin:</strong>
                                                    {{ $p->jenis_kelamin }}</li>
                                                <li class="list-group-item"><strong>Tempat Lahir:</strong>
                                                    {{ $p->tempat_lahir }}</li>
                                                <li class="list-group-item">
                                                    <strong>Tanggal Lahir:</strong>
                                                    {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                                </li>
                                                <li class="list-group-item"><strong>Alamat:</strong> {{ $p->alamat }}
                                                </li>
                                                <li class="list-group-item"><strong>No. Telepon:</strong>
                                                    {{ $p->telepon }}</li>
                                                <li class="list-group-item"><strong>Email:</strong> {{ $p->email }}
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>NIM / NISN:</strong>
                                                    {{ $p->nim_nisn }}</li>
                                                <li class="list-group-item"><strong>Asal Institusi:</strong>
                                                    {{ $p->asal_institusi }}</li>
                                                <li class="list-group-item"><strong>Fakultas:</strong> {{ $p->fakultas }}
                                                </li>
                                                <li class="list-group-item"><strong>Jurusan:</strong> {{ $p->jurusan }}
                                                </li>
                                                <li class="list-group-item"><strong>Rencana Mulai:</strong>
                                                    {{ $p->rencana_mulai }}</li>
                                                <li class="list-group-item"><strong>Rencana Selesai:</strong>
                                                    {{ $p->rencana_selesai }}</li>
                                                <li class="list-group-item">
                                                    <strong>Proposal:</strong>
                                                    @if ($p->proposal)
                                                        <a href="{{ asset('storage/' . $p->proposal) }}"
                                                            target="_blank">Lihat Proposal</a>
                                                    @else
                                                        -
                                                    @endif
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Surat Pengajuan:</strong>
                                                    @if ($p->surat_pengajuan)
                                                        <a href="{{ asset('storage/' . $p->surat_pengajuan) }}"
                                                            target="_blank">Lihat Surat</a>
                                                    @else
                                                        -
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <strong>Status:</strong>
                                        @if ($p->status == 'diajukan')
                                            <span class="badge bg-warning">Diajukan</span>
                                        @elseif($p->status == 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($p->status == 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                            <br>
                                            <small><strong>Alasan:</strong> {{ $p->alasan }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Edit -->
                    @if ($p->status != 'disetujui')
                        <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1"
                            aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('pelajar.pengajuan.update', $p->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Edit Pengajuan -
                                                {{ $p->nama }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama" class="form-control"
                                                    value="{{ $p->nama }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select name="jenis_kelamin" class="form-control" required>
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
                                                <input type="email" class="form-control" value="{{ $p->email }}"
                                                    readonly>
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
                                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada pengajuan magang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
