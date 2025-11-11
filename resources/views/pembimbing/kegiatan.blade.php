@extends('kerangka.master')

@section('title', 'Daftar Kegiatan Pelajar Saya')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="bg-primary rounded p-3">
                            <i class="fas fa-tasks text-white fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Daftar Kegiatan Peserta Bimbingan</h2>
                        <p class="text-muted mb-0">Laporan aktivitas dan progress pelajar bimbingan Anda</p>
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

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <!-- Card Header -->
            <div class="card-header bg-primary text-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="fas fa-list-check me-2"></i>Tabel Kegiatan
                    </h5>
                </div>
            </div>

            <!-- Search Section -->
            <div class="card-body bg-light border-bottom py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0" 
                                   placeholder="Cari nama kegiatan, pelajar, atau deskripsi...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="lengthMenu" class="form-select">
                            <option value="10">10 data</option>
                            <option value="25">25 data</option>
                            <option value="50">50 data</option>
                            <option value="100">100 data</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="kegiatanTable" class="table table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th class="py-3 px-3 text-center" style="width: 80px;">No</th>
                                <th class="py-3 px-3">Pelajar</th>
                                <th class="py-3 px-3">Nama Kegiatan</th>
                                <th class="py-3 px-3 text-center" style="width: 120px;">Tanggal</th>
                                <th class="py-3 px-3 text-center" style="width: 140px;">Status</th>
                                <th class="py-3 px-3 text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kegiatans as $index => $kegiatan)
                                <tr>
                                    <td class="text-center py-3">
                                        <span class="badge bg-light text-dark rounded-circle p-2 fw-medium">
                                            {{ $kegiatans->firstItem() + $index }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                                <i class="fas fa-user fw-bold"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $kegiatan->pelajar->nama ?? '-' }}</div>
                                                <small class="text-muted">{{ $kegiatan->pelajar->asal_institusi ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-semibold text-dark">{{ $kegiatan->nama_kegiatan }}</div>
                                        <small class="text-muted">{{ Str::limit($kegiatan->deskripsi, 50) }}</small>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-info bg-opacity-10 text-info border-0 px-3 py-2">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center py-3">
                                        @php
                                            $statusColors = [
                                                'Belum Dimulai' => 'secondary',
                                                'Dalam Proses' => 'warning',
                                                'Selesai' => 'success',
                                            ];
                                            $colorClass = $statusColors[$kegiatan->status_penyelesaian] ?? 'primary';
                                            $statusIcons = [
                                                'Belum Dimulai' => 'fa-clock',
                                                'Dalam Proses' => 'fa-spinner',
                                                'Selesai' => 'fa-check-circle',
                                            ];
                                            $statusIcon = $statusIcons[$kegiatan->status_penyelesaian] ?? 'fa-circle';
                                        @endphp
                                        <span class="badge bg-{{ $colorClass }} px-3 py-2">
                                            <i class="fas {{ $statusIcon }} me-1"></i>
                                            {{ $kegiatan->status_penyelesaian }}
                                        </span>
                                    </td>
                                    <td class="text-center py-3">
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $kegiatan->id }}">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $kegiatan->id }}" tabindex="-1"
                                    aria-labelledby="detailModalLabel{{ $kegiatan->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-primary text-white py-3">
                                                <div>
                                                    <h5 class="modal-title fw-bold mb-1" id="detailModalLabel{{ $kegiatan->id }}">
                                                        <i class="fas fa-info-circle me-2"></i>Detail Kegiatan
                                                    </h5>
                                                    <small class="opacity-75">{{ $kegiatan->nama_kegiatan }}</small>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="row g-4">
                                                    <!-- Informasi Utama -->
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <label class="text-muted small mb-2">Nama Pelajar</label>
                                                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                                                    <i class="fas fa-user"></i>
                                                                </div>
                                                                <span class="fw-semibold">{{ $kegiatan->pelajar->nama ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <label class="text-muted small mb-2">Tanggal Kegiatan</label>
                                                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                                                <i class="fas fa-calendar text-info me-3"></i>
                                                                <span class="fw-semibold">
                                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d F Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Nama Kegiatan -->
                                                    <div class="col-12">
                                                        <div class="info-item">
                                                            <label class="text-muted small mb-2">Nama Kegiatan</label>
                                                            <div class="p-3 bg-light rounded">
                                                                <p class="mb-0 fw-semibold text-dark fs-6">
                                                                    {{ $kegiatan->nama_kegiatan }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Deskripsi -->
                                                    <div class="col-12">
                                                        <div class="info-item">
                                                            <label class="text-muted small mb-2">Deskripsi Kegiatan</label>
                                                            <div class="p-3 bg-light rounded">
                                                                <p class="mb-0 text-dark" style="line-height: 1.6;">
                                                                    {{ $kegiatan->deskripsi }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Status dan Bukti -->
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <label class="text-muted small mb-2">Status Penyelesaian</label>
                                                            <div class="p-3 bg-light rounded text-center">
                                                                <span class="badge bg-{{ $colorClass }} px-3 py-2 fs-6">
                                                                    <i class="fas {{ $statusIcon }} me-1"></i>
                                                                    {{ $kegiatan->status_penyelesaian }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <label class="text-muted small mb-2">Bukti Dukung</label>
                                                            <div class="p-3 bg-light rounded text-center">
                                                                @if ($kegiatan->bukti_dukung)
                                                                    <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}"
                                                                        target="_blank" class="btn btn-primary">
                                                                        <i class="fas fa-download me-1"></i>
                                                                        Lihat Bukti
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">Tidak ada bukti</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light py-3">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i>Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                            <p class="fw-semibold mb-1">Belum Ada Kegiatan</p>
                                            <p class="small mb-0">Data kegiatan pelajar akan muncul di sini</p>
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
            const table = $('#kegiatanTable').DataTable({
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
                order: [[3, 'desc']]
            });

            // Custom search input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Custom length menu
            $('#lengthMenu').on('change', function() {
                table.page.len(parseInt(this.value)).draw();
            });

            // Update info display
            table.on('draw', function() {
                const info = table.page.info();
                $('#tableInfo').html(
                    'Menampilkan ' + (info.start + 1) + ' sampai ' + info.end + ' dari ' + info.recordsTotal + ' data'
                );
            });

            // Initial info display
            const info = table.page.info();
            $('#tableInfo').html(
                'Menampilkan ' + (info.start + 1) + ' sampai ' + info.end + ' dari ' + info.recordsTotal + ' data'
            );
        });
    </script>
@endpush

@push('styles')
    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-item label {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .table-primary {
            background-color: #0d6efd;
        }

        .table-primary th {
            border: none;
            color: white;
            font-weight: 600;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .modal-content {
            border: none;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        .badge {
            font-weight: 500;
        }
    </style>
@endpush    