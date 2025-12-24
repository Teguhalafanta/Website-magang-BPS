@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-people-fill me-2"></i>Manajemen User
            </h3>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah User
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="row g-3 mb-2">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Admin</p>
                                <h4 class="mb-0 fw-bold">{{ $users->where('role', 'admin')->count() }}</h4>
                            </div>
                            <i class="bi bi-shield-fill-check fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Pembimbing</p>
                                <h4 class="mb-0 fw-bold">{{ $users->where('role', 'pembimbing')->count() }}</h4>
                            </div>
                            <i class="bi bi-person-badge fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-1 small text-uppercase">Peserta</p>
                                <h4 class="mb-0 fw-bold">{{ $users->where('role', 'pelajar')->count() }}</h4>
                            </div>
                            <i class="bi bi-mortarboard fs-3 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 text-muted">
                    Total: <span class="badge bg-primary">{{ $users->count() }}</span> user
                </h6>
            </div>

            <div class="card-body p-0">
                @if ($users->count() > 0)
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th class="ps-3" style="width: 5%;">No</th>
                                    <th style="width: 25%;">Username</th>
                                    <th style="width: 25%;">Email</th>
                                    <th style="width: 15%;">Role</th>
                                    <th style="width: 18%;">Tanggal Dibuat</th>
                                    <th style="width: 12%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td class="text-center ps-3">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div class="fw-semibold">{{ $user->username }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">
                                            @if ($user->role == 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @elseif($user->role == 'pembimbing')
                                                <span class="badge bg-success">Pembimbing</span>
                                            @else
                                                <span class="badge bg-primary">Peserta</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada user</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <style>
            .table> :not(caption)>*>* {
                padding: 0.75rem 0.5rem;
                vertical-align: middle;
            }

            .avatar-sm {
                flex-shrink: 0;
            }

            #usersTable_wrapper .dataTables_filter {
                float: right;
                margin-bottom: 1rem;
                padding: 0 1rem;
            }

            #usersTable_wrapper .dataTables_length {
                float: left;
                margin-bottom: 1rem;
                padding: 0 1rem;
            }

            #usersTable_wrapper .dataTables_info {
                padding: 1rem;
                color: #6c757d;
            }

            #usersTable_wrapper .dataTables_paginate {
                padding: 1rem;
            }

            #usersTable_wrapper .dataTables_filter input {
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                padding: 0.375rem 0.75rem;
                margin-left: 0.5rem;
                background-color: #f8f9fa;
            }

            #usersTable_wrapper .dataTables_filter input:focus {
                background-color: #fff;
                border-color: #0d6efd;
                outline: 0;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            #usersTable_wrapper .dataTables_length select {
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                padding: 0.375rem 2rem 0.375rem 0.75rem;
                margin: 0 0.5rem;
                background-color: #f8f9fa;
            }

            #usersTable_wrapper .dataTables_length select:focus {
                background-color: #fff;
                border-color: #0d6efd;
                outline: 0;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            #usersTable_wrapper .dataTables_paginate .pagination {
                margin-bottom: 0;
            }

            #usersTable_wrapper .dataTables_paginate .page-link {
                background-color: #f8f9fa;
                border-color: #dee2e6;
                color: #0d6efd;
            }

            #usersTable_wrapper .dataTables_paginate .page-link:hover {
                background-color: #e9ecef;
                border-color: #dee2e6;
            }

            #usersTable_wrapper .dataTables_paginate .page-item.active .page-link {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }

            #usersTable_wrapper .dataTables_paginate .page-item.disabled .page-link {
                background-color: #f8f9fa;
                border-color: #dee2e6;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#usersTable').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        },
                        zeroRecords: "Tidak ada data yang ditemukan",
                        emptyTable: "Tidak ada data tersedia"
                    },
                    pageLength: 10,
                    ordering: true,
                    columnDefs: [{
                        orderable: false,
                        targets: [0, 5]
                    }]
                });
            });
        </script>
    @endpush
@endsection
