@extends('kerangka.master')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h3 class="fw-bold text-primary">Produk Magang Peserta Bimbingan</h3>
                <p class="text-muted">Pantau dan kelola produk dari pelajar yang Anda bimbing</p>
            </div>
        </div>

        @if ($produk->count() == 0)
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle fs-4 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Belum Ada Produk</h5>
                    <p class="mb-0">Belum ada produk yang diupload oleh pelajar bimbingan Anda.</p>
                </div>
            </div>
        @else
            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-box-seam fs-1 opacity-75"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-uppercase small">Total Produk</h6>
                                    <h3 class="mb-0 fw-bold">{{ $produk->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-people fs-1 opacity-75"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-uppercase small">Total Pelajar</h6>
                                    <h3 class="mb-0 fw-bold">{{ $produk->unique('pelajar_id')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar-check fs-1 opacity-75"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-uppercase small">Bulan Ini</h6>
                                    <h3 class="mb-0 fw-bold">
                                        {{ $produk->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-table me-2 text-primary"></i>Daftar Produk
                        </h5>
                        <span class="badge bg-primary rounded-pill">{{ $produk->count() }} Produk</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableProduk">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th style="width: 18%;">
                                        <i class="bi bi-person me-1"></i>Nama Pelajar
                                    </th>
                                    <th style="width: 22%;">
                                        <i class="bi bi-box me-1"></i>Nama Produk
                                    </th>
                                    <th style="width: 30%;">
                                        <i class="bi bi-file-text me-1"></i>Deskripsi
                                    </th>
                                    <th class="text-center" style="width: 100px;">
                                        <i class="bi bi-download me-1"></i>File
                                    </th>
                                    <th class="text-center" style="width: 120px;">
                                        <i class="bi bi-calendar me-1"></i>Tanggal Upload
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produk as $p)
                                    <tr>
                                        <td class="text-center fw-semibold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 35px; height: 35px; min-width: 35px;">
                                                    <strong>{{ substr($p->pelajar->nama, 0, 1) }}</strong>
                                                </div>
                                                <span class="fw-semibold">{{ $p->pelajar->nama }}</span>
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-dark">{{ $p->nama_produk }}</td>
                                        <td class="text-muted">
                                            @if ($p->deskripsi)
                                                <span class="d-inline-block text-truncate" style="max-width: 300px;"
                                                    title="{{ $p->deskripsi }}">
                                                    {{ $p->deskripsi }}
                                                </span>
                                            @else
                                                <span class="fst-italic text-secondary">Tidak ada deskripsi</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ asset('storage/' . $p->file_produk) }}"
                                                class="btn btn-sm btn-success shadow-sm" target="_blank"
                                                title="Download File">
                                                <i class="bi bi-download me-1"></i>Download
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary px-3 py-2">
                                                <i class="bi bi-calendar3 me-1"></i>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

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
                        "next": ">",
                        "previous": "<"
                    }
                },
                "pageLength": 10,
                "order": [
                    [5, "desc"]
                ], // Sort by date (newest first)
                "columnDefs": [{
                        "orderable": false,
                        "targets": 4
                    } // Disable sorting on download column
                ]
            });
        });
    </script>
@endpush
