@extends('kerangka.master')

@section('content')
<div class="container mt-4">
    <h3 class="text-primary"><i class="bi bi-file-earmark-text"></i> Laporan Kegiatan Magang</h3>

    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if ($laporan)
        <div class="card mt-3">
            <div class="card-body">
                <p>Laporan sudah diunggah:</p>
                <a href="{{ asset('storage/' . $laporan->file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    Lihat Laporan
                </a>
                <form action="{{ route('pelajar.laporan.store') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                    @csrf
                    <label class="btn btn-outline-danger btn-sm mb-0">
                        Ganti File <input type="file" name="file" hidden onchange="this.form.submit()">
                    </label>
                </form>
            </div>
        </div>
    @else
        <div class="card mt-4 p-4 text-center">
            <p>Belum ada laporan yang diunggah.</p>
            <form action="{{ route('pelajar.laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control mb-3" required>
                <button type="submit" class="btn btn-primary">Upload Laporan</button>
            </form>
        </div>
    @endif
</div>
@endsection
