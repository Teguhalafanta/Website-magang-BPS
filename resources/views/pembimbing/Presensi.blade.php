@extends('kerangka.master')

@section('title', 'Presensi Bimbingan')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="bg-primary rounded p-2">
                            <i class="fas fa-clipboard-list text-white fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Daftar Presensi Peserta Bimbingan</h2>
                        <p class="text-muted mb-0">Monitoring kehadiran dan aktivitas bimbingan pelajar</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Alert Error -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (!isset($selectedPelajarId) || !$selectedPelajarId)
            <!-- Presensi Hari Ini Card -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-primary text-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="fas fa-calendar-day me-2"></i>Presensi Hari Ini
                        </h5>
                        <span class="badge bg-light text-primary">
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    @php
                        $hariIni = \Carbon\Carbon::now()->format('Y-m-d');
                        $presensiHariIni = $presensis->where('tanggal', $hariIni);
                    @endphp

                    @if ($presensiHariIni->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Presensi Hari Ini</h5>
                            <p class="text-muted small">Belum ada pelajar yang melakukan presensi pada hari ini</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach ($presensiHariIni as $presensi)
                                @php
                                    $pelajar = $pelajars->firstWhere('id', $presensi->pelajar_id);
                                    $statusClass = match (strtolower($presensi->status)) {
                                        'hadir', 'tepat waktu' => 'success',
                                        'izin' => 'warning',
                                        'sakit' => 'info',
                                        'alpha' => 'danger',
                                        'terlambat' => 'secondary',
                                        default => 'secondary',
                                    };
                                    $statusIcon = match (strtolower($presensi->status)) {
                                        'hadir', 'tepat waktu' => 'fa-check-circle',
                                        'izin' => 'fa-exclamation-circle',
                                        'sakit' => 'fa-notes-medical',
                                        'alpha' => 'fa-times-circle',
                                        'terlambat' => 'fa-clock',
                                        default => 'fa-circle',
                                    };
                                @endphp
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar-circle bg-{{ $statusClass }} bg-opacity-10 me-3">
                                                    <i class="fas {{ $statusIcon }} text-{{ $statusClass }} fs-4"></i>
                                                </div>
                                                <div class="grow">
                                                    <h6 class="mb-1 fw-bold">{{ $pelajar->nama ?? 'N/A' }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-building me-1"></i>{{ $pelajar->asal_sekolah ?? '-' }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small text-muted">
                                                    <i class="fas fa-sign-in-alt me-1"></i>Datang:
                                                </span>
                                                <span class="badge bg-success">{{ $presensi->waktu_datang }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small text-muted">
                                                    <i class="fas fa-sign-out-alt me-1"></i>Pulang:
                                                </span>
                                                @php
                                                    $statusLower = strtolower($presensi->status ?? '');
                                                @endphp
                                                @if ($presensi->waktu_pulang)
                                                    <span class="badge bg-primary">{{ $presensi->waktu_pulang }}</span>
                                                @else
                                                    {{-- Show "Belum Pulang" only if peserta memang hadir/terlambat/tepat waktu --}}
                                                    @if (in_array($statusLower, ['hadir', 'tepat waktu', 'terlambat']))
                                                        <span class="badge bg-warning text-dark">Belum Pulang</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="small text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Status:
                                                </span>
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ ucfirst($presensi->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Statistik Hari Ini -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-chart-pie me-2"></i>Ringkasan Hari Ini
                            </h6>
                            <div class="row text-center g-2">
                                @php
                                    $totalHadirHariIni = $presensiHariIni
                                        ->whereIn('status', ['Hadir', 'Tepat Waktu'])
                                        ->count();
                                    $totalTerlambatHariIni = $presensiHariIni->where('status', 'Terlambat')->count();
                                    $totalIzinHariIni = $presensiHariIni->where('status', 'Izin')->count();
                                    $totalSakitHariIni = $presensiHariIni->where('status', 'Sakit')->count();
                                @endphp
                                <div class="col-3">
                                    <div class="p-3 bg-success bg-opacity-10 rounded">
                                        <div class="text-success fw-bold fs-4">{{ $totalHadirHariIni }}</div>
                                        <div class="small text-muted">Hadir</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="p-3 bg-secondary bg-opacity-10 rounded">
                                        <div class="text-secondary fw-bold fs-4">{{ $totalTerlambatHariIni }}</div>
                                        <div class="small text-muted">Terlambat</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                                        <div class="text-warning fw-bold fs-4">{{ $totalIzinHariIni }}</div>
                                        <div class="small text-muted">Izin</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="p-3 bg-info bg-opacity-10 rounded">
                                        <div class="text-info fw-bold fs-4">{{ $totalSakitHariIni }}</div>
                                        <div class="small text-muted">Sakit</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <!-- Card Header -->
            <div class="card-header bg-primary text-white py-2 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="fas fa-table me-2"></i>Tabel Presensi Lengkap
                    </h5>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card-body bg-light border-bottom">
                <div class="row g-5 align-items-end">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('pembimbing.presensi') }}" id="filterForm">
                            <label for="pelajar_id" class="form-label fw-semibold mb-2">
                                <i class="fas fa-user me-1"></i>Filter Berdasarkan Pelajar
                            </label>
                            <select name="pelajar_id" id="pelajar_id" class="form-select border-2" onchange="this.form.submit()">
                                <option value="">Semua Pelajar</option>
                                @foreach ($pelajars as $pelajar)
                                    <option value="{{ $pelajar->id }}"
                                        {{ isset($selectedPelajarId) && $selectedPelajarId == $pelajar->id ? 'selected' : '' }}>
                                        {{ $pelajar->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    @if (isset($selectedPelajarId) && $selectedPelajarId)
                        @php
                            $selectedPelajar = $pelajars->firstWhere('id', $selectedPelajarId);
                            $bulanDipilih = request('bulan', now()->format('Y-m'));
                        @endphp
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('pembimbing.presensi') }}" class="d-flex flex-column">
                                <input type="hidden" name="pelajar_id" value="{{ $selectedPelajarId }}">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-calendar-alt me-1"></i>Filter Bulan
                                </label>
                                <input type="month" name="bulan" class="form-control border-2"
                                    value="{{ $bulanDipilih }}" onchange="this.form.submit()">
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">
                @if (isset($selectedPelajarId) && $selectedPelajarId)
                    @php
                        $selectedPelajar = $pelajars->firstWhere('id', $selectedPelajarId);
                        $pelajarPresensi = $presensis->where('pelajar_id', $selectedPelajarId);

                        // Ambil bulan yang dipilih
                        $bulanDipilih = request('bulan', now()->format('Y-m'));
                        $tahun = (int) substr($bulanDipilih, 0, 4);
                        $bulan = (int) substr($bulanDipilih, 5, 2);

                        // Generate tanggal untuk bulan yang dipilih
                        $mulai = \Carbon\Carbon::parse($selectedPelajar->rencana_mulai ?? now());
                        $selesai = \Carbon\Carbon::parse($selectedPelajar->rencana_selesai ?? now());

                        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                        $semuaTanggal = [];

                        for ($i = 1; $i <= $jumlahHari; $i++) {
                            $tanggal = \Carbon\Carbon::createFromDate($tahun, $bulan, $i);
                            if ($tanggal->lt($mulai) || $tanggal->gt($selesai)) {
                                continue;
                            }
                            $semuaTanggal[] = $tanggal->format('Y-m-d');
                        }

                        $presensiMap = $pelajarPresensi->keyBy('tanggal');
                    @endphp

                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm table-bordered table-hover text-center align-middle small">
                            <thead class="table-primary sticky-top">
                                <tr>
                                    <th class="py-2">No</th>
                                    <th class="py-2">Tanggal</th>
                                    <th class="py-2">Hari</th>
                                    <th class="py-2">Datang</th>
                                    <th class="py-2">Pulang</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse (array_reverse($semuaTanggal) as $tanggal)
                                    @php
                                        $presensi = $presensiMap->get($tanggal);
                                        $carbonDate = \Carbon\Carbon::parse($tanggal);
                                        $namaHari = $carbonDate->locale('id')->isoFormat('dddd');
                                        $isWeekend = $carbonDate->isWeekend();
                                        $isFuture = $carbonDate->isFuture();
                                    @endphp
                                    <tr class="{{ $isWeekend ? 'table-light' : '' }}"
                                        id="row-{{ $presensi->id ?? '' }}">
                                        <td class="py-2">{{ $no++ }}</td>
                                        <td class="py-2">{{ $carbonDate->format('d/m/y') }}</td>
                                        <td class="py-2">
                                            @if ($isWeekend)
                                                <small class="badge bg-secondary">{{ substr($namaHari, 0, 3) }}</small>
                                            @else
                                                <small class="text-muted">{{ substr($namaHari, 0, 3) }}</small>
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            @if ($presensi)
                                                <small class="badge bg-success">{{ $presensi->waktu_datang }}</small>
                                            @elseif($isFuture)
                                                <small class="text-muted">-</small>
                                            @elseif($isWeekend)
                                                <small class="badge bg-secondary">Libur</small>
                                            @else
                                                {{-- show '-' when peserta tidak absen (dibuat oleh pembimbing atau alfa/izin/sakit) --}}
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            @php $statusLower = strtolower($presensi->status ?? ''); @endphp
                                            @if ($presensi && $presensi->waktu_pulang)
                                                <small class="badge bg-primary">{{ $presensi->waktu_pulang }}</small>
                                            @elseif($presensi && !$presensi->waktu_pulang)
                                                {{-- show "Belum" only if attendee actually came --}}
                                                @if (in_array($statusLower, ['hadir', 'tepat waktu', 'terlambat']))
                                                    <small class="badge bg-warning text-dark">Belum</small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            @elseif($isFuture)
                                                <small class="text-muted">-</small>
                                            @elseif($isWeekend)
                                                <small class="badge bg-secondary">Libur</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            @if ($presensi)
                                                @php
                                                    $statusClass = match (strtolower($presensi->status)) {
                                                        'hadir', 'tepat waktu' => 'success',
                                                        'izin' => 'warning',
                                                        'sakit' => 'info',
                                                        'alpha' => 'danger',
                                                        'terlambat' => 'secondary',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <small class="badge bg-{{ $statusClass }}"
                                                    id="badge-{{ $presensi->id }}">
                                                    {{ ucfirst($presensi->status) }}
                                                </small>
                                            @elseif ($isFuture)
                                                <small class="badge bg-secondary">Belum Tiba</small>
                                            @elseif ($isWeekend)
                                                <small class="badge bg-secondary">Libur</small>
                                            @else
                                                <small class="badge bg-danger">Alfa</small>
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            @if (!$isWeekend && !$isFuture)
                                                <button type="button"
                                                    class="btn btn-sm btn-warning btn-edit-presensi text-dark"
                                                    data-id="{{ $presensi->id ?? '' }}"
                                                    data-tanggal="{{ $tanggal }}"
                                                    data-pelajar-id="{{ $selectedPelajarId }}"
                                                    data-pelajar-nama="{{ $selectedPelajar->nama ?? '' }}"
                                                    data-bs-toggle="modal" data-bs-target="#editPresensiModal"
                                                    title="{{ $presensi ? 'Edit Presensi' : 'Isi Presensi' }}">
                                                    <i class="fas fa-{{ $presensi ? 'pen' : 'plus' }}"></i>
                                                </button>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <p class="mb-0">Tidak ada data presensi untuk bulan ini</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- STATISTIK BULAN INI --}}
                    @php
                        $presensiFilterBulan = $pelajarPresensi
                            ->filter(function ($p) use ($bulanDipilih) {
                                return substr($p->tanggal, 0, 7) == $bulanDipilih;
                            })
                            ->unique('tanggal');

                        // Define Tepat Waktu and Terlambat explicitly, then compute Hadir as their sum
                        $totalTepat = $presensiFilterBulan->where('status', 'Tepat Waktu')->count();
                        $totalTerlambat = $presensiFilterBulan->where('status', 'Terlambat')->count();
                        $totalHadir = $totalTepat + $totalTerlambat;
                        $totalIzin = $presensiFilterBulan->where('status', 'Izin')->count();
                        $totalSakit = $presensiFilterBulan->where('status', 'Sakit')->count();

                        $hariKerjaLewat = 0;
                        foreach ($semuaTanggal as $tgl) {
                            $date = \Carbon\Carbon::parse($tgl);
                            if (!$date->isWeekend() && $date->isPast()) {
                                $hariKerjaLewat++;
                            }
                        }

                        $totalAlfa = max(0, $hariKerjaLewat - ($totalHadir + $totalIzin + $totalSakit));
                    @endphp

                    <div class="card-body border-top">
                        <div class="small fw-bold mb-3">
                            <i class="fas fa-chart-bar me-1"></i>Statistik Bulan
                            {{ \Carbon\Carbon::parse($bulanDipilih)->locale('id')->isoFormat('MMMM Y') }}
                        </div>
                        <div class="row text-center g-2">
                            <div class="col-4 col-md-2">
                                <div class="p-2 bg-success bg-opacity-10 rounded">
                                    <div class="text-success fw-bold fs-5">{{ $totalHadir }}</div>
                                    <div class="small text-muted">Hadir</div>
                                </div>
                            </div>
                            <div class="col-4 col-md-2">
                                <div class="p-2 bg-primary bg-opacity-10 rounded">
                                    <div class="text-primary fw-bold fs-5">{{ $totalTepat }}</div>
                                    <div class="small text-muted">Tepat Waktu</div>
                                </div>
                            </div>
                            <div class="col-4 col-md-2">
                                <div class="p-2 bg-warning bg-opacity-10 rounded">
                                    <div class="text-warning fw-bold fs-5">{{ $totalTerlambat }}</div>
                                    <div class="small text-muted">Terlambat</div>
                                </div>
                            </div>
                            <div class="col-4 col-md-2">
                                <div class="p-2 bg-info bg-opacity-10 rounded">
                                    <div class="text-info fw-bold fs-5">{{ $totalSakit }}</div>
                                    <div class="small text-muted">Sakit</div>
                                </div>
                            </div>
                            <div class="col-4 col-md-2">
                                <div class="p-2 bg-warning bg-opacity-10 rounded">
                                    <div class="fw-bold fs-5" style="color: #ffc107;">{{ $totalIzin }}</div>
                                    <div class="small text-muted">Izin</div>
                                </div>
                            </div>
                            <div class="col-4 col-md-2">
                                <div class="p-2 bg-danger bg-opacity-10 rounded">
                                    <div class="text-danger fw-bold fs-5">{{ $totalAlfa }}</div>
                                    <div class="small text-muted">Alfa</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-5 text-center">
                        <i class="fas fa-user fa-4x mb-3 opacity-50 text-muted"></i>
                        <h5 class="text-muted mb-2">Pilih Pelajar</h5>
                        <p class="text-muted small mb-0">Silakan pilih pelajar dari dropdown di atas untuk melihat data
                            presensi lengkap</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Edit Presensi -->
    <div class="modal fade" id="editPresensiModal" tabindex="-1" aria-labelledby="editPresensiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
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
        $(function() {
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const pembimbingPresensiBase = "{{ url('pembimbing/presensi') }}";

            // Open modal and load data (supports create and edit)
            $(document).on('click', '.btn-edit-presensi', function() {
                const presensiId = $(this).data('id') || '';
                const tanggal = $(this).data('tanggal') || $(this).attr('data-tanggal') || '';
                const pelajarId = $(this).data('pelajar-id') || $(this).attr('data-pelajar-id') || '';
                const pelajarNama = $(this).data('pelajar-nama') || $(this).attr('data-pelajar-nama') || '';

                // reset
                $('#editPresensiForm')[0].reset();
                $('#error_status').hide().text('');

                // fill hidden fields
                $('#presensi_id').val(presensiId);
                $('#pelajar_id').val(pelajarId);
                $('#tanggal').val(tanggal);

                // fill display
                $('#modal_pelajar_nama').text(pelajarNama || '');
                $('#modal_tanggal').text(tanggal ? tanggal : '');

                if (presensiId) {
                    // load existing
                    $.get(`${pembimbingPresensiBase}/${presensiId}/data`)
                        .done(function(resp) {
                            if (resp.success && resp.data) {
                                const status = resp.data.status || '';
                                $(`input[name="status"][value="${status}"]`).prop('checked', true);
                                $('#modal_pelajar_nama').text(resp.data.pelajar_nama || pelajarNama ||
                                    '');
                                $('#modal_tanggal').text(resp.data.tanggal || tanggal || '');
                            }
                        })
                        .fail(function(xhr) {
                            console.error('Gagal memuat data presensi', xhr.responseText);
                            alert('Gagal memuat data presensi');
                        });
                } else {
                    // new presensi -> default status Hadir
                    $('#status_hadir').prop('checked', true);
                }
            });

            // Save changes
            $('#btnSimpanPresensi').on('click', function(e) {
                e.preventDefault();
                const presensiId = $('#presensi_id').val();
                const status = $('input[name="status"]:checked').val();

                if (!status) {
                    $('#error_status').show().text('Status presensi wajib dipilih');
                    return;
                }

                let url, data;
                if (presensiId) {
                    url = `${pembimbingPresensiBase}/${presensiId}/update`;
                    data = {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'PUT',
                        status: status
                    };
                } else {
                    // create new presensi by pembimbing
                    url = `${pembimbingPresensiBase}/create-by-pembimbing`;
                    data = {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        pelajar_id: $('#pelajar_id').val(),
                        tanggal: $('#tanggal').val(),
                        status: status
                    };
                }

                $(this).prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                $.post(url, data)
                    .done(function(resp) {
                        if (resp.success) {
                            // update badge text if present
                            const badge = $(`#badge-${resp.data.id}`);
                            if (badge.length) {
                                // simple mapping
                                const map = {
                                    'Hadir': 'bg-success',
                                    'Izin': 'bg-warning',
                                    'Sakit': 'bg-info',
                                    'Alpha': 'bg-danger',
                                    'Terlambat': 'bg-secondary',
                                    'Tepat Waktu': 'bg-success'
                                };
                                const cls = map[resp.data.status] || 'bg-secondary';
                                badge.removeClass(
                                        'bg-success bg-warning bg-info bg-danger bg-secondary')
                                    .addClass(cls).text(resp.data.status);
                            }

                            $('#editPresensiModal').modal('hide');
                            const alertHtml =
                                `<div class="alert alert-success alert-dismissible fade show" role="alert">${resp.message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
                            $('.container-fluid.py-4').prepend(alertHtml);
                            setTimeout(function() {
                                location.reload();
                            }, 1200);
                        } else {
                            alert(resp.message || 'Gagal menyimpan perubahan');
                        }
                    })
                    .fail(function(xhr) {
                        console.error('Error saving presensi', xhr.responseText);
                        let msg = 'Terjadi kesalahan saat menyimpan';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON
                            .message;
                        alert(msg);
                    })
                    .always(function() {
                        $('#btnSimpanPresensi').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i> Simpan Perubahan');
                    });
            });

            // reset when modal closed
            $('#editPresensiModal').on('hidden.bs.modal', function() {
                $('#editPresensiForm')[0].reset();
                $('#error_status').hide().text('');
                $('#btnSimpanPresensi').prop('disabled', false).html(
                    '<i class="fas fa-save me-1"></i> Simpan Perubahan');
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* BPS Professional Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card-header {
            font-weight: 600;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .table td {
            font-size: 0.85rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
            font-size: 0.75rem;
        }
        
        /* Avatar Circle for Today's Attendance */
        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
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
        
        /* Button hover effects */
        .btn-edit-presensi {
            transition: all 0.2s ease;
        }
        
        .btn-edit-presensi:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Force yellow styling for action buttons to match request */
        .btn-edit-presensi.btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        
        .btn-edit-presensi.btn-warning:hover,
        .btn-edit-presensi.btn-warning:focus {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529;
        }
        
        /* Improve action button visibility */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Professional table styling */
        .table-primary {
            background-color: #0d6efd;
            color: white;
        }
        
        .table-primary th {
            border-color: #0d6efd;
            color: white;
        }
        
        /* Form styling */
        .form-select, .form-control {
            border-radius: 0.375rem;
        }
        
        /* Alert styling */
        .alert {
            border-radius: 0.5rem;
        }
    </style>
@endpush