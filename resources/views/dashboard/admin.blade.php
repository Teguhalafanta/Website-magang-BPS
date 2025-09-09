@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Dashboard Admin BPS</h2>

        <div class="row">
            {{-- Total Pelajar --}}
            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.pengajuan.index') }}">
                    <div class="card text-white bg-primary shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Pelajar</h5>
                                <h3>{{ $jumlahPelajar }}</h3>
                            </div>
                            <i class="bi bi-people-fill fs-2"></i>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Absensi Hari Ini --}}
            <div class="col-md-3 mb-3">
                <a href="{{ route('absensi.index', ['today' => true]) }}" class="text-decoration-none">
                    <div class="card bg-warning text-white shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Absensi Hari Ini</h5>
                                <h3>{{ $jumlahAbsensiHariIni }}</h3>
                            </div>
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Kegiatan --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Kegiatan</h5>
                            <h3>{{ $jumlahKegiatan }}</h3>
                        </div>
                        <i class="bi bi-list-task fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <canvas id="grafikAbsensi"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="grafikJurusan"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx1 = document.getElementById('grafikAbsensi');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
                datasets: [{
                    label: 'Absensi',
                    data: [12, 19, 3, 5, 2],
                    backgroundColor: '#007bff'
                }]
            }
        });

        const ctx2 = document.getElementById('grafikJurusan');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Informatika', 'Statistika', 'Ekonomi'],
                datasets: [{
                    data: [12, 7, 5],
                    backgroundColor: ['#17a2b8', '#ffc107', '#dc3545']
                }]
            }
        });
    </script>
@endpush
