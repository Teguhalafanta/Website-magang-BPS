@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Navigasi --}}
    <div class="mb-4 text-center">
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-primary">Mahasiswa</a>
        <a href="{{ route('absensi.index') }}" class="btn btn-success">Absensi</a>
        <a href="{{ route('kegiatan.index') }}" class="btn btn-info">Kegiatan</a>
    </div>

    <h2 class="text-center mb-4">Data Mahasiswa</h2>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Tambah Mahasiswa --}}
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">Tambah Mahasiswa</div>
        <div class="card-body">
            <form action="{{ route('mahasiswa.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>NIM</label>
                    <input type="text" name="nim" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>
                <button class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Tabel Mahasiswa --}}
    <div class="card shadow">
        <div class="card-header bg-success text-white">Daftar Mahasiswa</div>
        <div class="card-body">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mahasiswas as $mhs)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mhs->nama }}</td>
                            <td>{{ $mhs->nim }}</td>
                            <td>{{ $mhs->telepon }}</td>
                            <td>{{ $mhs->alamat }}</td>
                            <td>
                                <a href="{{ route('mahasiswa.edit', $mhs->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('mahasiswa.destroy', $mhs->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus?')"
                                        class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
