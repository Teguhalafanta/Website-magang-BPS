@extends('kerangka.master')

@section('content')
    <h3 class="mb-4 fw-bold">Kelola Sertifikat Peserta Magang</h3>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th style="text-align:center;">Nama Pelajar</th>
                <th style="text-align:center;">Laporan Akhir</th>
                <th style="text-align:center;">Sertifikat</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($laporans as $item)
                <tr>
                    <td>{{ $item->user->pelajar->nama ?? $item->user->name }}</td>

                    <td class="text-center">
                        <a href="{{ route('laporan.download', $item->id) }}" target="_blank"
                            class="btn btn-outline-primary btn-sm">
                            📄 Lihat Laporan
                        </a>
                    </td>

                    <td class="text-center">

                        @if ($item->file_sertifikat)
                            <span class="badge bg-success mb-1">Sertifikat Sudah Dikirim</span>
                            <br>
                            <a href="{{ asset('storage/' . $item->file_sertifikat) }}" class="btn btn-primary btn-sm"
                                target="_blank">
                                🎓 Download Sertifikat
                            </a>
                        @else
                            <form action="{{ route('admin.sertifikat.upload', $item->id) }}" method="POST"
                                enctype="multipart/form-data" class="d-flex justify-content-center" style="gap:6px;">
                                @csrf
                                <input type="file" name="file_sertifikat" class="form-control form-control-sm" required>
                                <button type="submit" class="btn btn-success btn-sm">Kirim</button>
                            </form>
                        @endif

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Belum ada laporan yang disetujui pembimbing.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
