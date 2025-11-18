@extends('kerangka.master')

@section('content')
    <div class="container py-2" style="max-width: 800px;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-bps-primary">SISTEM PRESENSI MAGANG</h3>
            <p class="fw-bold text-muted small">Badan Pusat Statistik</p>
        </div>

        {{-- ALERT JIKA ADA PESAN --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- LOGIKA PRESENSI --}}
        @php
            $pelajar = auth()->user()->pelajar;
            $pelajarPresensi = $pelajar->presensis ?? collect([]);
            $hariIni = now()->toDateString();
            $presensiHariIni = $pelajarPresensi->where('tanggal', $hariIni)->first();
            $tanggalSelesai = \Carbon\Carbon::parse($pelajar->rencana_selesai ?? now());
            $magangSelesai = now()->gt($tanggalSelesai);
        @endphp

        {{-- CARD PRESENSI UTAMA --}}
        <div class="card mb-4 shadow-sm border-0 bps-card">
            <div class="card-header bps-card-header py-3">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i>Presensi Harian
                </h5>
            </div>
            <div class="card-body text-center p-4">
                <div class="small mb-3">
                    <span class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</span> •
                    <span>Waktu: <strong id="jamSekarang" class="text-bps-primary"></strong></span>
                </div>

                {{-- STATUS MAGANG SELESAI --}}
                @if ($magangSelesai)
                    <div class="alert alert-secondary text-center p-4">
                        <i class="bi bi-award-fill fs-3 mb-2 text-bps-primary"></i>
                        <h5 class="fw-bold mb-2">Magang Telah Selesai</h5>
                        <p class="mb-0">Terima kasih atas kontribusi Anda. Anda tidak dapat melakukan presensi lagi.</p>
                    </div>

                    {{-- BELUM ABSEN --}}
                @elseif (!$presensiHariIni)
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <button type="button" id="btnTapMasuk"
                            class="btn-bps btn-bps-primary d-flex flex-row align-items-center justify-content-center p-2"
                            onclick="tapMasuk()">
                            <div class="col-auto">
                                <i class="bi bi-box-arrow-in-right fs-2 mb-2"></i>
                            </div>
                            <span>Absen Masuk</span>
                        </button>
                    </div>
                    <p class="text-muted small mt-2">Silakan tekan tombol di atas untuk melakukan presensi masuk</p>

                    <form id="formMasuk" action="{{ route('pelajar.presensi.store') }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="jam_client" id="jamMasukInput">
                    </form>

                    {{-- SUDAH MASUK, BELUM PULANG --}}
                @elseif($presensiHariIni && !$presensiHariIni->waktu_pulang)
                    <div class="alert alert-success mb-3">
                        <h6 class="mb-2"><i class="bi bi-check-circle-fill"></i> Sudah Absen Masuk</h6>
                        <p class="mb-0">Jam Masuk: <strong>{{ $presensiHariIni->waktu_datang }}</strong></p>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <button type="button" id="btnTapPulang"
                            class="btn-bps btn-bps-warning d-flex flex-column align-items-center justify-content-center p-3"
                            onclick="tapPulang()">
                            <i class="bi bi-box-arrow-right fs-2 mb-2"></i>
                            <span>Absen Pulang</span>
                        </button>
                    </div>
                    <p class="text-muted small mt-2">Silakan tekan tombol di atas untuk melakukan presensi pulang</p>

                    <form id="formPulang" action="{{ route('pelajar.presensi.update', $presensiHariIni->id) }}"
                        method="POST" class="d-none">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="jam_client" id="jamPulangInput">
                    </form>

                    {{-- SUDAH SELESAI --}}
                @else
                    <div class="alert alert-info">
                        <h5 class="mb-3"><i class="bi bi-check-all"></i> Presensi Hari Ini Selesai</h5>
                        <div class="row justify-content-center">
                            <div class="col-md-5 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-1">Jam Masuk</h6>
                                        <h4 class="text-success mb-0">{{ $presensiHariIni->waktu_datang }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-1">Jam Pulang</h6>
                                        <h4 class="text-bps-primary mb-0">{{ $presensiHariIni->waktu_pulang }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if ($presensiHariIni->status == 'Terlambat')
                                <span class="badge bg-bps-secondary fs-6">{{ $presensiHariIni->status }}</span>
                            @else
                                <span class="badge bg-success fs-6">{{ $presensiHariIni->status }}</span>
                            @endif
                        </div>

                        {{-- TOMBOL UPDATE JAM PULANG --}}
                        <div class="mt-4 pt-3 border-top">
                            <p class="text-muted small mb-2">
                                <i class="bi bi-info-circle"></i> Perlu update jam pulang?
                            </p>
                            <button type="button" id="btnUpdatePulang" class="btn btn-warning btn-sm px-4 py-2"
                                onclick="updateJamPulang()">
                                <i class="bi bi-arrow-clockwise me-1"></i> Update Jam Pulang
                            </button>
                        </div>
                    </div>

                    <form id="formPulang" action="{{ route('pelajar.presensi.update', $presensiHariIni->id) }}"
                        method="POST" class="d-none">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="jam_client" id="jamPulangInput">
                    </form>
                @endif

                {{-- TOMBOL TOGGLE RIWAYAT --}}
                <div class="mt-4 pt-3 border-top">
                    <button class="btn btn-outline-bps-primary w-100" type="button" data-bs-toggle="collapse"
                        data-bs-target="#riwayatCollapse" aria-expanded="false" aria-controls="riwayatCollapse">
                        <i class="bi bi-clock-history me-2"></i>
                        <span class="riwayat-text">Lihat Riwayat Presensi</span>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- COLLAPSE RIWAYAT PRESENSI --}}
        <div class="collapse" id="riwayatCollapse">
            {{-- FILTER BULAN --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-bps-primary">
                    <i class="bi bi-calendar-month me-2"></i>Riwayat Presensi
                </h5>
                <form method="GET" action="{{ route('pelajar.presensi.index') }}" class="d-flex">
                    <div class="input-group input-group-sm" style="max-width: 200px;">
                        <span class="input-group-text bg-bps-primary text-white">
                            <i class="bi bi-filter"></i>
                        </span>
                        <input type="month" name="bulan" class="form-control"
                            value="{{ request('bulan', now()->format('Y-m')) }}" onchange="this.form.submit()">
                    </div>
                </form>
            </div>

            {{-- LOGIKA GENERATE TANGGAL --}}
            @php
                $mulai = \Carbon\Carbon::parse($pelajar->rencana_mulai ?? now());
                $selesai = \Carbon\Carbon::parse($pelajar->rencana_selesai ?? now());

                $bulanDipilih = request('bulan', now()->format('Y-m'));
                $tahun = (int) substr($bulanDipilih, 0, 4);
                $bulan = (int) substr($bulanDipilih, 5, 2);

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

            {{-- TABEL RIWAYAT PRESENSI --}}
            <div class="card shadow-sm border-0 bps-card mb-4">
                <div class="card-header bps-card-header py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Riwayat Bulan
                        {{ \Carbon\Carbon::parse($bulanDipilih)->locale('id')->isoFormat('MMMM Y') }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm table-hover text-center align-middle small mb-0">
                            <thead class="bps-table-header">
                                <tr class="text-center" style="font-size: 0.8rem;">
                                    <th class="py-2">No</th>
                                    <th class="py-2">Tanggal</th>
                                    <th class="py-2">Hari</th>
                                    <th class="py-2">Datang</th>
                                    <th class="py-2">Pulang</th>
                                    <th class="py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach (array_reverse($semuaTanggal) as $tanggal)
                                    @php
                                        $presensi = $presensiMap->get($tanggal);
                                        $carbonDate = \Carbon\Carbon::parse($tanggal);
                                        $namaHari = $carbonDate->locale('id')->isoFormat('dddd');
                                        $isWeekend = $carbonDate->isWeekend();
                                        $isFuture = $carbonDate->isFuture();
                                    @endphp
                                    <tr class="text-center {{ $isWeekend ? 'table-light' : '' }}">
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
                                                <small class="badge bg-danger">Tidak Hadir</small>
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            @if ($presensi && $presensi->waktu_pulang)
                                                <small class="badge bg-bps-primary">{{ $presensi->waktu_pulang }}</small>
                                            @elseif($presensi && !$presensi->waktu_pulang)
                                                <small class="badge bg-warning text-dark">Belum</small>
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
                                                @switch(strtolower($presensi->status))
                                                    @case('hadir')
                                                        <small class="badge bg-success">Hadir</small>
                                                    @break

                                                    @case('izin')
                                                        <small class="badge bg-warning text-dark">Izin</small>
                                                    @break

                                                    @case('sakit')
                                                        <small class="badge bg-info text-dark">Sakit</small>
                                                    @break

                                                    @case('terlambat')
                                                        <small class="badge bg-bps-secondary">Terlambat</small>
                                                    @break

                                                    @case('alpha')
                                                        <small class="badge bg-danger">Alfa</small>
                                                    @break

                                                    @default
                                                        <small class="badge bg-light text-dark"></small>
                                                @endswitch
                                            @elseif ($isFuture)
                                                <small class="badge bg-secondary">Belum Tiba</small>
                                            @elseif ($isWeekend)
                                                <small class="badge bg-secondary">Libur</small>
                                            @else
                                                <small class="badge bg-danger">Alfa</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- STATISTIK BULAN INI --}}
            @php
                $bulanIni = now()->format('Y-m');
                $isCurrentMonth = request('bulan', $bulanIni) == $bulanIni;

                $presensiFilterBulan = $pelajarPresensi
                    ->filter(function ($p) use ($bulanDipilih) {
                        return substr($p->tanggal, 0, 7) == $bulanDipilih;
                    })
                    ->unique('tanggal');

                // Hitung total berdasarkan status
                $totalTepat = $presensiFilterBulan->where('status', 'Tepat Waktu')->count();
                $totalTerlambat = $presensiFilterBulan->where('status', 'Terlambat')->count();
                $totalHadir = $totalTepat + $totalTerlambat;
                $totalIzin = $presensiFilterBulan->where('status', 'Izin')->count();
                $totalSakit = $presensiFilterBulan->where('status', 'Sakit')->count();

                // Hitung hari kerja yang sudah lewat
                $hariKerjaLewat = 0;
                foreach ($semuaTanggal as $tgl) {
                    $date = \Carbon\Carbon::parse($tgl);
                    if (!$date->isWeekend() && $date->isPast()) {
                        $hariKerjaLewat++;
                    }
                }

                // Hitung Alfa berdasarkan hari kerja - (hadir + izin + sakit)
                $totalAlfa = max(0, $hariKerjaLewat - ($totalHadir + $totalIzin + $totalSakit));
            @endphp

            {{-- CARD STATISTIK --}}
            <div class="card shadow-sm border-0 bps-card">
                <div class="card-header bps-card-header py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart-line me-2"></i>Statistik Bulan
                        {{ \Carbon\Carbon::parse($bulanDipilih)->locale('id')->isoFormat('MMMM Y') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center g-3">
                        <div class="col-4 col-md-2">
                            <div class="p-2 bg-success bg-opacity-10 rounded border">
                                <div class="text-success fw-bold fs-5">{{ $totalHadir }}</div>
                                <div class="small text-muted">Hadir</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="p-2 bg-bps-light bg-opacity-10 rounded border">
                                <div class="text-bps-primary fw-bold fs-5">{{ $totalTepat }}</div>
                                <div class="small text-muted">Tepat Waktu</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="p-2 bg-warning bg-opacity-10 rounded border">
                                <div class="text-warning fw-bold fs-5">{{ $totalTerlambat }}</div>
                                <div class="small text-muted">Terlambat</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="p-2 bg-info bg-opacity-10 rounded border">
                                <div class="text-info fw-bold fs-5">{{ $totalSakit }}</div>
                                <div class="small text-muted">Sakit</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="p-2 bg-warning bg-opacity-10 rounded border">
                                <div class="fw-bold fs-5" style="color: #ffc107;">{{ $totalIzin }}</div>
                                <div class="small text-muted">Izin</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="p-2 bg-danger bg-opacity-10 rounded border">
                                <div class="text-danger fw-bold fs-5">{{ $totalAlfa }}</div>
                                <div class="small text-muted">Alfa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CUSTOM CSS BPS --}}
    <style>
        :root {
            --bps-primary: #0055a5;
            --bps-secondary: #e63946;
            --bps-light: #f8f9fa;
            --bps-dark: #343a40;
        }

        .text-bps-primary {
            color: var(--bps-primary) !important;
        }

        .text-bps-secondary {
            color: var(--bps-secondary) !important;
        }

        .bg-bps-primary {
            background-color: var(--bps-primary) !important;
        }

        .bg-bps-secondary {
            background-color: var(--bps-secondary) !important;
        }

        .btn-outline-bps-primary {
            color: var(--bps-primary);
            border-color: var(--bps-primary);
        }

        .btn-outline-bps-primary:hover {
            background-color: var(--bps-primary);
            color: white;
        }

        .bps-card {
            border-radius: 8px;
            overflow: hidden;
        }

        .bps-card-header {
            background-color: var(--bps-primary);
            color: white;
            border-bottom: none;
        }

        .bps-table-header {
            background-color: var(--bps-primary);
            color: white;
        }

        .bps-table-header th {
            border-bottom: none;
            font-weight: 600;
        }

        .btn-bps {
            padding: 15px 25px;
            border-radius: 8px;
            border: none;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            min-width: 160px;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-bps i {
            font-size: 24px;
        }

        .btn-bps-primary {
            background: linear-gradient(135deg, var(--bps-primary) 0%, #0077cc 100%);
        }

        .btn-bps-primary:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 85, 165, 0.3);
        }

        .btn-bps-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        }

        .btn-bps-warning:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 193, 7, 0.3);
        }

        .btn-bps:active:not(.disabled) {
            transform: translateY(0) !important;
        }

        .btn-bps.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        @keyframes pulse-bps {

            0%,
            100% {
                box-shadow: 0 4px 12px rgba(0, 85, 165, 0.3);
            }

            50% {
                box-shadow: 0 4px 20px rgba(0, 85, 165, 0.5);
            }
        }

        @keyframes pulse-warning {

            0%,
            100% {
                box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
            }

            50% {
                box-shadow: 0 4px 20px rgba(255, 193, 7, 0.5);
            }
        }

        .btn-bps-primary:not(.disabled) {
            animation: pulse-bps 2s infinite;
        }

        .btn-bps-warning:not(.disabled) {
            animation: pulse-warning 2s infinite;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 85, 165, 0.05);
        }

        .collapse {
            transition: all 0.3s ease;
        }
    </style>

    {{-- SCRIPT UNTUK TOGGLE RIWAYAT --}}
    <script>
        let sedangAbsenMasuk = false;

        function getCurrentTime() {
            const now = new Date();
            return now.toLocaleTimeString('en-GB', {
                hour12: false
            });
        }

        // Update jam setiap detik
        setInterval(() => {
            const jamElement = document.getElementById('jamSekarang');
            if (jamElement) {
                jamElement.textContent = getCurrentTime();
            }
        }, 1000);

        // Inisialisasi jam saat load
        document.addEventListener('DOMContentLoaded', function() {
            const jamElement = document.getElementById('jamSekarang');
            if (jamElement) {
                jamElement.textContent = getCurrentTime();
            }

            // Update teks tombol riwayat berdasarkan state collapse
            const riwayatCollapse = document.getElementById('riwayatCollapse');
            const riwayatText = document.querySelector('.riwayat-text');

            riwayatCollapse.addEventListener('show.bs.collapse', function() {
                riwayatText.textContent = 'Sembunyikan Riwayat Presensi';
            });

            riwayatCollapse.addEventListener('hide.bs.collapse', function() {
                riwayatText.textContent = 'Lihat Riwayat Presensi';
            });
        });

        function tapMasuk() {
            const btnTap = document.getElementById('btnTapMasuk');

            if (sedangAbsenMasuk) {
                alert('Sedang memproses presensi masuk, mohon tunggu...');
                return;
            }

            const currentTime = getCurrentTime();

            if (confirm(`Konfirmasi Presensi Masuk\n\nWaktu: ${currentTime}\n\nLanjutkan?`)) {
                sedangAbsenMasuk = true;
                btnTap.classList.add('disabled');
                btnTap.innerHTML =
                    '<div class="d-flex flex-column align-items-center"><i class="bi bi-hourglass-split fs-2 mb-2"></i><span>Memproses...</span></div>';

                document.getElementById('jamMasukInput').value = currentTime;
                document.getElementById('formMasuk').submit();
            }
        }

        function tapPulang() {
            const btnTap = document.getElementById('btnTapPulang');

            const currentTime = getCurrentTime();
            const confirmMessage =
                `Konfirmasi Presensi Pulang\n\nWaktu: ${currentTime}\n\n⚠️ Info: Jika Anda sudah absen pulang sebelumnya, waktu akan diperbarui ke waktu terbaru.\n\nLanjutkan?`;

            if (confirm(confirmMessage)) {
                const originalContent = btnTap.innerHTML;
                btnTap.innerHTML =
                    '<div class="d-flex flex-column align-items-center"><i class="bi bi-hourglass-split fs-2 mb-2"></i><span>Memproses...</span></div>';

                document.getElementById('jamPulangInput').value = currentTime;
                document.getElementById('formPulang').submit();

                setTimeout(() => {
                    btnTap.innerHTML = originalContent;
                }, 3000);
            }
        }

        function updateJamPulang() {
            const currentTime = getCurrentTime();

            if (confirm(
                    `Update Presensi Pulang\n\nWaktu baru: ${currentTime}\n\n⚠️ Waktu pulang sebelumnya akan diganti dengan waktu ini.\n\nLanjutkan?`
                )) {
                document.getElementById('jamPulangInput').value = currentTime;
                document.getElementById('formPulang').submit();
            }
        }
    </script>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
