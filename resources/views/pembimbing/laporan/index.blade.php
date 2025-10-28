@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <h3 class="text-center mb-4">Laporan Akhir Peserta Bimbingan</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card p-3 shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelajar</th>
                            <th>NIM / Username</th>
                            <th>Tanggal Upload</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporans as $index => $laporan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $laporan->user->name ?? '-' }}</td>
                                <td>{{ $laporan->user->username ?? '-' }}</td>
                                <td>{{ $laporan->created_at->format('d-m-Y') }}</td>
                                <td>{{ basename($laporan->file) }}</td>
                                <td>
                                    <a href="{{ route('laporan.download', $laporan->id) }}" class="btn btn-success btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">Belum ada laporan peserta bimbingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
