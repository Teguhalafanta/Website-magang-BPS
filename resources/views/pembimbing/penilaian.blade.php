@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <h4>Penilaian Peserta</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penilaians as $n)
                    <tr>
                        <td>{{ $n->pelajar->nama }}</td>
                        <td>{{ $n->nilai }}</td>
                        <td>{{ $n->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
