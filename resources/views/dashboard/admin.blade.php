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
                <a href="{{ route('pelajar.presensi.index', ['today' => true]) }}" class="text-decoration-none">
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
                <a href="{{ route('pelajar.kegiatan.index') }}" class="text-decoration-none">
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
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-3 pb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                            Grafik Presensi Mingguan
                        </h6>
                    </div>
                    <div class="card-body p-3" style="height: 250px;">
                        <canvas id="grafikPresensi"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-3 pb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-pie-chart-fill text-info me-2"></i>
                            Distribusi Jurusan
                        </h6>
                    </div>
                    <div class="card-body p-3" style="height: 250px;">
                        <canvas id="grafikJurusan"></canvas>
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
        const ctx1 = document.getElementById('grafikPresensi');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                datasets: [{
                    label: 'Jumlah Presensi',
                    data: [12, 19, 3, 5, 2],
                    backgroundColor: '#0d6efd',
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('grafikJurusan');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Informatika', 'Statistika', 'Ekonomi'],
                datasets: [{
                    data: [12, 7, 5],
                    backgroundColor: ['#0d6efd', '#ffc107', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            }
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
