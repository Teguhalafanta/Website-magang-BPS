@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <div class="row mb-3">
            <div class="col">
                <h3 class="fw-bold text-primary">
                    <i class="bi bi-gear-fill me-2"></i>Kelola Produk Magang Peserta
                </h3>
            </div>
        </div>

        {{-- Summary Statistics --}}
        <div class="row g-3 mb-2">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-0">
                                <i class="bi bi-box-seam fs-1 opacity-75"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-uppercase small opacity-75">Total Produk</h6>
                                <h3 class="mb-0 fw-bold">{{ $produk->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-0">
                                <i class="bi bi-people-fill fs-1 opacity-75"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-uppercase small opacity-75">Total Peserta</h6>
                                <h3 class="mb-0 fw-bold">{{ $produk->unique('pelajar_id')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-0">
                                <i class="bi bi-calendar-week fs-1 opacity-75"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-uppercase small opacity-75">Minggu Ini</h6>
                                <h3 class="mb-0 fw-bold">
                                    {{ $produk->where('created_at', '>=', now()->startOfWeek())->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-0">
                                <i class="bi bi-calendar-check fs-1 opacity-75"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-uppercase small opacity-75">Bulan Ini</h6>
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
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-table me-2 text-primary"></i>Daftar Produk Magang
                    </h5>
                    <span class="badge bg-primary rounded-pill fs-6">{{ $produk->count() }} Produk</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="text-center" style="width: 18%;">Nama Peserta</th>
                                <th class="text-center" style="width: 20%;">Nama Produk</th>
                                <th class="text-center" style="width: 25%;">Deskripsi</th>
                                <th class="text-center" style="width: 100px;">File</th>
                                <th class="text-center" style="width: 120px;">Tanggal</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
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
                                                <strong>{{ substr($p->pelajar->nama ?? 'T', 0, 1) }}</strong>
                                            </div>
                                            <span class="fw-semibold">{{ $p->pelajar->nama ?? 'Tidak diketahui' }}</span>
                                        </div>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $p->nama_produk }}</td>
                                    <td class="text-muted">
                                        @if ($p->deskripsi)
                                            <span class="d-inline-block text-truncate" style="max-width: 250px;"
                                                title="{{ $p->deskripsi }}">
                                                {{ $p->deskripsi }}
                                            </span>
                                        @else
                                            <span class="fst-italic text-secondary">Tidak ada deskripsi</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ asset('storage/' . $p->file_produk) }}" target="_blank"
                                            class="btn btn-sm btn-success shadow-sm" title="Download File">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $p->created_at->format('d-m-Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.produk.edit', $p->id) }}"
                                                class="btn btn-sm btn-warning shadow-sm" title="Edit Produk">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger shadow-sm" title="Hapus Produk">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Info Card --}}
        <div class="card border-0 bg-light mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-2">
                            <i class="bi bi-info-circle text-primary me-2"></i>Informasi:
                        </h6>
                        <ul class="small text-muted mb-md-0">
                            <li>Gunakan fitur pencarian untuk menemukan produk tertentu</li>
                            <li>Klik nama kolom untuk mengurutkan data</li>
                            <li>Data dapat difilter berdasarkan periode waktu</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-2">
                            <i class="bi bi-shield-check text-success me-2"></i>Aksi Admin:
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>Edit untuk memperbarui informasi produk</li>
                            <li>Hapus untuk menghapus produk dari sistem</li>
                            <li>Download untuk melihat file produk</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
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
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [4, 6]
                }]
            });
        });
    </script>
@endpush
