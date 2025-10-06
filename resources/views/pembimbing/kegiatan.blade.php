@extends('kerangka.master')

@section('title', 'Daftar Kegiatan Pelajar Saya')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4 text">Daftar Kegiatan Pelajar Saya</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white fw-bold">Tabel Kegiatan</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Pelajar</th>
                                <th>Bukti Dukung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kegiatans as $index => $kegiatan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ Str::limit($kegiatan->deskripsi, 50) }}</td>
                                    <td>{{ $kegiatan->volume ?? '-' }}</td>
                                    <td>{{ $kegiatan->satuan ?? '-' }}</td>
                                    <td>{{ $kegiatan->durasi ? $kegiatan->durasi . ' mnt' : '-' }}</td>
                                    <td>{{ $kegiatan->status_penyelesaian }}</td>
                                    <td>{{ $kegiatan->pelajar->user->name ?? '-' }}</td>
                                    <td>
                                        @if ($kegiatan->bukti_dukung)
                                            <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}"
                                                target="_blank">Lihat</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Belum ada kegiatan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($kegiatans->hasPages())
                <div class="card-footer d-flex justify-content-center">
                    {{ $kegiatans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
