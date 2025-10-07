@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Dashboard Pelajar</h2>

        <div class="row">
            {{-- Presensi Hari Ini --}}
            <div class="col-md-4 mb-3">
                <a href="{{ route('pelajar.presensi.index', ['today' => true]) }}" class="text-decoration-none">
                    <div class="card bg-warning text-white shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Presensi Hari Ini</h5>
                                <h3>{{ $jumlahPresensiHariIni }}</h3>
                            </div>
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Kegiatan --}}
            <div class="col-md-4 mb-3">
                <a href="{{ route('pelajar.kegiatan.index', ['today' => true]) }}" class="text-decoration-none">
                    <div class="card text-white bg-success shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Kegiatan</h5>
                                <h3>{{ $jumlahKegiatan }}</h3>
                            </div>
                            <i class="bi bi-list-task fs-2"></i>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Ajukan Magang --}}
            <div class="col-md-4 mb-3">
                <a href="{{ route('pelajar.pengajuan.create') }}" class="text-decoration-none">
                    <div class="card text-white bg-info shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Ajukan Magang</h5>
                            </div>
                            <i class="bi bi-file-earmark-text fs-2"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
