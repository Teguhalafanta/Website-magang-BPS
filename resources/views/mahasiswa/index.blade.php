@extends('kerangka.master')

@section('content')
<section>
    <h3>Tambah Data Mahasiswa</h3>
    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label>Nama Mahasiswa</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>NIM</label>
            <input type="text" name="nim" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>No Telpon</label>
            <input type="text" name="telpon" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mb-5">Simpan Mahasiswa</button>
    </form>

    <h3>Form Absensi</h3>
    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label>Nama Mahasiswa</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>NIM</label>
            <input type="text" name="nim" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Status Kehadiran</label>
            <select name="status" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="hadir">Hadir</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
                <option value="alpha">Alpha</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success mb-5">Simpan Absensi</button>
    </form>

    <h3>Daftar Absensi</h3>
    <table class="table table-bordered">
        <thead>
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
                <td>{{ $absen->mahasiswa->nama ?? $absen->nama_mahasiswa ?? '-' }}</td>
                <td>{{ $absen->mahasiswa->nim ?? $absen->nim ?? '-' }}</td>
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

    @if (!$absenHariIni)
    <div class="alert alert-warning">
        Anda <b>belum absen hari ini</b>. Silakan lakukan absen terlebih dahulu.
    </div>
    <button class="btn btn-secondary mb-3" disabled>Tambah Kegiatan</button>
    @else
    <a href="{{ route('kegiatan.create') }}" class="btn btn-primary mb-3">Tambah Kegiatan</a>
    @endif

    <h3>Data Mahasiswa</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>No Telpon</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mahasiswas as $mahasiswa)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mahasiswa->nama }}</td>
                <td>{{ $mahasiswa->nim }}</td>
                <td>{{ $mahasiswa->telpon }}</td>
                <td>{{ $mahasiswa->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
