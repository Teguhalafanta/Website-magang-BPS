@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2>Edit Kegiatan</h2>

        <form action="{{ route('pelajar.kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control"
                       value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required>
                @error('nama_kegiatan')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control"
                       value="{{ old('tanggal', $kegiatan->tanggal->format('Y-m-d')) }}" required>
                @error('tanggal')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="Belum" {{ old('status', $kegiatan->status_penyelesaian) == 'Belum' ? 'selected' : '' }}>Belum</option>
                    <option value="Proses" {{ old('status', $kegiatan->status_penyelesaian) == 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Selesai" {{ old('status', $kegiatan->status_penyelesaian) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input file bukti (opsional) --}}
            <div class="mb-3">
                <label for="bukti_dukung" class="form-label">Bukti Dukung</label>
                <input type="file" name="bukti_dukung" id="bukti_dukung" class="form-control">
                @if ($kegiatan->bukti_dukung)
                    <p class="mt-2">File saat ini: <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}" target="_blank">Lihat</a></p>
                @endif
                @error('bukti_dukung')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('pelajar.kegiatan.harian') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
