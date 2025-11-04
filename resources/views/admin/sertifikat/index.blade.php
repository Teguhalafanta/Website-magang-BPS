@extends('kerangka.master')

@section('content')
<h3>Kelola Sertifikat Peserta Magang</h3>

<table border="1" cellpadding="10" cellspacing="0" width="70%">
    <thead style="background: #dbeafe; font-weight: bold;">
        <tr>
            <th style="text-align:center;">Nama Pelajar</th>
            <th style="text-align:center;">Laporan</th>
            <th style="text-align:center;">Upload Sertifikat / Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($laporans as $item)
        <tr>
            <td>{{ $item->user->name }}</td>

            <td style="text-align:center;">
                <a href="{{ route('laporan.download', $item->id) }}" target="_blank">
                    📄 Lihat Laporan
                </a>
            </td>

            <td>
                @if($item->file_sertifikat)
                    ✅ Sertifikat sudah dikirim <br>
                    <a href="{{ asset('storage/' . $item->file_sertifikat) }}" target="_blank">
                        🎓 Download Sertifikat
                    </a>
                @else
                    <form action="{{ route('admin.sertifikat.upload', $item->id) }}" method="POST" enctype="multipart/form-data" style="display:flex; gap:6px;">
                        @csrf
                        <input type="file" name="file_sertifikat" required>
                        <button type="submit">Kirim</button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="text-align:center; color:gray;">Belum ada laporan yang disetujui pembimbing.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
