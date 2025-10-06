@extends('kerangka.master')

@section('title', 'Daftar Presensi Mahasiswa')

@section('content')
    <div class="container my-4">
        <h3>Daftar Presensi Mahasiswa</h3>

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

        {{-- Tabel Presensi --}}
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white fw-bold">
                <i class="fas fa-table me-2"></i>Presensi Pelajar
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Pelajar</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($presensis as $index => $presensi)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $presensi->pelajar->nama ?? '-' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d-m-Y') }}
                                    </td>
                                    <td class="text-center">{{ $presensi->status }}</td>
                                    <td>{{ $presensi->keterangan ?? '-' }}</td>
                                    <td class="text-center">{{ ucfirst($presensi->shift) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada data presensi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($presensis->hasPages())
                <div class="card-footer d-flex justify-content-center">
                    {{ $presensis->links() }}
                </div>
            @endif
        </div>
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
