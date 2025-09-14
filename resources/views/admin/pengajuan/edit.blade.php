@extends('kerangka.master')

@section('content')
<div class="container">
    <h2>Edit Pengajuan Magang</h2>

    <form action="{{ route('admin.pengajuan.update', $pengajuan->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Kolom kiri --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pengajuan->nama) }}"
                        required>
                    @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki"
                            {{ old('jenis_kelamin', $pengajuan->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="Perempuan"
                            {{ old('jenis_kelamin', $pengajuan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control"
                        value="{{ old('tempat_lahir', $pengajuan->tempat_lahir) }}" required>
                    @error('tempat_lahir')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                        value="{{ old('tanggal_lahir', $pengajuan->tanggal_lahir) }}" required>
                    @error('tanggal_lahir')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $pengajuan->alamat) }}</textarea>
                    @error('alamat')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control"
                        value="{{ old('telepon', $pengajuan->telepon) }}">
                    @error('telepon')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Kolom kanan --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $pengajuan->email) }}" required>
                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">NIM / NISN</label>
                    <input type="text" name="nim_nisn" class="form-control"
                        value="{{ old('nim_nisn', $pengajuan->nim_nisn) }}" required>
                    @error('nim_nisn')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Asal Institusi</label>
                    <input type="text" name="asal_institusi" class="form-control"
                        value="{{ old('asal_institusi', $pengajuan->asal_institusi) }}" required>
                    @error('asal_institusi')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Fakultas</label>
                    <input type="text" name="fakultas" class="form-control"
                        value="{{ old('fakultas', $pengajuan->fakultas) }}">
                    @error('fakultas')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <input type="text" name="jurusan" class="form-control"
                        value="{{ old('jurusan', $pengajuan->jurusan) }}" required>
                    @error('jurusan')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Rencana Mulai</label>
                    <input type="date" name="rencana_mulai" class="form-control"
                        value="{{ old('rencana_mulai', $pengajuan->rencana_mulai) }}" required>
                    @error('rencana_mulai')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Rencana Selesai</label>
                    <input type="date" name="rencana_selesai" class="form-control"
                        value="{{ old('rencana_selesai', $pengajuan->rencana_selesai) }}" required>
                    @error('rencana_selesai')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Proposal (PDF/DOC)</label>
                    <input type="file" name="proposal" class="form-control" accept=".pdf,.doc,.docx">
                    @if ($pengajuan->proposal)
                    <small>File saat ini: <a href="{{ asset('storage/' . $pengajuan->proposal) }}"
                            target="_blank">Lihat</a></small>
                    @endif
                    @error('proposal')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Surat Pengajuan (PDF/DOC)</label>
                    <input type="file" name="surat_pengajuan" class="form-control" accept=".pdf,.doc,.docx">
                    @if ($pengajuan->surat_pengajuan)
                    <small>File saat ini: <a href="{{ asset('storage/' . $pengajuan->surat_pengajuan) }}"
                            target="_blank">Lihat</a></small>
                    @endif
                    @error('surat_pengajuan')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection