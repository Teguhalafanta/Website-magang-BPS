@extends('kerangka.master')

@section('content')
<div class="container py-2">
    <h4 class="mb-3">Upload Laporan Akhir</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('pelajar.laporan.create') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pilih File Laporan (PDF)</label>
                    <input type="file" name="file_laporan" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
