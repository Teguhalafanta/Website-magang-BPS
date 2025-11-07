@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 text-center">Presensi Pelajar</h3>

        {{-- ALERT JIKA ADA PESAN --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- LOGIKA PRESENSI --}}
        @php

            $pelajar = auth()->user()->pelajar;
            $pelajarPresensi = $pelajar->presensis ?? collect([]);
            $hariIni = now()->toDateString();
            $presensiHariIni = $pelajarPresensi->where('tanggal', $hariIni)->first();

            // Tambahkan logika selesai magang
            $tanggalSelesai = \Carbon\Carbon::parse($pelajar->rencana_selesai ?? now());
            $magangSelesai = now()->gt($tanggalSelesai); // true jika sudah lewat tanggal selesai
        @endphp

        {{-- CARD PRESENSI DENGAN TOMBOL TAP --}}
        <div class="card mb-4 shadow border-0">
            <div class="card-body text-center p-4">
                <h5 class="mb-3">Presensi Hari Ini ({{ now()->format('d M Y') }})</h5>
                <div class="mb-3">
                    <p class="mb-2">Waktu Sekarang: <strong id="jamSekarang" class="fs-5 text-primary"></strong></p>
                </div>

                {{-- CEK JIKA SUDAH SELESAI MAGANG --}}
                @if ($magangSelesai)
                    {{-- Magang selesai → tampilkan pesan --}}
                    <div class="alert alert-secondary text-center p-4">
                        <i class="bi bi-check-circle-fill fs-3 mb-2"></i>
                        <h5 class="fw-bold mb-2">Magang Telah Selesai</h5>
                        <p class="mb-0">Anda tidak dapat melakukan presensi lagi, tetapi masih bisa melihat riwayat
                            presensi Anda.</p>
                    </div>
                @elseif (!$presensiHariIni)
                    {{-- Belum absen sama sekali hari ini --}}
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <button type="button" id="btnTapMasuk"
                            class="btn-tap btn-tap-masuk d-flex flex-column align-items-center justify-content-center"
                            onclick="tapMasuk()">
                            <i class="bi bi-box-arrow-in-right fs-3 mb-2"></i>
                            <span>Absen Masuk</span>
                        </button>
                    </div>

                    <form id="formMasuk" action="{{ route('pelajar.presensi.store') }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="jam_client" id="jamMasukInput">
                    </form>
                @elseif($presensiHariIni && !$presensiHariIni->waktu_pulang)
                    {{-- Sudah masuk, belum pulang --}}
                    <div class="alert alert-success mb-3">
                        <h6 class="mb-2"><i class="bi bi-check-circle-fill"></i> Sudah Absen Masuk</h6>
                        <p class="mb-0">Jam Masuk: <strong>{{ $presensiHariIni->waktu_datang }}</strong></p>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <button type="button" id="btnTapPulang"
                            class="btn-tap btn-tap-pulang d-flex flex-column align-items-center justify-content-center"
                            onclick="tapPulang()">
                            <i class="bi bi-box-arrow-right fs-3 mb-2"></i>
                            <span>Absen Pulang</span>
                        </button>
                    </div>

                    <form id="formPulang" action="{{ route('pelajar.presensi.update', $presensiHariIni->id) }}"
                        method="POST" class="d-none">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="jam_client" id="jamPulangInput">
                    </form>
                @else
                    {{-- Sudah masuk dan pulang --}}
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
                                        <h4 class="text-primary mb-0">{{ $presensiHariIni->waktu_pulang }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if ($presensiHariIni->status == 'Terlambat')
                                <span class="badge bg-danger fs-6">{{ $presensiHariIni->status }}</span>
                            @else
                                <span class="badge bg-success fs-6">{{ $presensiHariIni->status }}</span>
                            @endif
                        </div>

                        {{-- TOMBOL UPDATE JAM PULANG --}}
                        <div class="mt-4 pt-3 border-top">
                            <p class="text-muted small mb-2">
                                <i class="bi bi-info-circle"></i> Perlu update jam pulang? Klik tombol di bawah
                            </p>
                            <button type="button" id="btnUpdatePulang" class="btn btn-warning btn-sm px-4 py-2"
                                onclick="updateJamPulang()">
                                <i class="bi bi-arrow-clockwise me-1"></i> Update Jam Pulang
                            </button>
                        </div>
                    </div>

                    {{-- Form untuk update (tetap ada meskipun sudah selesai) --}}
                    <form id="formPulang" action="{{ route('pelajar.presensi.update', $presensiHariIni->id) }}"
                        method="POST" class="d-none">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="jam_client" id="jamPulangInput">
                    </form>
                @endif
            </div>
        </div>

        {{-- FILTER BULAN --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"></h4>
            <form method="GET" action="{{ route('pelajar.presensi.index') }}" class="d-flex gap-2">
                <input type="month" name="bulan" class="form-control"
                    value="{{ request('bulan', now()->format('Y-m')) }}" onchange="this.form.submit()">
            </form>
        </div>

        {{-- LOGIKA GENERATE TANGGAL --}}
        @php

            $pelajar = auth()->user()->pelajar;
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

        {{-- TOMBOL UNTUK TAMPILKAN TABEL RIWAYAT --}}
        <div class="text-center mt-4">
            <button id="btnShowRiwayat" class="btn btn-outline-secondary btn-lg px-4 py-2 fw-semibold shadow-sm"
                style="border-radius: 30px;">
                <i class="bi bi-clock-history me-2"></i> Lihat Riwayat Presensi
            </button>
        </div>

        {{-- TABEL RIWAYAT PRESENSI (DISEMBUNYIKAN AWALNYA) --}}
        <div id="riwayatCard" class="card mt-4 shadow-sm border-0 d-none">
            <div class="card-body">
                <h5 class="card-title mb-3 fw-bold text-uppercase text-dark">
                    Riwayat Presensi Bulan {{ \Carbon\Carbon::parse($bulanDipilih)->locale('id')->isoFormat('MMMM Y') }}
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Waktu Datang</th>
                                <th>Waktu Pulang</th>
                                <th>Status</th>
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
                                <tr class="{{ $isWeekend ? 'table-light' : '' }}">
                                    <td>{{ $no++ }}</td>
                                    <td><strong>{{ $carbonDate->format('d M Y') }}</strong></td>
                                    <td>
                                        @if ($isWeekend)
                                            <span class="badge bg-secondary">{{ $namaHari }}</span>
                                        @else
                                            <span class="text-muted">{{ $namaHari }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($presensi)
                                            <span class="badge bg-success">
                                                <i class="bi bi-clock"></i> {{ $presensi->waktu_datang }}
                                            </span>
                                        @elseif($isFuture)
                                            <span class="text-muted">-</span>
                                        @elseif($isWeekend)
                                            <span class="badge bg-secondary">Libur</span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle"></i> Tidak Hadir
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($presensi && $presensi->waktu_pulang)
                                            <span class="badge bg-primary">
                                                <i class="bi bi-clock"></i> {{ $presensi->waktu_pulang }}
                                            </span>
                                        @elseif($presensi && !$presensi->waktu_pulang)
                                            <span class="badge bg-warning text-dark">Belum Pulang</span>
                                        @elseif($isFuture)
                                            <span class="text-muted">-</span>
                                        @elseif($isWeekend)
                                            <span class="badge bg-secondary">Libur</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($presensi)
                                            @switch(strtolower($presensi->status))
                                                @case('hadir')
                                                    <span class="badge bg-success">Hadir</span>
                                                @break

                                                @case('izin')
                                                    <span class="badge bg-warning text-dark">Izin</span>
                                                @break

                                                @case('sakit')
                                                    <span class="badge bg-info text-dark">Sakit</span>
                                                @break

                                                @case('terlambat')
                                                    <span class="badge bg-secondary">Terlambat</span>
                                                @break

                                                @case('alpha')
                                                    <span class="badge bg-danger">Alfa</span>
                                                @break

                                                @default
                                                    <span class="badge bg-light text-dark">-</span>
                                            @endswitch
                                        @elseif ($isFuture)
                                            <span class="badge bg-secondary">Belum Tiba</span>
                                        @elseif ($isWeekend)
                                            <span class="badge bg-secondary">Libur</span>
                                        @else
                                            <span class="badge bg-danger">Alfa</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- SCRIPT UNTUK TOMBOL RIWAYAT --}}
        <script>
            document.getElementById('btnShowRiwayat').addEventListener('click', function() {
                const card = document.getElementById('riwayatCard');
                card.classList.toggle('d-none');
                this.innerHTML = card.classList.contains('d-none') ?
                    '<i class="bi bi-clock-history me-2"></i> Lihat Riwayat Presensi' :
                    '<i class="bi bi-eye-slash me-2"></i> Sembunyikan Riwayat Presensi';
            });
        </script>

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
            $totalHadir = $presensiFilterBulan->count();
            $totalTepat = $presensiFilterBulan->where('status', 'Tepat Waktu')->count();
            $totalTerlambat = $presensiFilterBulan->where('status', 'Terlambat')->count();
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

        {{-- Tombol untuk menampilkan statistik --}}
        <div class="text-center mt-4">
            <button id="btnShowStatistik" class="btn btn-outline-primary btn-lg px-4 py-2 fw-semibold shadow-sm"
                style="border-radius: 30px;">
                <i class="bi bi-bar-chart-line me-2"></i> Lihat Statistik Bulan Ini
            </button>
        </div>

        {{-- CARD STATISTIK --}}
        <div id="statistikCard" class="card mt-4 shadow-sm border-0 d-none">
            <div class="card-body">
                <h5 class="card-title mb-3 fw-bold text-uppercase text-dark">
                    Statistik Bulan {{ \Carbon\Carbon::parse($bulanDipilih)->locale('id')->isoFormat('MMMM Y') }}
                </h5>
                <div class="row text-center">
                    <div class="col-md-2 mb-3">
                        <div class="p-3 bg-success text-white rounded shadow-sm">
                            <h3 class="mb-0">{{ $totalHadir }}</h3>
                            <small>Hadir</small>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="p-3 bg-primary text-white rounded shadow-sm">
                            <h3 class="mb-0">{{ $totalTepat }}</h3>
                            <small>Tepat Waktu</small>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="p-3 bg-warning text-dark rounded shadow-sm">
                            <h3 class="mb-0">{{ $totalTerlambat }}</h3>
                            <small>Terlambat</small>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="p-3 bg-info text-dark rounded shadow-sm">
                            <h3 class="mb-0">{{ $totalSakit }}</h3>
                            <small>Sakit</small>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="p-3 bg-warning text-dark rounded shadow-sm" style="background-color: #ffc107;">
                            <h3 class="mb-0">{{ $totalIzin }}</h3>
                            <small>Izin</small>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="p-3 bg-danger text-white rounded shadow-sm">
                            <h3 class="mb-0">{{ $totalAlfa }}</h3>
                            <small>Alfa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SCRIPT UNTUK TOMBOL STATISTIK --}}
        <script>
            document.getElementById('btnShowStatistik').addEventListener('click', function() {
                const card = document.getElementById('statistikCard');
                card.classList.toggle('d-none');
                this.innerHTML = card.classList.contains('d-none') ?
                    '<i class="bi bi-bar-chart-line me-2"></i> Lihat Statistik Bulan Ini' :
                    '<i class="bi bi-eye-slash me-2"></i> Sembunyikan Statistik';
            });
        </script>
    </div>

    {{-- CUSTOM CSS --}}
    <style>
        .btn-tap {
            padding: 16px 32px;
            border-radius: 12px;
            border: none;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 16px;
            min-width: 180px;
            justify-content: center;
        }

        .btn-tap i {
            font-size: 24px;
        }

        .btn-tap-masuk {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-tap-masuk:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
        }

        .btn-tap-pulang {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        .btn-tap-pulang:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 193, 7, 0.4);
        }

        .btn-tap:active:not(.disabled) {
            transform: translateY(0) !important;
        }

        .btn-tap.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            }

            50% {
                box-shadow: 0 4px 20px rgba(40, 167, 69, 0.5);
            }
        }

        @keyframes pulsePulang {

            0%,
            100% {
                box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
            }

            50% {
                box-shadow: 0 4px 20px rgba(255, 193, 7, 0.5);
            }
        }

        .btn-tap-masuk:not(.disabled) {
            animation: pulse 2s infinite;
        }

        .btn-tap-pulang:not(.disabled) {
            animation: pulsePulang 2s infinite;
        }
    </style>

    {{-- SCRIPT LOGIC --}}
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
        });

        function tapMasuk() {
            const btnTap = document.getElementById('btnTapMasuk');

            // PROTEKSI: Hanya bisa sekali untuk absen masuk
            if (sedangAbsenMasuk) {
                alert('Sedang memproses presensi masuk, mohon tunggu...');
                return;
            }

            const currentTime = getCurrentTime();

            if (confirm(`Konfirmasi Presensi Masuk\n\nWaktu: ${currentTime}\n\nLanjutkan?`)) {
                sedangAbsenMasuk = true;
                btnTap.classList.add('disabled');
                btnTap.innerHTML =
                    '<div class="d-flex flex-column align-items-center"><i class="bi bi-hourglass-split fs-3 mb-2"></i><span>Memproses...</span></div>';

                document.getElementById('jamMasukInput').value = currentTime;
                document.getElementById('formMasuk').submit();
            }
        }

        function tapPulang() {
            const btnTap = document.getElementById('btnTapPulang');

            // TIDAK ADA PROTEKSI - Bisa diklik berkali-kali
            const currentTime = getCurrentTime();

            // Pesan konfirmasi dengan info bahwa bisa diupdate
            const confirmMessage =
                `Konfirmasi Presensi Pulang\n\nWaktu: ${currentTime}\n\n⚠️ Info: Jika Anda sudah absen pulang sebelumnya, waktu akan diperbarui ke waktu terbaru.\n\nLanjutkan?`;

            if (confirm(confirmMessage)) {
                // Tampilkan loading (tanpa disable permanent)
                const originalContent = btnTap.innerHTML;
                btnTap.innerHTML =
                    '<div class="d-flex flex-column align-items-center"><i class="bi bi-hourglass-split fs-3 mb-2"></i><span>Memproses...</span></div>';

                document.getElementById('jamPulangInput').value = currentTime;
                document.getElementById('formPulang').submit();

                // Reset button setelah 3 detik (antisipasi jika gagal submit)
                setTimeout(() => {
                    btnTap.innerHTML = originalContent;
                }, 3000);
            }
        }

        // Fungsi untuk update jam pulang dari tombol update
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
