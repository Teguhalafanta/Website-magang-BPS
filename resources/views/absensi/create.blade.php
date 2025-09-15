@extends('kerangka.master')

@section('content')
<div class="container">
    <h3>Tambah Absensi</h3>

    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="">Pilih Status</option>
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Alpha">Alpha</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
