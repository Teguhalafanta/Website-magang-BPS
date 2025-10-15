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

            <!-- Search Section -->
            <div class="card-body bg-light border-bottom">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('pembimbing.presensi') }}" id="filterForm">
                            <label for="pelajar_id" class="form-label fw-semibold mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="me-1" viewBox="0 0 16 16">
                                    <path
                                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                </svg>
                                Filter Berdasarkan Pelajar
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
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="presensiTable" class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 50px;">No</th>
                                <th class="py-3 px-4 fw-semibold text-secondary text-start" style="min-width: 150px;">
                                    Pelajar</th>
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 120px;">Tanggal</th>
                                <th class="py-3 px-3 fw-semibold text-secondary" style="width: 100px;">Status</th>
                                <th class="py-3 px-4 fw-semibold text-secondary text-start" style="min-width: 200px;">
                                    Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($presensis as $index => $presensi)
                                <tr class="border-bottom">
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
                                    <td class="px-4">
                                        <span class="text-dark">{{ $presensi->keterangan ?? '-' }}</span>
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
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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
                    [2, 'desc']
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
    </style>
@endpush
