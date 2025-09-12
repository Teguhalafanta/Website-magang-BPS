@extends('kerangka.master')

@section('content')
<div class="container">
    <h2>Detail Kegiatan</h2>

    <p><strong>Judul:</strong> {{ $kegiatan->judul }}</p>
    <p><strong>Deskripsi:</strong> {{ $kegiatan->deskripsi }}</p>
    <p><strong>Tanggal:</strong> {{ $kegiatan->tanggal->format('d-m-Y') }}</p>
    <p><strong>Status:</strong> {{ $kegiatan->status }}</p>

    <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
