@extends('kerangka.master')

@section('content')
<div class="container">
    <h3>Edit Presensi</h3>
    <form action="{{ route('presensi.update', $presensi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $presensi->tanggal }}">
        </div>

        <div class="mb-3">
            <label>Waktu Datang</label>
            <input type="time" name="waktu_datang" class="form-control" value="{{ $presensi->waktu_datang }}">
        </div>

        <div class="mb-3">
            <label>Waktu Pulang</label>
            <input type="time" name="waktu_pulang" class="form-control" value="{{ $presensi->waktu_pulang }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Tepat Waktu" {{ $presensi->status == 'Tepat Waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                <option value="Terlambat" {{ $presensi->status == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
