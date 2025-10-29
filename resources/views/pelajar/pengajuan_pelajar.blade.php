@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Form Pengajuan Magang</h2>

        <form action="{{ route('pelajar.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Kolom kiri --}}
                <div class="col-md-6">
                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}"
                            required>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}"
                            required>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Telepon --}}
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}">
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="col-md-6">
                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}"
                            readonly>
                    </div>

                    {{-- NIM / NISN --}}
                    <div class="mb-3">
                        <label class="form-label">NIM / NISN</label>
                        <input type="text" name="nim_nisn" class="form-control" value="{{ old('nim_nisn') }}" required>
                    </div>

                    {{-- Asal Institusi --}}
                    <div class="mb-3">
                        <label class="form-label">Asal Institusi</label>
                        <input type="text" name="asal_institusi" class="form-control"
                            value="{{ old('asal_institusi') }}" required>
                    </div>

                    {{-- Fakultas --}}
                    <div class="mb-3">
                        <label class="form-label">Fakultas</label>
                        <input type="text" name="fakultas" class="form-control" value="{{ old('fakultas') }}">
                    </div>

                    {{-- Jurusan --}}
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan') }}" required>
                    </div>

                    {{-- Rencana Mulai --}}
                    <div class="mb-3">
                        <label class="form-label">Rencana Mulai</label>
                        <input type="date" name="rencana_mulai" class   ="form-control"
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ old('rencana_mulai') }}"
                            required>
                    </div>

                    {{-- Rencana Selesai --}}
                    <div class="mb-3">
                        <label class="form-label">Rencana Selesai</label>
                        <input type="date" name="rencana_selesai" class="form-control"
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ old('rencana_selesai') }}"
                            required>
                    </div>


                    {{-- Upload Proposal --}}
                    <div class="mb-3">
                        <label class="form-label">Upload Proposal (PDF/DOC)</label>
                        <input type="file" name="proposal" class="form-control" accept=".pdf,.doc,.docx" required>
                    </div>

                    {{-- Upload Surat Pengajuan --}}
                    <div class="mb-3">
                        <label class="form-label">Upload Surat Pengajuan / CV (PDF/DOC)</label>
                        <input type="file" name="surat_pengajuan" class="form-control" accept=".pdf,.doc,.docx">
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5">Ajukan Magang</button>
            </div>

        </form>
    </div>
@endsection
