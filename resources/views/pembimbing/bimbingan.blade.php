@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <!-- Header Section -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="bg-primary rounded p-2">
                            <i class="bi bi-person text-white fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">Daftar Peserta Bimbingan</h3>
                        <p class="text-muted mb-0">Kelola dan lihat detail peserta bimbingan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
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
                        <i class="bi bi-list-check me-2"></i>Data Peserta Bimbingan
                    </h5>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card-body bg-light border-bottom py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInputBimbingan" class="form-control border-start-0" 
                                    placeholder="Cari nama, institusi, atau jurusan...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="lengthMenuBimbingan" class="form-select">
                            <option value="10">10 data</option>
                            <option value="25">25 data</option>
                            <option value="50">50 data</option>
                            <option value="100">100 data</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="bimbinganTable" class="table table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th class="py-3 px-3 text-center" style="width: 80px;">No</th>
                                <th class="py-3 px-3 text-center">Nama Peserta</th>
                                <th class="py-3 px-3 text-center">Asal Institusi</th>
                                <th class="py-3 px-3 text-center">Jurusan</th>
                                <th class="py-3 px-3 text-center" style="width: 140px;">Status Magang</th>
                                <th class="py-3 px-3 text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($pelajars))
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">Belum ada peserta bimbingan</p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($pelajars as $key => $p)
                                    <tr>
                                        <td class="text-center fw-medium py-3">{{ $key + 1 }}</td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                                    <span class="fw-bold">{{ strtoupper(substr($p->nama, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $p->nama }}</div>
                                                    <small class="text-muted">{{ $p->nim_nisn ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">{{ $p->asal_institusi }}</td>
                                        <td class="py-3">
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 border-0">
                                                {{ $p->jurusan }}
                                            </span>
                                        </td>
                                        <td class="text-center py-3">
                                            @php
                                                $statusRaw = strtolower($p->status_magang ?? $p->status ?? '');
                                                $isSelesai = $statusRaw === 'selesai'
                                                    || ($p->rencana_selesai && \Carbon\Carbon::parse($p->rencana_selesai)->isPast());
                                            @endphp

                                            @if ($isSelesai)
                                                <span class="badge bg-success px-3 py-2">Selesai Magang</span>
                                            @elseif (in_array($statusRaw, ['disetujui', 'aktif']))
                                                <span class="badge bg-primary px-3 py-2">Aktif</span>
                                            @elseif ($statusRaw === 'menunggu')
                                                <span class="badge bg-warning text-dark px-3 py-2">Menunggu</span>
                                            @elseif ($statusRaw === 'ditolak')
                                                <span class="badge bg-danger px-3 py-2">Ditolak</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3">
                                            <button class="btn btn-primary btn-sm detail-btn"
                                                data-id="{{ $p->id }}"
                                                data-nama="{{ $p->nama }}"
                                                data-jenis-kelamin="{{ $p->jenis_kelamin }}"
                                                data-tempat-lahir="{{ $p->tempat_lahir }}"
                                                data-tanggal-lahir="{{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('d F Y') : '-' }}"
                                                data-alamat="{{ $p->alamat }}"
                                                data-telepon="{{ $p->telepon }}"
                                                data-email="{{ $p->email }}"
                                                data-nim-nisn="{{ $p->nim_nisn }}"
                                                data-asal-institusi="{{ $p->asal_institusi }}"
                                                data-fakultas="{{ $p->fakultas }}"
                                                data-jurusan="{{ $p->jurusan }}"
                                                data-rencana-mulai="{{ $p->rencana_mulai ? \Carbon\Carbon::parse($p->rencana_mulai)->format('d F Y') : '-' }}"
                                                data-rencana-selesai="{{ $p->rencana_selesai ? \Carbon\Carbon::parse($p->rencana_selesai)->format('d F Y') : '-' }}"
                                                data-proposal="{{ $p->proposal }}"
                                                data-surat-pengajuan="{{ $p->surat_pengajuan }}">
                                                <i class="bi bi-eye me-1"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="detailModalLabel">
                            Detail Peserta Bimbingan
                        </h5>
                        <small class="opacity-75" id="modal-subtitle"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <!-- Personal Information -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                            <i class="bi bi-person me-2"></i>Informasi Pribadi
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Nama Lengkap</label>
                                    <div class="fw-semibold" id="modal-nama"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Jenis Kelamin</label>
                                    <div class="fw-semibold" id="modal-jenis-kelamin"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Tempat Lahir</label>
                                    <div class="fw-semibold" id="modal-tempat-lahir"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Tanggal Lahir</label>
                                    <div class="fw-semibold" id="modal-tanggal-lahir"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-item">
                                    <label class="text-muted small">Alamat</label>
                                    <div class="fw-semibold" id="modal-alamat"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                            <i class="bi bi-phone me-2"></i>Informasi Kontak
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">No. Telepon</label>
                                    <div class="fw-semibold" id="modal-telepon"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Email</label>
                                    <div class="fw-semibold" id="modal-email"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                            <i class="bi bi-mortarboard me-2"></i>Informasi Akademik
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">NIM / NISN</label>
                                    <div class="fw-semibold" id="modal-nim-nisn"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Asal Institusi</label>
                                    <div class="fw-semibold" id="modal-asal-institusi"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Fakultas</label>
                                    <div class="fw-semibold" id="modal-fakultas"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Jurusan</label>
                                    <div class="fw-semibold" id="modal-jurusan"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                            <i class="bi bi-calendar-week me-2"></i>Jadwal Bimbingan
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Rencana Mulai</label>
                                    <div class="fw-semibold" id="modal-rencana-mulai"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Rencana Selesai</label>
                                    <div class="fw-semibold" id="modal-rencana-selesai"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div>
                        <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                            <i class="bi bi-file-earmark-check me-2"></i>Dokumen
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="bi bi-file-pdf text-primary fs-5"></i>
                                            </div>
                                            <div class="flex-1">
                                                <small class="text-muted d-block">Proposal</small>
                                                <div id="modal-proposal-link"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="bi bi-file-pdf text-primary fs-5"></i>
                                            </div>
                                            <div class="flex-1">
                                                <small class="text-muted d-block">Surat Pengajuan</small>
                                                <div id="modal-surat-link"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const bimbinganTable = $('#bimbinganTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    pageLength: 10,
                    language: {
                        search: "",
                        searchPlaceholder: "Cari peserta...",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        zeroRecords: "Tidak ada data yang cocok",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "›",
                            previous: "‹"
                        },
                    },
                    dom: 't',
                    autoWidth: false,
                    responsive: false,
                    columnDefs: [
                        { targets: '_all', orderable: true }
                    ]
                });

                // Input pencarian custom
                $('#searchInputBimbingan').on('keyup', function() {
                    bimbinganTable.search(this.value).draw();
                });

                // Dropdown jumlah data per halaman
                $('#lengthMenuBimbingan').on('change', function() {
                    bimbinganTable.page.len(parseInt(this.value)).draw();
                });

                // Handle detail button click
                $('.detail-btn').on('click', function() {
                    const btn = $(this);
                    const data = btn.data();

                    // Fill modal with data
                    $('#modal-subtitle').text(data.nama);
                    $('#modal-nama').text(data.nama);
                    $('#modal-jenis-kelamin').text(data.jenisKelamin);
                    $('#modal-tempat-lahir').text(data.tempatLahir);
                    $('#modal-tanggal-lahir').text(data.tanggalLahir);
                    $('#modal-alamat').text(data.alamat);
                    $('#modal-telepon').text(data.telepon);
                    $('#modal-email').text(data.email);
                    $('#modal-nim-nisn').text(data.nimNisn || '-');
                    $('#modal-asal-institusi').text(data.asalInstitusi);
                    $('#modal-fakultas').text(data.fakultas || '-');
                    $('#modal-jurusan').text(data.jurusan);
                    $('#modal-rencana-mulai').text(data.rencanaMulai);
                    $('#modal-rencana-selesai').text(data.rencanaSelesai);

                    // Handle proposal link
                    if (data.proposal) {
                        $('#modal-proposal-link').html(`
                            <a href="/storage/${data.proposal}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                <i class="bi bi-download me-1"></i>Unduh
                            </a>
                        `);
                    } else {
                        $('#modal-proposal-link').html('<span class="text-muted small">Belum tersedia</span>');
                    }

                    // Handle surat pengajuan link
                    if (data.suratPengajuan) {
                        $('#modal-surat-link').html(`
                            <a href="/storage/${data.suratPengajuan}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                <i class="bi bi-download me-1"></i>Unduh
                            </a>
                        `);
                    } else {
                        $('#modal-surat-link').html('<span class="text-muted small">Belum tersedia</span>');
                    }

                    // Show modal
                    $('#detailModal').modal('show');
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .avatar-circle {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
            }

            .info-item {
                margin-bottom: 0.5rem;
            }

            .info-item label {
                font-size: 0.875rem;
                margin-bottom: 0.25rem;
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
        </style>
    @endpush
@endsection