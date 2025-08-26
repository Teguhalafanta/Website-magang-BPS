@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Absensi Mahasiswa</h2>

    {{-- Form Absensi --}}
    <div class="card mb-4 shadow">
        <div class="card-header bg-success text-white">Form Absensi</div>
        <div class="card-body">
            <form action="{{ route('absensi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Mahasiswa</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>NIM</label>
                    <input type="text" name="nim" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Status Kehadiran</label>
                    <select name="status" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpha">Alpha</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Simpan Absensi</button>
            </form>
        </div>
    </div>

    {{-- Tabel Absensi --}}
    <div class="card shadow">
        <div class="card-header bg-primary text-white">Daftar Absensi</div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absen)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $absen->mahasiswa->nama ?? '-' }}</td>
                            <td>{{ $absen->mahasiswa->nim ?? '-' }}</td>
                            <td>{{ ucfirst($absen->status) }}</td>
                            <td>{{ $absen->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data absensi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Cek absen hari ini --}}
    @if (!$absenHariIni)
        <div class="alert alert-warning mt-3">
            Anda <b>belum absen hari ini</b>. Silakan lakukan absen terlebih dahulu.
        </div>
        <button class="btn btn-secondary mb-3" disabled>Tambah Kegiatan</button>
    @else
        <a href="{{ route('kegiatan.create') }}" class="btn btn-primary mt-3">Tambah Kegiatan</a>
    @endif
</div>
@endsection
