@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data Kegiatan</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('kegiatan.create') }}" class="btn btn-primary mb-3">+ Tambah Kegiatan</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Kegiatan</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kegiatans as $kegiatan)
                <tr>
                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                    <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $kegiatan->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada kegiatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
