@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4 fw-bold text-primary">Daftar Absensi</h3>

    {{-- Tabel Absensi --}}
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensis as $index => $absensi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $absensi->mahasiswa->nama ?? '-' }}</td>
                        <td>{{ $absensi->mahasiswa->nim ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ match(strtolower($absensi->status)) {
                                'hadir' => 'success',
                                'izin' => 'info',
                                'sakit' => 'warning',
                                'alpa', 'alpha' => 'danger',
                                default => 'secondary'
                            } }}">
                                {{ ucfirst($absensi->status) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada data absensi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Notifikasi belum absen --}}
    @if($belumAbsen)
        <div class="alert alert-warning mt-4">
            Anda <strong>belum absen hari ini.</strong> Silakan lakukan absen terlebih dahulu.
        </div>
    @endif

    {{-- Tombol Tambah Absen --}}
    <a href="{{ route('absensi.create') }}" class="btn btn-primary mt-2">
        Tambah Absen
    </a>

    {{-- Data Mahasiswa --}}
    <h4 class="mt-5 fw-bold text-primary">Data Mahasiswa</h4>
    <div class="table-responsive">
        <table class="table table-hover text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>No Telpon</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $index => $mhs)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mhs->nama }}</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->no_telp }}</td>
                        <td>{{ $mhs->alamat }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada data mahasiswa</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
