@extends('kerangka.master')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary mb-0">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin BPS
            </h4>
            <span class="badge bg-secondary">{{ date('d M Y') }}</span>
        </div>

        <div class="row g-3">
            {{-- Total Pelajar --}}
            <div class="col-md-4 mb-2">
                <a href="{{ route('admin.pengajuan.index') }}" class="text-decoration-none">
                    <div class="card text-white bg-primary shadow border-0 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="text-white-50 mb-1 text-uppercase" style="font-size: 0.7rem;">Total Pelajar
                                    </h6>
                                    <h3 class="fw-bold mb-0">{{ $jumlahPelajar }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-3 p-2">
                                    <i class="bi bi-people-fill fs-4"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-white-50" style="font-size: 0.75rem;">Lihat Detail</small>
                                <i class="bi bi-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Presensi Hari Ini --}}
            <div class="col-md-4 mb-2">
                <a href="{{ route('admin.presensi.index', ['today' => true]) }}" class="text-decoration-none">
                    <div class="card bg-warning text-white shadow border-0 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="text-white-50 mb-1 text-uppercase" style="font-size: 0.7rem;">Presensi Hari
                                        Ini</h6>
                                    <h3 class="fw-bold mb-0">{{ $jumlahPresensiHariIni }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-3 p-2">
                                    <i class="bi bi-calendar-check-fill fs-4"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-white-50" style="font-size: 0.75rem;">Lihat Detail</small>
                                <i class="bi bi-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Kegiatan --}}
            <div class="col-md-4 mb-2">
                <a href="{{ route('admin.kegiatan.index') }}" class="text-decoration-none">
                    <div class="card text-white bg-success shadow border-0 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="text-white-50 mb-1 text-uppercase" style="font-size: 0.7rem;">Total Kegiatan
                                    </h6>
                                    <h3 class="fw-bold mb-0">{{ $jumlahKegiatan }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-3 p-2">
                                    <i class="bi bi-list-task fs-4"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-white-50" style="font-size: 0.75rem;">Lihat Detail</small>
                                <i class="bi bi-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Grafik --}}
        <div class="row mt-3 g-3">
            <div class="col-md-8">
                <div class="card border-0"
                    style="box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; background: #ffffff;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: #1a202c; font-size: 1.1rem;">
                                    <i class="bi bi-bar-chart-line-fill text-success me-2"></i>
                                    Grafik Jumlah Peserta Magang per Bulan
                                </h5>
                            </div>
                            <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex">
                                <select name="tahun" class="form-select border-0 bg-light"
                                    style="font-size: 0.875rem; padding: 0.5rem 2rem 0.5rem 0.75rem; border-radius: 8px; font-weight: 500; color: #4a5568; cursor: pointer; min-width: 90px;"
                                    onchange="this.form.submit()">
                                    @foreach ($daftarTahun as $th)
                                        <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>
                                            {{ $th }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4 pt-2" style="height: 320px;">
                        <canvas id="grafikPesertaMagang"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0"
                    style="box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; background: #ffffff;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: #1a202c; font-size: 1.1rem;">
                                    <i class="bi bi-pie-chart-fill text-success me-2"></i>
                                    Distribusi Absensi Harian
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4 pt-2" style="height: 320px;">
                        @if ($dataPresensiHarian['totalPeserta'] > 0)
                            <canvas id="grafikPresensiHarian"></canvas>
                        @else
                            <div class="d-flex flex-column justify-content-center align-items-center h-100 text-secondary">
                                <i class="bi bi-emoji-neutral" style="font-size: 2.5rem;"></i>
                                <p class="mt-2 mb-0 fw-semibold">Tidak ada peserta aktif hari ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 g-3">
            <div class="col-md-6">
                <div class="card border-0"
                    style="box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; background: #ffffff;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: #1a202c; font-size: 1.1rem;">
                                    <i class="bi bi-bar-chart text-success me-2"></i>
                                    Grafik Peserta Magang per Instansi
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4 pt-2" style="height: 320px;">
                        <canvas id="grafikPeserta"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0"
                    style="box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; background: #ffffff;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: #1a202c; font-size: 1.1rem;">
                                    <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                                    Grafik Total Peserta Magang Tiap Tahun
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4 pt-2" style="height: 320px;">
                        <canvas id="chartMagangTiapTahun"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kalender --}}
        <div class="row mt-3 g-3">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-3 pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">
                                <i class="bi bi-calendar-event text-success me-2"></i>
                                Kalender Kegiatan
                            </h6>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-secondary" id="prevMonth"
                                    style="padding: 0.25rem 0.5rem;">
                                    <i class="bi bi-chevron-left" style="font-size: 0.8rem;"></i>
                                </button>
                                <span class="fw-bold mx-3" style="font-size: 0.85rem;" id="currentMonth"></span>
                                <button class="btn btn-sm btn-outline-secondary" id="nextMonth"
                                    style="padding: 0.25rem 0.5rem;">
                                    <i class="bi bi-chevron-right" style="font-size: 0.8rem;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center mb-0" style="font-size: 0.8rem;">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="text-danger py-1 px-1" style="width: 14.28%;">Min</th>
                                        <th class="py-1 px-1" style="width: 14.28%;">Sen</th>
                                        <th class="py-1 px-1" style="width: 14.28%;">Sel</th>
                                        <th class="py-1 px-1" style="width: 14.28%;">Rab</th>
                                        <th class="py-1 px-1" style="width: 14.28%;">Kam</th>
                                        <th class="py-1 px-1" style="width: 14.28%;">Jum</th>
                                        <th class="text-primary py-1 px-1" style="width: 14.28%;">Sab</th>
                                    </tr>
                                </thead>
                                <tbody id="calendarBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .hover-cell:hover {
            background-color: #f8f9fa !important;
            transition: background-color 0.2s ease;
        }
    </style>
    <script>
        const ctx3 = document.getElementById('grafikPesertaMagang');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: @json($dataMagangPerBulan['labels']),
                datasets: [{
                    label: 'Jumlah Peserta',
                    data: @json($dataMagangPerBulan['totals']),
                    backgroundColor: '#198754',
                    borderRadius: 6,
                    borderWidth: 0,
                    barThickness: 32,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 2
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 8,
                            font: {
                                size: 11
                            },
                            color: '#64748B',
                            maxRotation: 0,
                            minRotation: 0,
                            autoSkip: false
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10
                    }
                }
            }
        });

        (function() {
            const ctxPresensi = document.getElementById('grafikPresensiHarian').getContext('2d');
            if (ctxPresensi) {
                const totalPeserta = @json($dataPresensiHarian['totalPeserta'] ?? 0);
                const sudahPresensi = @json($dataPresensiHarian['sudahPresensi'] ?? 0);
                const belumPresensi = @json($dataPresensiHarian['belumPresensi'] ?? 0);

                const semuaSudahPresensi = (belumPresensi === 0 && totalPeserta > 0);
                const warna = semuaSudahPresensi ? ['#198754', '#e9ecef'] : ['#dc3545', '#e9ecef'];

                new Chart(ctxPresensi, {
                    type: 'doughnut',
                    data: {
                        labels: ['Sudah Presensi', 'Belum Presensi'],
                        datasets: [{
                            data: [sudahPresensi, belumPresensi],
                            backgroundColor: warna,
                            borderWidth: 0,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 12
                                    },
                                    padding: 10
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const value = context.parsed;
                                        const percentage = total ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${context.label}: ${value} (${percentage}%)`;
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: `Persentase Presensi Hari Ini ({{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }})`,
                                font: {
                                    size: 13,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                });
            }
        })();

        const ctx = document.getElementById('grafikPeserta');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($instansi->pluck('nama_instansi')),
                datasets: [{
                    label: 'Jumlah Peserta Magang',
                    data: @json($instansi->pluck('total')),
                    backgroundColor: ['#4A90E2', '#F15B5B', '#7ED321', '#F8E71C', '#9013FE'],
                    borderRadius: 8,
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Peserta Magang per Instansi'
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxTahun = document.getElementById('chartMagangTiapTahun').getContext('2d');
        const chartTahun = new Chart(ctxTahun, {
            type: 'line',
            data: {
                labels: @json($grafikMagangPerTahun['labels']),
                datasets: [{
                    label: 'Total Peserta Magang',
                    data: @json($grafikMagangPerTahun['totals']),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointBackgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Peserta'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tahun'
                        }
                    }
                }
            }
        });

        // Kalender
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        function generateCalendar(month, year) {
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            document.getElementById('currentMonth').textContent = `${months[month]} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let calendarHTML = '';
            let day = 1;

            // Hitung jumlah minggu yang dibutuhkan
            const totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;

            for (let i = 0; i < totalCells; i++) {
                if (i % 7 === 0) {
                    calendarHTML += '<tr>';
                }

                if (i < firstDay || day > daysInMonth) {
                    calendarHTML += '<td class="text-muted p-0" style="height: 45px;"></td>';
                } else {
                    const today = new Date();
                    const isToday = day === today.getDate() && month === today.getMonth() && year === today.getFullYear();
                    const bgClass = isToday ? 'bg-primary text-white' : 'bg-white';
                    const hoverClass = !isToday ? 'hover-cell' : '';

                    calendarHTML += `
                        <td style="height: 45px; vertical-align: middle; cursor: pointer;" class="${bgClass} ${hoverClass} p-0">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <span class="fw-${isToday ? 'bold' : 'normal'}" style="font-size: 0.75rem;">${day}</span>
                            </div>
                        </td>
                    `;
                    day++;
                }

                if (i % 7 === 6) {
                    calendarHTML += '</tr>';
                }
            }

            document.getElementById('calendarBody').innerHTML = calendarHTML;
        }

        document.getElementById('prevMonth').addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        });

        document.getElementById('nextMonth').addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
        });

        // Generate kalender pertama kali
        generateCalendar(currentMonth, currentYear);
    </script>
@endpush
