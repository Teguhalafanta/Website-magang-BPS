@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        Daftar Kegiatan
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('kegiatan.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan') }}">
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

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatans as $kegiatan)
                <tr>
                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                    <td>{{ $kegiatan->tanggal }}</td>
                    <td>{{ $kegiatan->deskripsi }}</td>
                    <td>
                        <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($kegiatans->count() == 0)
                <tr>
                    <td colspan="4" class="text-center">Data kegiatan belum ada.</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{ $kegiatans->links() }}
    </div>
</div>
@endsection
