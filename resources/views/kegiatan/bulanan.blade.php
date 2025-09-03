@extends('kerangka.master')

@section('content')
    <div class="container">
        <h3>Kegiatan Bulanan</h3>
        <p>Bulan: {{ now()->format('F Y') }}</p>

        @if ($kegiatans->isEmpty())
            <div class="alert alert-info">
                Tidak ada kegiatan untuk bulan ini.
            </div>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kegiatans as $index => $kegiatan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kegiatan->nama_kegiatan }}</td>
                            <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $kegiatan->deskripsi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
