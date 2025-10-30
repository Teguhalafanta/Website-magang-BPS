@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Daftar Laporan Akhir Pelajar</h3>

        @if ($laporans->isEmpty())
            <div class="alert alert-info">Belum ada laporan yang diunggah.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <th>Nama Pelajar</th>
                        <th>Judul Laporan</th>
                        <th>Tanggal Upload</th>
                        <th>Berkas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporans as $laporan)
                        <tr>
                            <td class="text-center">{{ $laporan->pelajar->nama ?? '-' }}</td>
                            <td class="text-center">{{ $laporan->judul ?? 'Laporan Magang' }}</td>
                            <td class="text-center">{{ $laporan->created_at->format('d-m-Y') }}</td>
                            <td class="text-center">
                                {{-- Tombol lihat file --}}
                                <a href="{{ asset('storage/' . $laporan->file) }}" target="_blank"
                                    class="btn btn-primary btn-sm">
                                    Lihat
                                </a>
                                {{-- Tombol download file --}}
                                <a href="{{ route('laporan.download', $laporan->id) }}" class="btn btn-success btn-sm">
                                    Download
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
