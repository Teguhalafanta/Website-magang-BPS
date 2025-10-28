@extends('kerangka.master')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Laporan Akhir Pelajar</h3>

    @if ($laporans->isEmpty())
        <div class="alert alert-info">Belum ada laporan yang diunggah.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Pelajar</th>
                    <th>Judul Laporan</th>
                    <th>Tanggal Upload</th>
                    <th>Berkas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporans as $laporan)
                    <tr>
                        <td>{{ $laporan->user->name ?? '-' }}</td>
                        <td>{{ $laporan->judul ?? 'Laporan Magang' }}</td>
                        <td>{{ $laporan->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ asset('storage/laporan/' . $laporan->file) }}" target="_blank" class="btn btn-primary btn-sm">
                                Lihat File
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
