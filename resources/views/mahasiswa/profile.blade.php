@extends('kerangka.master')
@section('title', 'Profil Mahasiswa')

@section('content')
    <div class="container">
        <h2>Profil Saya</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>

        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
    </div>
@endsection
