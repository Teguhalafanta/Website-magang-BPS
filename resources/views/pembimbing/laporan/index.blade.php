@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <!-- Header Section -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="bg-primary rounded p-2">
                            <i class="bi bi-file-check text-white fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">Verifikasi Laporan Akhir Peserta Bimbingan</h3>
                        <p class="text-muted mb-0">Silakan cek laporan akhir peserta sebelum disetujui atau ditolak</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Alert Error -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <span>{{ session('error') }}</span>
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
                        <i class="bi bi-list-check me-2"></i>Daftar Laporan Peserta
                    </h5>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card-body bg-light border-bottom py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <select class="form-select">
                            <option>10 data</option>
                            <option>25 data</option>
                            <option>50 data</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th class="py-3 px-3 text-center">No</th>
                                <th class="py-3 px-3 text-center">Nama Peserta</th>
                                <th class="py-3 px-3 text-center">Status</th>
                                <th class="py-3 px-3 text-center">Lihat</th>
                                <th class="py-3 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporans as $index => $laporan)
                                <tr>
                                    <td class="text-center py-3 px-3 fw-medium">{{ $index + 1 }}</td>
                                    <td class="py-3 px-3 fw-medium">{{ $laporan->user->pelajar->nama }}</td>

                                    <td class="text-center py-3 px-3">
                                        @if ($laporan->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock me-1"></i>Menunggu
                                            </span>
                                        @elseif ($laporan->status == 'ditolak')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-times me-1"></i>Ditolak
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-check me-1"></i>Disetujui
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center py-3 px-3">
                                        <button class="btn btn-outline-primary btn-sm"
                                            onclick="tampilLaporan('{{ asset('storage/' . $laporan->file) }}')">
                                            <i class="bi bi-eye me-1"></i>Lihat Laporan
                                        </button>
                                    </td>

                                    <td class="text-center py-3 px-3">
                                        @if ($laporan->status == 'menunggu')
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('pembimbing.laporan.setujui', $laporan->id) }}" 
                                                        method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check me-1"></i>Setujui
                                                    </button>
                                                </form>

                                                <form action="{{ route('pembimbing.laporan.tolak', $laporan->id) }}" 
                                                        method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-times me-1"></i>Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada aksi</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">Belum ada laporan dikirim</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PREVIEW LAPORAN -->
        <div id="preview-box" class="card border-0 shadow-sm rounded-3 mt-4" style="display:none;">
            <div class="card-header bg-primary text-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="bi bi-file-pdf me-2"></i>Preview Laporan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" 
                            onclick="tutupPreview()"></button>
                </div>
            </div>
            <div class="card-body p-0">
                <iframe id="frame-laporan" src="" width="100%" height="600px"
                    style="border:none; border-radius: 0 0 0.5rem 0.5rem;"></iframe>
            </div>
        </div>

    </div>

    <script>
        function tampilLaporan(url) {
            console.log('Membuka file:', url);
            document.getElementById('preview-box').style.display = 'block';
            document.getElementById('frame-laporan').src = url;
            window.scrollTo({
                top: document.getElementById('preview-box').offsetTop - 20,
                behavior: 'smooth'
            });
        }

        function tutupPreview() {
            document.getElementById('preview-box').style.display = 'none';
            document.getElementById('frame-laporan').src = '';
        }
    </script>
@endsection

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
            font-size: 1rem;
        }
        
        .table td {
            font-size: 1rem;
            vertical-align: middle;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
            font-size: 0.875rem;
            padding: 0.5em 0.75em;
        }
        
        /* Professional table styling */
        .table-primary {
            background-color: #0d6efd;
            color: white;
        }
        
        .table-primary th {
            border-color: #0d6efd;
            color: white;
            font-weight: 600;
        }
        
        /* Button styling */
        .btn {
            font-weight: 500;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        /* Form styling */
        .form-select, .form-control {
            border-radius: 0.375rem;
        }
        
        /* Alert styling */
        .alert {
            border-radius: 0.5rem;
        }
        
        /* Input group styling */
        .input-group-text {
            background-color: white;
        }
        
        /* Gap utility for flex items */
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Hover effects */
        .btn {
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
    </style>
@endpush