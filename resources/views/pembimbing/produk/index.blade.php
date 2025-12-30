@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <!-- Header Section -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="bg-primary rounded p-2">
                            <i class="bi bi-box-seam  text-white fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">Produk Magang Peserta Bimbingan</h3>
                        <p class="text-muted mb-0">Pantau dan kelola produk dari peserta yang Anda bimbing</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($produk->count() == 0)
            <!-- Empty State -->
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body py-5">
                    <div class="text-center">
                        <i class="bi bi-inbox fa-4x text-primary opacity-50 mb-4"></i>
                        <h4 class="text-primary fw-bold mb-3">Belum Ada Produk</h4>
                        <p class="text-muted mb-0">Belum ada produk yang diupload oleh peserta bimbingan Anda.</p>
                    </div>
                </div>
            </div>
        @else
            {{-- Summary Cards --}}
            <div class="row g-3 mb-2">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-white bg-primary">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-10 rounded-circle p-1 me-3">
                                    <i class="bi bi-box fa-2x text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-uppercase small opacity-75">Total Produk</h6>
                                    <h3 class="mb-0 fw-bold">{{ $produk->count() }}</h3>
                                    <small class="opacity-75">Seluruh produk magang</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-white bg-success">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-10 rounded-circle p-1 me-3">
                                    <i class="bi bi-person fa-2x text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-uppercase small opacity-75">Total Peserta</h6>
                                    <h3 class="mb-0 fw-bold">{{ $produk->unique('pelajar_id')->count() }}</h3>
                                    <small class="opacity-75">Peserta aktif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-white bg-info">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-10 rounded-circle p-1 me-3">
                                    <i class="bi bi-calendar-check fa-2x text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-uppercase small opacity-75">Bulan Ini</h6>
                                    <h3 class="mb-0 fw-bold">
                                        {{ $produk->where('created_at', '>=', now()->startOfMonth())->count() }}
                                    </h3>
                                    <small class="opacity-75">Produk baru</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-primary text-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="bi bi-table me-2"></i>Daftar Produk Magang
                        </h5>
                        <span class="badge bg-light text-primary fs-6">{{ $produk->count() }} Produk</span>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="card-body bg-light border-bottom py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" 
                                        placeholder="Cari nama produk, peserta, atau deskripsi...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableProduk">
                            <thead class="table-primary">
                                <tr>
                                    <th class="py-3 px-3 text-center" style="width: 80px;">No</th>
                                    <th class="py-3 px-3 text-center">Nama Peserta</th>
                                    <th class="py-3 px-3 text-center">Nama Produk</th>
                                    <th class="py-3 px-3 text-center">Deskripsi</th>
                                    <th class="py-3 px-3 text-center" style="width: 120px;">File</th>
                                    <th class="py-3 px-3 text-center" style="width: 140px;">Tanggal Upload</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produk as $p)
                                    <tr>
                                        <td class="text-center py-3 fw-medium">
                                            <span class="badge bg-light text-dark rounded-circle p-2">
                                                {{ $loop->iteration }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                                    <strong>{{ substr($p->pelajar->nama, 0, 1) }}</strong>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $p->pelajar->nama }}</div>
                                                    <small class="text-muted">{{ $p->pelajar->asal_institusi ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 fw-semibold text-dark">{{ $p->nama_produk }}</td>
                                        <td class="py-3 text-muted">
                                            @if ($p->deskripsi)
                                                <span class="d-inline-block text-truncate" style="max-width: 300px;"
                                                    title="{{ $p->deskripsi }}">
                                                    {{ $p->deskripsi }}
                                                </span>
                                            @else
                                                <span class="text-muted fst-italic">Tidak ada deskripsi</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3">
                                            <a href="{{ asset('storage/' . $p->file_produk) }}"
                                                class="btn btn-success btn-sm" target="_blank"
                                                title="Download File">
                                                <i class="bi bi-download me-1"></i>Download
                                            </a>
                                        </td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $p->created_at->format('d-m-Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tableProduk').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ produk",
                    "zeroRecords": "Tidak ditemukan data produk",
                    "search": "Cari:",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ produk",
                    "infoEmpty": "Menampilkan 0 - 0 dari 0 produk",
                    "infoFiltered": "(difilter dari _MAX_ total produk)",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "›",
                        "previous": "‹"
                    }
                },
                "pageLength": 10,
                "order": [[5, "desc"]], // Sort by date (newest first)
                "columnDefs": [{
                    "orderable": false,
                    "targets": 4 // Disable sorting on download column
                }],
                "dom": 't'
            });
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

        .badge {
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }
    </style>
@endpush