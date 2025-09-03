@extends('kerangka.master')

@section('content')
<div class="card">
    <div class="card-header bg-warning text-dark">
        Edit Absensi
    </div>
    <div class="card-body">
        <form action="{{ route('absensi.update', $absen->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Pelajar</label>
                <select name="pelajar_id" class="form-select">
                    @foreach($pelajars as $mhs)
                        <option value="{{ $mhs->id_pelajar }}" {{ $absen->pelajar_id == $mhs->id_pelajar ? 'selected' : '' }}>
                            {{ $mhs->nama }} ({{ $mhs->nim }})
                        </option>
                    @endforeach
                </select>
                @error('pelajar_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $absen->tanggal }}">
                @error('tanggal')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    @foreach(['Hadir','Izin','Sakit','Alfa'] as $status)
                        <option value="{{ $status }}" {{ $absen->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @error('status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="form-control" value="{{ $absen->keterangan }}">
                @error('keterangan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
