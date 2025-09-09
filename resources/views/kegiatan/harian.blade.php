@extends('kerangka.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3>Kegiatan Harian</h3>
                        <p class="text-muted">Tanggal: {{ now()->format('d-m-Y') }}</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal">
                        <i class="fas fa-plus"></i> Tambah Kegiatan
                    </button>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        @if ($kegiatans->isEmpty())
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i>
                                Tidak ada kegiatan untuk hari ini.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Kegiatan</th>
                                            <th>Deskripsi</th>
                                            <th>Volume</th>
                                            <th>Satuan</th>
                                            <th>Durasi</th>
                                            <th>Pemberi Tugas</th>
                                            <th>Tim Kerja</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kegiatans as $index => $kegiatan)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                                                <td>{{ $kegiatan->nama_kegiatan }}</td>
                                                <td>{{ Str::limit($kegiatan->deskripsi, 50) }}</td>
                                                <td>{{ $kegiatan->volume ?? '-' }}</td>
                                                <td>{{ $kegiatan->satuan ?? '-' }}</td>
                                                <td>{{ $kegiatan->durasi ?? '-' }} menit</td>
                                                <td>{{ $kegiatan->pemberi_tugas ?? '-' }}</td>
                                                <td>{{ $kegiatan->tim_kerja ?? '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $kegiatan->status_penyelesaian == 'Selesai' ? 'success' : ($kegiatan->status_penyelesaian == 'Dalam Proses' ? 'warning' : 'secondary') }}">
                                                        {{ $kegiatan->status_penyelesaian }}
                                                    </span>
                                                </td>
                                                <td style="white-space: nowrap; text-align: center;">
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                                        onclick="editKegiatan({{ $kegiatan->id }})">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="hapusKegiatan({{ $kegiatan->id }})">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            @if (method_exists($kegiatans, 'links'))
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $kegiatans->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahKegiatanModal" tabindex="-1" aria-labelledby="tambahKegiatanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('pelajar.kegiatan.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="volume" class="form-label">Volume</label>
                        <input type="number" name="volume" class="form-control" min="0" value="0">
                    </div>

                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="durasi" class="form-label">Durasi (menit)</label>
                        <input type="number" name="durasi" class="form-control" min="1">
                    </div>

                    <div class="mb-3">
                        <label for="pemberi_tugas" class="form-label">Pemberi Tugas</label>
                        <input type="text" name="pemberi_tugas" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="tim_kerja" class="form-label">Tim Kerja</label>
                        <input type="text" name="tim_kerja" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Belum">Belum</option>
                            <option value="Proses">Proses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editKegiatanModal" tabindex="-1" aria-labelledby="editKegiatanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" class="modal-content" id="formEditKegiatan">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editKegiatanContent">
                    <!-- Diisi via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
    <script>
        function editKegiatan(id) {
            // Redirect ke halaman edit sesuai route Laravel pelajar.kegiatan.edit
            window.location.href = `/pelajar/kegiatan/${id}/edit`;
        }

        function hapusKegiatan(id) {
            if (confirm('Yakin ingin menghapus kegiatan ini?')) {
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    alert('CSRF token tidak ditemukan! Pastikan meta tag ada di halaman.');
                    return;
                }
                const csrfToken = csrfTokenMeta.getAttribute('content');

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pelajar/kegiatan/${id}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
