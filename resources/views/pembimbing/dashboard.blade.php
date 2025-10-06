@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <h4>Dashboard Pembimbing</h4>
        <p>Selamat datang, {{ Auth::user()->name }} 👋</p>
    </div>
@endsection
