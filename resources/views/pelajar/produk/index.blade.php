@extends('kerangka.master')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h3 class="fw-bold text-primary">Produk Magang Saya</h3>
                <p class="text-muted">Kelola dan pantau produk hasil magang Anda</p>
            </div>
        </div>

        {{-- NOTIFIKASI BERHASIL --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-3">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @php
            $status = auth()->check() && auth()->user()->pelajar ? auth()->user()->pelajar->status : null;
        @endphp

        <div class="row mb-3">
            <div class="col">
                <a href="{{ route('pelajar.produk.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>Upload Produk Baru
                </a>
            </div>
        </div>

        @if ($produk->count() == 0)
            <div class="alert alert-info border-0 shadow-sm" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                Belum ada produk yang diunggah. Mulai upload produk pertama Anda!
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableProduk">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th style="width: 20%;">Nama Produk</th>
                                    <th style="width: 35%;">Deskripsi</th>
                                    <th class="text-center" style="width: 80px;">File</th>
                                    <th class="text-center" style="width: 120px;">Tanggal Upload</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produk as $p)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ $p->nama_produk }}</td>
                                        <td class="text-muted">{{ $p->deskripsi ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ asset('storage/' . $p->file_produk) }}" target="_blank"
                                                class="btn btn-sm btn-success shadow-sm" title="Download File">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                {{ $p->created_at->format('d-m-Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ asset('storage/' . $p->file_produk) }}" target="_blank"
                                                    class="btn btn-sm btn-success shadow-sm" title="Download File">
                                                    <i class="bi bi-download"></i>
                                                </a>

                                                @if ($status !== 'selesai')
                                                    <a href="{{ route('pelajar.produk.edit', $p->id) }}"
                                                        class="btn btn-sm btn-warning shadow-sm" title="Edit Produk">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <form action="{{ route('pelajar.produk.destroy', $p->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger shadow-sm"
                                                            title="Hapus Produk">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Jika selesai magang, sembunyikan tombol edit/hapus --}}
                                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                                        title="Tidak dapat mengubah setelah selesai magang">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                                        title="Tidak dapat menghapus setelah selesai magang">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                @endif
                                            </div>
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
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ditemukan data",
                    "search": "Cari:",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 - 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": ">",
                        "previous": "<"
                    }
                },
                "pageLength": 10,
                "order": [
                    [4, "desc"]
                ]
            });
        });
    </script>
@endpush
