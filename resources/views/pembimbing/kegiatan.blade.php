@extends('kerangka.master')

@section('title', 'Daftar Kegiatan Pelajar Saya')

@section('content')
    <div class="container my-5">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="white" viewBox="0 0 16 16">
                            <path
                                d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Daftar Kegiatan Pelajar</h2>
                        <p class="text-muted mb-0">Laporan aktivitas dan progress pelajar bimbingan Anda</p>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2"
                        viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Card -->
        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
            <!-- Card Header -->
            <div class="card-header bg-gradient text-white py-3 border-0"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="me-2 mb-1" viewBox="0 0 16 16">
                            <path
                                d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                            <path
                                d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
                        </svg>
                        Tabel Kegiatan
                    </h5>
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-semibold">
                        Total: {{ $kegiatans->total() }} Kegiatan
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 50px;">No</th>
                                <th class="py-3 px-4 fw-semibold text-secondary text-start" style="min-width: 180px;">Nama
                                    Kegiatan</th>
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 110px;">Tanggal</th>
                                <th class="py-3 px-4 fw-semibold text-secondary text-start" style="min-width: 200px;">
                                    Deskripsi</th>
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 80px;">Volume</th>
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 90px;">Satuan</th>
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 90px;">Durasi</th>
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 120px;">Status</th>
                                <th class="py-3 px-4 fw-semibold text-secondary text-start" style="min-width: 140px;">
                                    Pelajar</th>
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 100px;">Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kegiatans as $index => $kegiatan)
                                <tr class="border-bottom">
                                    <td class="text-center px-3">
                                        <span class="badge bg-light text-dark rounded-circle p-2"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            {{ $kegiatans->firstItem() + $index }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-semibold text-dark">{{ $kegiatan->nama_kegiatan }}</div>
                                    </td>
                                    <td class="text-center px-3">
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                fill="currentColor" class="me-1 mb-1" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <span class="text-muted"
                                            style="font-size: 0.9rem;">{{ Str::limit($kegiatan->deskripsi, 60) }}</span>
                                    </td>
                                    <td class="text-center px-3">
                                        <span class="fw-semibold text-dark">{{ $kegiatan->volume ?? '-' }}</span>
                                    </td>
                                    <td class="text-center px-3">
                                        <span class="text-muted">{{ $kegiatan->satuan ?? '-' }}</span>
                                    </td>
                                    <td class="text-center px-3">
                                        @if ($kegiatan->durasi)
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                                {{ $kegiatan->durasi }} mnt
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center px-3">
                                        @php
                                            $statusColors = [
                                                'Belum Dimulai' => 'secondary',
                                                'Dalam Proses' => 'warning',
                                                'Selesai' => 'success',
                                            ];
                                            $colorClass = $statusColors[$kegiatan->status_penyelesaian] ?? 'primary';
                                        @endphp
                                        <span class="badge bg-{{ $colorClass }} px-3 py-2">
                                            {{ $kegiatan->status_penyelesaian }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2"
                                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                                </svg>
                                            </div>
                                            <span class="text-dark">{{ $kegiatan->pelajar->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center px-3">
                                        @if ($kegiatan->bukti_dukung)
                                            <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="me-1 mb-1" viewBox="0 0 16 16">
                                                    <path
                                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                                    <path
                                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                                </svg>
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                fill="currentColor" class="mb-3 opacity-50" viewBox="0 0 16 16">
                                                <path
                                                    d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z" />
                                            </svg>
                                            <p class="fw-semibold mb-1">Belum Ada Kegiatan</p>
                                            <p class="small mb-0">Data kegiatan pelajar akan muncul di sini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($kegiatans->hasPages())
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $kegiatans->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
