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

            {{-- Ajukan Magang (Hanya tampil jika belum disetujui) --}}
            @if (!auth()->user()->isApproved())
                <div class="col-md-4 mb-3">
                    <a href="{{ route('pelajar.pengajuan.create') }}" class="text-decoration-none">
                        <div class="card text-white bg-info shadow">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>Ajukan Magang</h5>
                                    <h3>&nbsp;</h3> {{-- Tambahkan elemen kosong agar tinggi seimbang --}}
                                </div>
                                <i class="bi bi-file-earmark-text fs-2"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        </div>

        {{-- Informasi Status Pengajuan (Hanya tampil jika belum disetujui) --}}
        @if (!auth()->user()->isApproved())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Informasi:</strong>
                            @if (auth()->user()->pelajar)
                                @if (auth()->user()->pelajar->status === 'menunggu')
                                    Pengajuan magang Anda sedang dalam proses review. Silakan tunggu persetujuan dari admin.
                                @elseif(auth()->user()->pelajar->status === 'ditolak')
                                    Pengajuan magang Anda ditolak.
                                    @if (auth()->user()->pelajar->alasan)
                                        <br><strong>Alasan:</strong> {{ auth()->user()->pelajar->alasan }}
                                    @endif
                                    <br>Anda dapat mengajukan kembali dengan memperbaiki data pengajuan.
                                @else
                                    Silakan lengkapi pengajuan magang Anda untuk mengakses semua fitur.
                                @endif
                            @else
                                Silakan ajukan magang terlebih dahulu untuk mengakses semua fitur dashboard.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
