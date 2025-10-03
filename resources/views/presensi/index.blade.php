@extends('kerangka.master')

@section('content')
<div class="container">
    <h3>Daftar Presensi</h3>
    <a href="{{ route('presensi.create') }}" class="btn btn-success mb-3">+ Presensi Masuk</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu Datang</th>
                <th>Waktu Pulang</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presensis as $p)
                <tr>
                    <td>{{ $p->tanggal }}</td>
                    <td>{{ $p->waktu_datang }}</td>
                    <td>{{ $p->waktu_pulang ?? '-' }}</td>
                    <td>{{ $p->status }}</td>
                    <td>
                        @if(!$p->waktu_pulang)
                            <form action="{{ route('presensi.update', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning btn-sm">Absen Pulang</button>
                            </form>
                        @else
                            <span class="text-success">Selesai</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
