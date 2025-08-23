@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Mahasiswa</h2>

    {{-- Tampilkan pesan error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form edit mahasiswa --}}
    <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama">Nama</label>
            <input type="text" name="nama" class="form-control" 
                   value="{{ old('nama', $mahasiswa->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="nim">NIM</label>
            <input type="text" name="nim" class="form-control" 
                   value="{{ old('nim', $mahasiswa->nim) }}" required>
        </div>

        <div class="mb-3">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control" 
                   value="{{ old('telepon', $mahasiswa->telepon) }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ old('alamat', $mahasiswa->alamat) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
