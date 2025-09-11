@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2>Edit Pengajuan Magang</h2>

        <form action="{{ route('admin.pengajuan.update', $pengajuan->id_pelajar) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $pengajuan->nama }}" required>
            </div>

            <div class="mb-3">
                <label>Asal Institusi</label>
                <input type="text" name="asal_institusi" class="form-control" value="{{ $pengajuan->asal_institusi }}"
                    required>
            </div>

            <div class="mb-3">
                <label>Jurusan</label>
                <input type="text" name="jurusan" class="form-control" value="{{ $pengajuan->jurusan }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $pengajuan->email }}" required>
            </div>

            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ $pengajuan->telepon }}">
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
