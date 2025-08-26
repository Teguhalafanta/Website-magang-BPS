@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <strong>Daftar Kegiatan</strong>
            </div>
            <div class="card-body">
                {{-- Notifikasi sukses --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Form Tambah Kegiatan --}}
                <form action="{{ route('kegiatan.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control"
                                value="{{ old('nama_kegiatan') }}">
                            @error('nama_kegiatan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}">
                            @error('tanggal')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" class="form-control" value="{{ old('deskripsi') }}">
                            @error('deskripsi')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-1 align-self-end">
                            <button type="submit" class="btn btn-success w-100">Simpan</button>
                        </div>
                    </div>
                </form>

                {{-- Tabel Daftar Kegiatan --}}
                <table class="table table-bordered table-hover mt-4">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatans as $kegiatan)
                            <tr>
                                <td>{{ $kegiatan->nama_kegiatan }}</td>
                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d M Y') }}</td>
                                <td>{{ $kegiatan->deskripsi }}</td>
                                <td class="text-center">
                                    <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
                                        class="btn btn-sm btn-warning mb-1">Edit</a>
                                    <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data kegiatan belum ada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $kegiatans->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
