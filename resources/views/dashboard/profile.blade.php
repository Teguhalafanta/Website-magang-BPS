@extends('kerangka.master')
@section('title', 'Profile')
@section('content')
    <div class="container">
        <h2>Profil Saya</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>NIM</label>
                <input type="text" class="form-control" value="{{ $profile->nim }}" disabled>
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="{{ $profile->username }}">
            </div>

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $profile->nama }}">
            </div>

            <div class="mb-3">
                <label>Asal Universitas</label>
                <input type="text" name="asal_univ" class="form-control" value="{{ $profile->asal_univ }}">
            </div>

            <div class="mb-3">
                <label>Jurusan</label>
                <input type="text" name="jurusan" class="form-control" value="{{ $profile->jurusan }}">
            </div>

            <div class="mb-3">
                <label>Prodi</label>
                <input type="text" name="prodi" class="form-control" value="{{ $profile->prodi }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $profile->email }}">
            </div>

            <div class="mb-3">
                <label>Password (kosongkan jika tidak diganti)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update Profil</button>
        </form>
    </div>
@endsection
