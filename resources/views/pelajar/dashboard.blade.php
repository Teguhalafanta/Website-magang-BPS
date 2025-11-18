@extends('kerangka.master')

@section('content')
@php
    $pelajar = auth()->check() ? auth()->user()->pelajar : null;
    $status = $pelajar ? $pelajar->status : null;
    // helper to decide disabled state
    function cardDisabled($allowedStatuses, $current)
    {
        return !in_array($current, $allowedStatuses);
    }
@endphp
    
<div class="container py-2">
    <div class="row mb-4">
        <div class="col">
            <h3 class="fw-bold text-primary">Dashboard Pelajar</h3>
            <p class="text-muted">Ringkasan singkat dan akses cepat ke fitur penting</p>
        </div>
    </div>

    <div class="row g-3">
        {{-- Presensi (boleh diakses jika disetujui atau selesai) --}}
        <div class="col-md-4">
            <a href="{{ route('pelajar.presensi.index') }}"
                class="card text-decoration-none h-100 {{ cardDisabled(['disetujui','selesai'],$status) ? 'text-muted' : '' }}"
                style="pointer-events: {{ cardDisabled(['disetujui','selesai'],$status) ? 'none' : 'auto' }};">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 display-6 text-primary"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <h6 class="mb-0">Presensi</h6>
                        <small class="text-muted">Lihat dan kelola presensi Anda</small>
                    </div>
                </div>
            </a>
        </div>

        {{-- Kegiatan (boleh diakses jika disetujui atau selesai) --}}
        <div class="col-md-4">
            <a href="{{ route('pelajar.kegiatan.harian') }}" class="card text-decoration-none h-100 {{ cardDisabled(['disetujui','selesai'],$status) ? 'text-muted' : '' }}"
                style="pointer-events: {{ cardDisabled(['disetujui','selesai'],$status) ? 'none' : 'auto' }};">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 display-6 text-primary"><i class="bi bi-journal-text"></i></div>
                    <div>
                        <h6 class="mb-0">Kegiatan</h6>
                        <small class="text-muted">Lihat catatan kegiatan harian & bulanan</small>
                    </div>
                </div>
            </a>
        </div>

        {{-- Produk --}}
        <div class="col-md-4">
            <a href="{{ route('pelajar.produk.index') }}" class="card text-decoration-none h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 display-6 text-primary"><i class="bi bi-archive"></i></div>
                    <div>
                        <h6 class="mb-0">Produk Magang</h6>
                        <small class="text-muted">Kelola produk magang Anda</small>
                    </div>
                </div>
            </a>
        </div>

        {{-- Upload Laporan Akhir (boleh diakses jika disetujui atau selesai) --}}
        <div class="col-md-4">
            <a href="{{ route('pelajar.laporan.index') }}" class="card text-decoration-none h-100 {{ cardDisabled(['disetujui','selesai'],$status) ? 'text-muted' : '' }}"
                style="pointer-events: {{ cardDisabled(['disetujui','selesai'],$status) ? 'none' : 'auto' }};">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 display-6 text-primary"><i class="bi bi-upload"></i></div>
                    <div>
                        <h6 class="mb-0">Laporan Akhir</h6>
                        <small class="text-muted">Upload dan cek status laporan akhir</small>
                    </div>
                </div>
            </a>
        </div>

        {{-- Sertifikat / Riwayat (boleh dilihat saat selesai) --}}
        <div class="col-md-4">
            <a href="{{ route('pelajar.laporan.index') }}" class="card text-decoration-none h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 display-6 text-primary"><i class="bi bi-award-fill"></i></div>
                    <div>
                        <h6 class="mb-0">Sertifikat / Riwayat</h6>
                        <small class="text-muted">Unduh sertifikat atau lihat riwayat magang</small>
                    </div>
                </div>
            </a>
        </div>

        {{-- Info box when not approved --}}
        @if(!$pelajar || $status !== 'disetujui' && $status !== 'selesai')
            <div class="col-12 mt-3">
                <div class="alert alert-info">
                    Status pengajuan Anda: <strong>{{ $status ?? 'Belum mengajukan' }}</strong>. Beberapa fitur mungkin terkunci hingga pengajuan disetujui.
                </div>
            </div>
        @endif

    </div>
</div>
@endsection