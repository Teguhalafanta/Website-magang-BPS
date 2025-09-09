@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Form Pengajuan Magang</h2>

        <form action="{{ route('pelajar.pengajuan.store') }}" method="POST">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            {{-- Jenis Kelamin --}}
            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            {{-- Tempat Lahir --}}
            <div class="mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" required>
            </div>

            {{-- Tanggal Lahir --}}
            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" required>
                {{-- kalau mau date + time: type="datetime-local" --}}
            </div>


            {{-- Alamat --}}
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" required></textarea>
            </div>

            {{-- Telepon --}}
            <div class="mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text" name="telepon" class="form-control">
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            {{-- NIM / NISN --}}
            <div class="mb-3">
                <label class="form-label">NIM / NISN</label>
                <input type="text" name="nim_nisn" class="form-control" required>
            </div>

            {{-- Asal Institusi --}}
            <div class="mb-3">
                <label class="form-label">Asal Institusi</label>
                <input type="text" name="asal_institusi" class="form-control" required>
            </div>

            {{-- Fakultas (opsional) --}}
            <div class="mb-3">
                <label class="form-label">Fakultas</label>
                <input type="text" name="fakultas" class="form-control">
            </div>

            {{-- Jurusan --}}
            <div class="mb-3">
                <label class="form-label">Jurusan</label>
                <input type="text" name="jurusan" class="form-control" required>
            </div>

            {{-- Rencana Mulai --}}
            <div class="mb-3">
                <label class="form-label">Rencana Mulai</label>
                <input type="date" name="rencana_mulai" class="form-control" required>
            </div>

            {{-- Rencana Selesai --}}
            <div class="mb-3">
                <label class="form-label">Rencana Selesai</label>
                <input type="date" name="rencana_selesai" class="form-control" required>
            </div>

            {{-- Tombol Submit --}}
            <button type="submit" class="btn btn-primary">Ajukan Magang</button>
        </form>
    </div>
@endsection
