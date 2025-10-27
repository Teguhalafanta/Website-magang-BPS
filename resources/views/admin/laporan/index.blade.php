@extends('kerangka.master')

@section('content')
    <h3>Daftar Laporan Akhir Pelajar</h3>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Pelajar</th>
                <th>File</th>
                <th>Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporans as $laporan)
                <tr>
                    <td>{{ $laporan->user->name }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $laporan->file) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                    </td>
                    <td>{{ $laporan->created_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
