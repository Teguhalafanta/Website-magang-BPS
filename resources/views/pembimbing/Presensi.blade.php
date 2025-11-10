@extends('kerangka.master')

@section('title', 'Presensi Bimbingan')

@section('content')
    <div class="container my-5">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Daftar Presensi Peserta Bimbingan</h2>
                        <p class="text-muted mb-0">Monitoring kehadiran dan aktivitas bimbingan pelajar</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
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

    <!-- Alert Error -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2"
                    viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                </svg>
                <span>{{ session('error') }}</span>
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
                <h5 class="mb-0 fw-bold text-white">
                    Tabel Presensi
                </h5>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="card-body bg-light border-bottom">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label for="searchInput" class="form-label fw-semibold mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="me-1" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                        Cari Presensi
                    </label>
                    <input type="text" id="searchInput" class="form-control form-control-lg border-2"
                        placeholder="Ketik untuk mencari...">
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('pembimbing.presensi') }}" id="filterForm">
                        <label for="pelajar_id" class="form-label fw-semibold mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="me-1" viewBox="0 0 16 16">
                                <path
                                    d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                            </svg>
                            Filter Pelajar
                        </label>
                        <select name="pelajar_id" id="pelajar_id" class="form-select form-select-lg border-2"
                            onchange="this.form.submit()">
                            <option value="">Semua Pelajar</option>
                            @foreach ($pelajars as $pelajar)
                                <option value="{{ $pelajar->id }}"
                                    {{ isset($selectedPelajarId) && $selectedPelajarId == $pelajar->id ? 'selected' : '' }}>
                                    {{ $pelajar->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="bulan" value="{{ request('bulan', now()->format('Y-m')) }}">
                    </form>
                </div>
                <div class="col-md-4">
                    <label for="bulan" class="form-label fw-semibold mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="me-1" viewBox="0 0 16 16">
                            <path
                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                        </svg>
                        Filter Bulan
                    </label>
                    <input type="month" name="bulan" id="bulan" class="form-control form-control-lg border-2"
                        value="{{ request('bulan', now()->format('Y-m')) }}"
                        onchange="document.getElementById('filterForm').submit()">
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="presensiTable" class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-center align-middle">
                            <th class="py-3 px-3 fw-semibold text-secondary" style="width: 50px;">No</th>
                            <th class="py-3 px-3 fw-semibold text-secondary text-start" style="min-width: 200px;">
                                Pelajar</th>
                            <th class="py-3 px-3 fw-semibold text-secondary" style="width: 120px;">Tanggal</th>
                            <th class="py-3 px-3 fw-semibold text-secondary" style="width: 100px;">Status</th>
                            <th class="py-3 px-3 fw-semibold text-secondary" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Jika pelajar terpilih, tampilkan semua tanggal sesuai durasi magangnya
                            $showRange = isset($selectedPelajarId) && $selectedPelajarId;
                            if ($showRange) {
                                $pelajarFull = $pelajars->where('id', $selectedPelajarId)->first();
                                if ($pelajarFull) {
                                    $mulaiFull = \Carbon\Carbon::parse($pelajarFull->rencana_mulai ?? now());
                                    $selesaiFull = \Carbon\Carbon::parse($pelajarFull->rencana_selesai ?? now());
                                    $semuaTanggalFull = [];
                                    for ($d = $mulaiFull->copy(); $d->lte($selesaiFull); $d->addDay()) {
                                        $semuaTanggalFull[] = $d->format('Y-m-d');
                                    }

                                    $pelajarPresensis = $presensis
                                        ->filter(function ($p) use ($selectedPelajarId) {
                                            return $p->pelajar_id == $selectedPelajarId;
                                        })
                                        ->unique('tanggal');
                                    $presensiMapFull = $pelajarPresensis->keyBy('tanggal');
                                } else {
                                    $showRange = false;
                                }
                            }
                        @endphp

                        @if (!empty($showRange) && !empty($pelajarFull))
                            @php $no = 1; @endphp
                            @foreach ($semuaTanggalFull as $tanggal)
                                @php
                                    $pres = $presensiMapFull->get($tanggal);
                                    $carbonDate = \Carbon\Carbon::parse($tanggal);
                                    $isWeekend = $carbonDate->isWeekend();
                                @endphp
                                <tr class="border-bottom">
                                    <td class="text-center px-3">
                                        <span class="badge bg-light text-dark rounded-circle p-2"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            {{ $no++ }}
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
                                            <span class="text-dark">{{ $pelajarFull->nama ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center px-3">
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-2">
                                            {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center px-3">
                                        @if ($pres)
                                            @php
                                                $statusClass = match (strtolower($pres->status)) {
                                                    'hadir' => 'success',
                                                    'izin' => 'warning',
                                                    'sakit' => 'info',
                                                    'alpha' => 'danger',
                                                    'terlambat' => 'secondary',
                                                    default => 'secondary',
                                                };
                                                $statusIcon = match (strtolower($pres->status)) {
                                                    'hadir' => 'check',
                                                    'izin' => 'exclamation',
                                                    'sakit' => 'notes-medical',
                                                    'alpha' => 'times',
                                                    'terlambat' => 'clock',
                                                    default => 'question',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} px-3 py-2">
                                                <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                                {{ ucfirst($pres->status) }}
                                            </span>
                                        @else
                                            @if ($carbonDate->isFuture())
                                                <span class="badge bg-secondary">Belum Tiba</span>
                                            @elseif($isWeekend)
                                                <span class="badge bg-secondary">Libur</span>
                                            @else
                                                <span class="badge bg-danger">Alfa</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center px-3">
                                        <button type="button"
                                            class="btn btn-sm {{ $pres ? 'btn-primary' : 'btn-success' }} btn-edit-presensi"
                                            data-tanggal="{{ $tanggal }}" data-pelajar-id="{{ $pelajarFull->id }}"
                                            data-pelajar-nama="{{ $pelajarFull->nama }}"
                                            data-id="{{ $pres ? $pres->id : '' }}" data-bs-toggle="modal"
                                            data-bs-target="#editPresensiModal">
                                            <i class="fas fa-{{ $pres ? 'edit' : 'plus' }} me-1"></i>
                                            {{ $pres ? 'Edit' : 'Isi' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @forelse ($presensis as $index => $presensi)
                                <tr class="border-bottom" id="row-{{ $presensi->id }}">
                                    <td class="text-center px-3">
                                        <span class="badge bg-light text-dark rounded-circle p-2"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            {{ $index + 1 }}
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
                                            <span class="text-dark">{{ $presensi->pelajar->nama ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center px-3">
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                fill="currentColor" class="me-1 mb-1" viewBox="0 0 16 16">
                                                <path
                                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center px-3">
                                        @php
                                            $statusClass = match (strtolower($presensi->status)) {
                                                'hadir' => 'success',
                                                'izin' => 'warning',
                                                'sakit' => 'info',
                                                'alpha' => 'danger',
                                                'terlambat' => 'secondary',
                                                default => 'secondary',
                                            };
                                            $statusIcon = match (strtolower($presensi->status)) {
                                                'hadir' => 'check',
                                                'izin' => 'exclamation',
                                                'sakit' => 'notes-medical',
                                                'alpha' => 'times',
                                                'terlambat' => 'clock',
                                                default => 'question',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }} px-3 py-2"
                                            id="badge-{{ $presensi->id }}">
                                            <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                            {{ ucfirst($presensi->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center px-3">
                                        <button type="button" class="btn btn-sm btn-primary btn-edit-presensi"
                                            data-id="{{ $presensi->id }}" data-bs-toggle="modal"
                                            data-bs-target="#editPresensiModal">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                fill="currentColor" class="mb-3 opacity-50" viewBox="0 0 16 16">
                                                <path
                                                    d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z" />
                                            </svg>
                                            <p class="fw-semibold mb-1">Belum Ada Data Presensi</p>
                                            <p class="small mb-0">Data presensi akan muncul di sini setelah pelajar
                                                melakukan
                                                absensi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div class="d-flex justify-content-between align-items-center p-3 bg-light border-top">
                <div class="text-muted small" id="tableInfo">
                    Menampilkan data
                </div>
                <nav id="paginationNav">
                    <!-- Pagination will be inserted here by DataTables -->
                </nav>
            </div>
        </div>
    </div>

    {{-- STATISTIK BULAN TERPILIH --}}
    @php
        $bulanDipilih = request('bulan', now()->format('Y-m'));

        // Filter presensi berdasarkan bulan dan pelajar yang dipilih
        $presensiFilterBulan = $presensis
            ->filter(function ($p) use ($bulanDipilih, $selectedPelajarId) {
                $matchBulan = substr($p->tanggal, 0, 7) == $bulanDipilih;
                $matchPelajar = !$selectedPelajarId || $p->pelajar_id == $selectedPelajarId;
                return $matchBulan && $matchPelajar;
            })
            ->unique('tanggal');

        // Hitung total berdasarkan status
        $totalHadir = $presensiFilterBulan->count();
        $totalTepat = $presensiFilterBulan->where('status', 'Tepat Waktu')->count();
        $totalTerlambat = $presensiFilterBulan->where('status', 'Terlambat')->count();
        $totalIzin = $presensiFilterBulan->where('status', 'Izin')->count();
        $totalSakit = $presensiFilterBulan->where('status', 'Sakit')->count();
        $totalAlfa = $presensiFilterBulan->where('status', 'Alpha')->count();
    @endphp

    {{-- CARD STATISTIK --}}
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-body p-3">
            <div class="fw-bold mb-3">
                <i class="bi bi-bar-chart-line me-2"></i>Statistik Bulan
                {{ \Carbon\Carbon::parse($bulanDipilih)->locale('id')->isoFormat('MMMM Y') }}
                @if ($selectedPelajarId)
                    <span class="text-muted small">-
                        {{ $pelajars->where('id', $selectedPelajarId)->first()->nama ?? '' }}</span>
                @endif
            </div>
            <div class="row text-center g-3">
                <div class="col-6 col-md-2">
                    <div class="p-3 bg-success bg-opacity-10 rounded">
                        <div class="text-success fw-bold fs-4">{{ $totalHadir }}</div>
                        <div class="small text-muted">Hadir</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                        <div class="text-primary fw-bold fs-4">{{ $totalTepat }}</div>
                        <div class="small text-muted">Tepat Waktu</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                        <div class="text-warning fw-bold fs-4">{{ $totalTerlambat }}</div>
                        <div class="small text-muted">Terlambat</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="p-3 bg-info bg-opacity-10 rounded">
                        <div class="text-info fw-bold fs-4">{{ $totalSakit }}</div>
                        <div class="small text-muted">Sakit</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                        <div class="fw-bold fs-4" style="color: #ffc107;">{{ $totalIzin }}</div>
                        <div class="small text-muted">Izin</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="p-3 bg-danger bg-opacity-10 rounded">
                        <div class="text-danger fw-bold fs-4">{{ $totalAlfa }}</div>
                        <div class="small text-muted">Alfa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Edit Presensi -->
    <div class="modal fade" id="editPresensiModal" tabindex="-1" aria-labelledby="editPresensiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title text-white" id="editPresensiModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Presensi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPresensiForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="presensi_id">
                        <input type="hidden" id="pelajar_id" name="pelajar_id">
                        <input type="hidden" id="tanggal" name="tanggal">

                        <!-- Info Pelajar -->
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user me-2"></i>
                                <strong>Informasi Pelajar</strong>
                            </div>
                            <p class="mb-1 fw-bold" id="modal_pelajar_nama"></p>
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                <span id="modal_tanggal"></span>
                            </small>
                        </div>

                        <!-- Status Presensi -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Status Presensi <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="status" id="status_hadir"
                                        value="Hadir">
                                    <label class="btn btn-outline-success w-100" for="status_hadir">
                                        <i class="fas fa-check-circle me-1"></i> Hadir
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="status" id="status_izin"
                                        value="Izin">
                                    <label class="btn btn-outline-warning w-100" for="status_izin">
                                        <i class="fas fa-exclamation-circle me-1"></i> Izin
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="status" id="status_sakit"
                                        value="Sakit">
                                    <label class="btn btn-outline-info w-100" for="status_sakit">
                                        <i class="fas fa-notes-medical me-1"></i> Sakit
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="status" id="status_alpha"
                                        value="Alpha">
                                    <label class="btn btn-outline-danger w-100" for="status_alpha">
                                        <i class="fas fa-times-circle me-1"></i> Alpha
                                    </label>
                                </div>
                                <div class="col-12">
                                    <input type="radio" class="btn-check" name="status" id="status_terlambat"
                                        value="Terlambat">
                                    <label class="btn btn-outline-secondary w-100" for="status_terlambat">
                                        <i class="fas fa-clock me-1"></i> Terlambat
                                    </label>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block" id="error_status"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="btnSimpanPresensi">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Setup CSRF token untuk semua AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Base URL untuk rute presensi pembimbing
            const pembimbingPresensiBase = "{{ url('pembimbing/presensi') }}";

            const table = $('#presensiTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "›",
                        previous: "‹"
                    },
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian"
                },
                dom: 't',
                scrollX: false,
                autoWidth: false,
                order: [
                    [2, 'asc']
                ]
            });

            // Custom search input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Update info display
            table.on('draw', function() {
                const info = table.page.info();
                $('#tableInfo').html(
                    'Menampilkan ' + (info.start + 1) + ' sampai ' + info.end + ' dari ' + info
                    .recordsTotal + ' data'
                );
            });

            // Initial info display
            const info = table.page.info();
            $('#tableInfo').html(
                'Menampilkan ' + (info.start + 1) + ' sampai ' + info.end + ' dari ' + info.recordsTotal +
                ' data'
            );

            // ============ EDIT PRESENSI FUNCTIONALITY ============

            // Buka modal dan load data presensi
            $(document).on('click', '.btn-edit-presensi', function() {
                const presensiId = $(this).data('id');
                $('#presensi_id').val(presensiId);

                console.log('Edit presensi ID:', presensiId); // Debug

                // Reset form dan error messages
                $('#editPresensiForm')[0].reset();
                $('.invalid-feedback').hide().text('');
                $('.is-invalid').removeClass('is-invalid');

                const presensiId = $(this).data('id');
                const tanggal = $(this).data('tanggal');
                const pelajarId = $(this).data('pelajar-id');
                const pelajarNama = $(this).data('pelajar-nama');

                // Clear previous form data
                $('#editPresensiForm')[0].reset();
                $('.invalid-feedback').hide().text('');
                $('.is-invalid').removeClass('is-invalid');

                // Fill in modal info
                $('#modal_pelajar_nama').text(pelajarNama);
                $('#modal_tanggal').text(tanggal);

                // Set hidden fields
                $('#presensi_id').val(presensiId);
                $('#pelajar_id').val(pelajarId);
                $('#tanggal').val(tanggal);

                if (presensiId) {
                    // If existing presensi, load via AJAX
                    $.ajax({
                        url: `${pembimbingPresensiBase}/${presensiId}/data`,
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                const data = response.data;
                                $(`input[name="status"][value="${data.status}"]`).prop(
                                    'checked', true);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading data:', xhr.responseText);
                            alert('Gagal memuat data presensi');
                        }
                    });
                } else {
                    // For new presensi, set default "Hadir" status
                    $('#status_hadir').prop('checked', true);
                }
            });

            // Simpan perubahan presensi
            $('#btnSimpanPresensi').on('click', function(e) {
                e.preventDefault();

                const presensiId = $('#presensi_id').val();
                const status = $('input[name="status"]:checked').val();

                console.log('Saving presensi:', {
                    presensiId,
                    status,
                }); // Debug

                // Validasi
                if (!status) {
                    $('#error_status').show().text('Status presensi wajib dipilih');
                    return;
                }

                // Reset error messages
                $('.invalid-feedback').hide().text('');
                $('.is-invalid').removeClass('is-invalid');

                // Disable tombol dan tampilkan loading
                $(this).prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                // Kirim data via AJAX
                const isNew = !presensiId;
                const url = isNew ? pembimbingPresensiBase :
                    `${pembimbingPresensiBase}/${presensiId}/update`;
                const method = isNew ? 'POST' : 'PUT';

                // Prepare data
                const formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: status
                };

                // Add additional fields for new records
                if (isNew) {
                    formData.pelajar_id = $('#pelajar_id').val();
                    formData.tanggal = $('#tanggal').val();
                } else {
                    formData._method = 'PUT';
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log('Update response:', response); // Debug

                        if (response.success) {
                            // Update badge status di tabel
                            const statusConfig = {
                                'Hadir': {
                                    class: 'success',
                                    icon: 'check'
                                },
                                'Izin': {
                                    class: 'warning',
                                    icon: 'exclamation'
                                },
                                'Sakit': {
                                    class: 'info',
                                    icon: 'notes-medical'
                                },
                                'Alpha': {
                                    class: 'danger',
                                    icon: 'times'
                                },
                                'Terlambat': {
                                    class: 'secondary',
                                    icon: 'clock'
                                },
                                'Tepat Waktu': {
                                    class: 'success',
                                    icon: 'check'
                                }
                            };

                            const config = statusConfig[status] || {
                                class: 'secondary',
                                icon: 'question'
                            };
                            const badgeHtml =
                                `<i class="fas fa-${config.icon} me-1"></i>${status}`;

                            $(`#badge-${presensiId}`)
                                .removeClass(
                                    'bg-success bg-warning bg-info bg-danger bg-secondary')
                                .addClass(`bg-${config.class}`)
                                .html(badgeHtml);

                            // Tutup modal
                            $('#editPresensiModal').modal('hide');

                            // Tampilkan notifikasi sukses
                            const successAlert = `
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                                <span>${response.message}</span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                            $('.container.my-5').prepend(successAlert);

                            // Auto hide alert setelah 3 detik
                            setTimeout(function() {
                                $('.alert-success').fadeOut('slow', function() {
                                    $(this).remove();
                                });
                            }, 3000);

                            // Reload halaman untuk update statistik
                            setTimeout(function() {
                                location.reload();
                            }, 1500);

                            // Reset tombol
                            $('#btnSimpanPresensi').prop('disabled', false).html(
                                '<i class="fas fa-save me-1"></i> Simpan Perubahan');
                        } else {
                            alert(response.message || 'Gagal menyimpan perubahan');
                            $('#btnSimpanPresensi').prop('disabled', false).html(
                                '<i class="fas fa-save me-1"></i> Simpan Perubahan');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving:', xhr.responseText); // Debug

                        let errorMessage = 'Terjadi kesalahan saat menyimpan data';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        alert(errorMessage);

                        // Enable tombol kembali
                        $('#btnSimpanPresensi').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i> Simpan Perubahan');
                    }
                });
            });

            // Reset form saat modal ditutup
            $('#editPresensiModal').on('hidden.bs.modal', function() {
                $('#editPresensiForm')[0].reset();
                $('.invalid-feedback').hide().text('');
                $('.is-invalid').removeClass('is-invalid');
                $('#btnSimpanPresensi').prop('disabled', false).html(
                    '<i class="fas fa-save me-1"></i> Simpan Perubahan');
            });
        });
    </script>
@endpush

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

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Custom styling for radio buttons */
        .btn-check:checked+.btn-outline-success {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-check:checked+.btn-outline-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        .btn-check:checked+.btn-outline-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-check:checked+.btn-outline-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-check:checked+.btn-outline-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        /* Modal animation */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        /* Button hover effects */
        .btn-edit-presensi:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }

        /* Bootstrap Icons */
        .bi {
            vertical-align: middle;
        }
    </style>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush
