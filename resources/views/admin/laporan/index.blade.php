@extends('kerangka.master')

@section('content')
<h3>Halaman Admin - Upload Sertifikat</h3>

@if(session('success'))
<div style="color: green;">{{ session('success') }}</div>
@endif

@if(session('error'))
<div style="color: red;">{{ session('error') }}</div>
@endif

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Nama Peserta</th>
        <th>Laporan Magang</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach($laporans as $laporan)
    <tr>
        <td>{{ $laporan->user->name }}</td>
        <td>
            <a href="{{ route('download.laporan', $laporan->id) }}" target="_blank">Lihat Laporan</a>
        </td>
        <td>
            @if($laporan->status == 'menunggu')
                <span style="color: orange;">Menunggu Pembimbing</span>
            @elseif($laporan->status == 'disetujui')
                <span style="color: green;">Disetujui Pembimbing</span>
            @elseif($laporan->status == 'ditolak')
                <span style="color: red;">Ditolak - Peserta Upload Ulang</span>
            @elseif($laporan->status == 'selesai')
                <span style="color: blue;">Selesai</span>
            @endif
        </td>
        <td>
            @if($laporan->status == 'disetujui')
                <!-- Upload sertifikat -->
                <form action="{{ route('admin.upload.sertifikat', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file_sertifikat" required>
                    <button type="submit">Upload Sertifikat</button>
                </form>
            @elseif($laporan->status == 'selesai')
                <!-- Download sertifikat -->
                <a href="{{ asset('storage/'.$laporan->file_sertifikat) }}" target="_blank">Download Sertifikat</a>
            @else
                Tidak ada aksi
            @endif
        </td>
    </tr>
    @endforeach
</table>

@endsection
