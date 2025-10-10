@extends('kerangka.master')

@section('title', 'Presensi Bimbingan')

@section('content')
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold text-black mb-2">
                    <i class="fas fa-clipboard-check me-2"></i>Daftar Presensi Pelajar Bimbingan
                </h2>
                <p class="text-muted">Monitoring kehadiran dan aktivitas bimbingan pelajar</p>
            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Cek jika data kosong --}}
        @if ($presensis->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-inbox text-muted" style="font-size: 64px; opacity: 0.5;"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Data Presensi</h5>
                    <p class="text-muted small mb-0">Data presensi akan muncul di sini setelah pelajar melakukan absensi</p>
                </div>
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
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">{{ $nama }}</h5>
                                <small class="text-white opacity-75">Total Presensi: {{ $list->count() }} kali</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center border-0 py-3" style="width: 60px;">
                                            <small class="text-muted fw-bold">No</small>
                                        </th>
                                        <th class="text-center border-0 py-3">
                                            <small class="text-muted fw-bold">
                                                <i class="fas fa-calendar-alt me-1"></i>Tanggal
                                            </small>
                                        </th>
                                        <th class="text-center border-0 py-3">
                                            <small class="text-muted fw-bold">
                                                <i class="fas fa-check-circle me-1"></i>Status
                                            </small>
                                        </th>
                                        <th class="border-0 py-3">
                                            <small class="text-muted fw-bold">
                                                <i class="fas fa-comment-dots me-1"></i>Keterangan
                                            </small>
                                        </th>
                                        <th class="text-center border-0 py-3">
                                            <small class="text-muted fw-bold">
                                                <i class="fas fa-clock me-1"></i>Shift
                                            </small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $index => $presensi)
                                        <tr class="border-bottom">
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark rounded-circle"
                                                    style="width: 32px; height: 32px; line-height: 32px; display: inline-block;">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-normal">
                                                    {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $statusClass = match (strtolower($presensi->status)) {
                                                        'hadir' => 'success',
                                                        'izin' => 'warning',
                                                        'sakit' => 'info',
                                                        'alpha' => 'danger',
                                                        default => 'secondary',
                                                    };
                                                    $statusIcon = match (strtolower($presensi->status)) {
                                                        'hadir' => 'check',
                                                        'izin' => 'exclamation',
                                                        'sakit' => 'notes-medical',
                                                        'alpha' => 'times',
                                                        default => 'question',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }} px-3 py-2">
                                                    <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                                    {{ ucfirst($presensi->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ $presensi->keterangan ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 fw-normal">
                                                    {{ ucfirst($presensi->shift) }}
                                                </span>
                                            </td>
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
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transition: background-color 0.2s ease;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .text-dark-muted {
            color: #6c757d;
        }
    </style>
@endpush
