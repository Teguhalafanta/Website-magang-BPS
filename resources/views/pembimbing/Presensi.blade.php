@extends('kerangka.master')

@section('title', 'Presensi Bimbingan')

@section('content')
    <div class="container my-4">
        <h3>Daftar Presensi Pelajar Bimbingan</h3>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Cek jika data kosong --}}
        @if ($presensis->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                <p class="text-muted mt-2 mb-0">Belum ada data presensi.</p>
            </div>
        @else
            @php
                // Kelompokkan data presensi berdasarkan nama pelajar
                $grouped = $presensis->groupBy(function ($item) {
                    return $item->pelajar->nama ?? 'Tanpa Nama';
                });
            @endphp

            {{-- Tampilkan presensi per pelajar --}}
            @foreach ($grouped as $nama => $list)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white fw-bold">
                        <i class="fas fa-user me-2"></i>{{ $nama }}
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Shift</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $index => $presensi)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d-m-Y') }}
                                            </td>
                                            <td class="text-center">{{ ucfirst($presensi->status) }}</td>
                                            <td>{{ $presensi->keterangan ?? '-' }}</td>
                                            <td class="text-center">{{ ucfirst($presensi->shift) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .table th,
        .table td {
            vertical-align: middle;
            white-space: nowrap;
        }
    </style>
@endpush
