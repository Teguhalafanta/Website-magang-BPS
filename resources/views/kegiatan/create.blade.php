@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Kegiatan</h2>

    <form action="{{ route('kegiatan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="4" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
