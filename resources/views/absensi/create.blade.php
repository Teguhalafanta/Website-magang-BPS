@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Form Absensi</h2>

    {{-- Tampilkan pesan error umum --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tampilkan pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf

        {{-- Input NIM Mahasiswa --}}
        <div class="mb-3">
            <label for="nim" class="form-label">NIM Mahasiswa</label>
            <input type="text" name="nim" id="nim" class="form-control" value="{{ old('nim') }}" required
                placeholder="Masukkan NIM mahasiswa">
        </div>

        {{-- Status Absensi --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ old('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>
@endsection
