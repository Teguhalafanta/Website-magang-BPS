@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <h2 class="mb-4">Data Mahasiswa</h2>

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
                <div class="row mb-3">
                    <div class="col">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>NIM</label>
                        <input type="text" name="nim" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>
                </div>
                <button class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Tabel Mahasiswa --}}
    <div class="card shadow">
        <div class="card-header bg-success text-white">Daftar Mahasiswa</div>
        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
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
                    @forelse($mahasiswas as $mhs)
                        <tr>
                            <td>{{ $mhs->nama }}</td>
                            <td>{{ $mhs->nim }}</td>
                            <td>{{ $mhs->telepon }}</td>
                            <td>{{ $mhs->alamat }}</td>
                            <td>
                                <a href="{{ route('mahasiswa.edit', $mhs->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus?')"
                                        class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <a href="{{ route('mahasiswa.index') }}" class="text-decoration-none">
                            <div class="card bg-primary text-white shadow">
                                <div class="card-body">
                                    <h5>Total Mahasiswa</h5>
                                    <h2>{{ $totalMahasiswa }}</h2>
                                </div>
                            </div>
                        </a>
                        <tr>
                            <td colspan="5">Tidak ada data mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
