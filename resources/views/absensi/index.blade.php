@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-success text-white">
            Absensi Mahasiswa
        </div>
        <div class="card-body">
            {{-- Tampilkan pesan sukses --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form tambah absensi --}}
            <form action="{{ route('absensi.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label">Mahasiswa</label>
                        <select name="mahasiswa_id" class="form-select">
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach ($mahasiswas as $mhs)
                                <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                    {{ $mhs->nama }} ({{ $mhs->nim }})
                                </option>
                            @endforeach
                        </select>
                        @error('mahasiswa_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}">
                        @error('tanggal')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">-- Pilih Status --</option>
                            @foreach (['Hadir', 'Izin', 'Sakit', 'Alfa'] as $status)
                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}">
                        @error('keterangan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-success w-100">Simpan</button>
                    </div>
                </div>
            </form>

            {{-- Tabel daftar absensi --}}
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absen)
                        <tr>
                            <td>{{ $absen->mahasiswa->nama }}</td>
                            <td>{{ $absen->mahasiswa->nim }}</td>
                            <td>{{ $absen->tanggal }}</td>
                            <td>{{ $absen->status }}</td>
                            <td>{{ $absen->keterangan }}</td>
                            <td>
                                <a href="{{ route('absensi.edit', $absen->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('absensi.destroy', $absen->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus data absensi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Data absensi belum ada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            {{ $absensis->links() }}
        </div>

        {{-- Tabel Absensi --}}
        <div class="card shadow">
            <div class="card-header bg-primary text-white">Daftar Absensi</div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $absen)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $absen->mahasiswa->nama ?? '-' }}</td>
                                <td>{{ $absen->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ ucfirst($absen->status) }}</td>
                                <td>{{ $absen->created_at->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data absensi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Cek absen hari ini --}}
        @if (!$absenHariIni)
            <div class="alert alert-warning mt-3">
                Anda <b>belum absen hari ini</b>. Silakan lakukan absen terlebih dahulu.
            </div>
            <button class="btn btn-secondary mb-3" disabled>Tambah Kegiatan</button>
        @else
            <a href="{{ route('kegiatan.create') }}" class="btn btn-primary mt-3">Tambah Kegiatan</a>
        @endif
    </div>
@endsection
