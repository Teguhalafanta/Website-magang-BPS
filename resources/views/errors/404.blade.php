@extends('kerangka.master')

@section('content')
    <div class="container text-center py-5">
        <h1 class="display-4 text-danger">404</h1>
        <p class="lead">Halaman yang kamu cari tidak ditemukan.</p>
        <a href="{{ url('/dashboard') }}" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
    </div>
@endsection
