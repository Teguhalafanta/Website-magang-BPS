@extends('kerangka.master')

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4">Form Presensi Masuk</h3>

    <form action="{{ route('presensi.store') }}" method="POST">
        @csrf

        <table class="table table-bordered w-50 mx-auto text-center">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Waktu Datang</th>
                    <th>Waktu Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $now = now();
                    $jamMasuk = \Carbon\Carbon::createFromTime(7, 30);
                    $status = $now->gt($jamMasuk) ? 'Terlambat' : 'Tepat Waktu';
                @endphp
                <tr>
                    <td>{{ $now->toDateString() }}</td>
                    <td>{{ $now->format('H:i:s') }}</td>
                    <td>-</td> {{-- Kosong karena ini absen masuk --}}
                    <td>{{ $status }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Hidden inputs untuk dikirim ke backend --}}
        <input type="hidden" name="tanggal" value="{{ $now->toDateString() }}">
        <input type="hidden" name="waktu_datang" value="{{ $now->format('H:i:s') }}">
        <input type="hidden" name="status" value="{{ $status }}">

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Absen Masuk</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
