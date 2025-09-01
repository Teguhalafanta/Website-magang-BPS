@extends('kerangka.master')

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-center">Create Mahasiswa</h4>
            </div>
            <div class="card-content">
                <div class="card-body">

                    {{-- tampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form form-horizontal" method="POST" action="{{ route('mahasiswa.store') }}">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Nama</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="nama" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                                        placeholder="Nama">
                                    @error('nama')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label>NIM</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="nim_nisn" name="nim_nisn"
                                        class="form-control @error('nim_nisn') is-invalid @enderror"
                                        value="{{ old('nim_nisn') }}" placeholder="NIM">
                                    @error('nim_nisn')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label>Telepon</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="telepon" name="telepon"
                                        class="form-control @error('telepon') is-invalid @enderror"
                                        value="{{ old('telepon') }}" placeholder="Telepon">
                                    @error('telepon')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label>Alamat</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="alamat" name="alamat"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        value="{{ old('alamat') }}" placeholder="Alamat">
                                    @error('alamat')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
