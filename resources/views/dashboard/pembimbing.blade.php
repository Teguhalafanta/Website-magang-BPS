@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <h3>Dashboard Pembimbing</h3>
        <p>Selamat datang, {{ auth()->user()->name }} (Pembimbing)</p>
        <a href="{{ route('pembimbing.bimbingan.index') }}" class="btn btn-primary">Lihat Bimbingan</a>
    </div>
@endsection
