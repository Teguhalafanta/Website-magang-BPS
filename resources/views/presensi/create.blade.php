@extends('kerangka.master')

@section('content')
<div class="container">
    <h3>Form Presensi Masuk</h3>
    <form action="{{ route('presensi.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Absen Masuk</button>
        <a href="{{ route('presensi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
