@extends('kerangka.master')

@section('title', 'Daftar Kegiatan Saya')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4 text-primary">Daftar Kegiatan Saya</h2>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="flash-message"></div>

        {{-- Form tambah kegiatan --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white fw-bold">
                <i class="fas fa-plus-circle me-2"></i>Tambah Kegiatan Baru
            </div>
            <div class="card-body">
                <form id="formTambahKegiatan">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" class="form-control"
                                placeholder="Masukkan nama kegiatan" required>
                            <small class="text-danger" id="error-nama_kegiatan"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                            <small class="text-danger" id="error-tanggal"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status Penyelesaian <span class="text-danger">*</span></label>
                            <select name="status_penyelesaian" class="form-select" required>
                                <option value="">Pilih Status</option>
                                <option value="Belum Dimulai">Belum Dimulai</option>
                                <option value="Dalam Proses">Dalam Proses</option>
                                <option value="Selesai" selected>Selesai</option>
                            </select>
                            <small class="text-danger" id="error-status_penyelesaian"></small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Jelaskan kegiatan yang dilakukan" required></textarea>
                            <small class="text-danger" id="error-deskripsi"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Volume</label>
                            <input type="text" name="volume" class="form-control" placeholder="Contoh: 5">
                            <small class="text-danger" id="error-volume"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Satuan</label>
                            <select name="satuan" class="form-select">
                                <option value="">Pilih Satuan</option>
                                <option value="Dokumen">Dokumen</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Unit">Unit</option>
                                <option value="Paket">Paket</option>
                                <option value="Buah">Buah</option>
                                <option value="Set">Set</option>
                                <option value="File">File</option>
                            </select>
                            <small class="text-danger" id="error-satuan"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Durasi (menit)</label>
                            <input type="number" name="durasi" class="form-control" min="1"
                                placeholder="Contoh: 120">
                            <small class="text-danger" id="error-durasi"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Pemberi Tugas</label>
                            <input type="text" name="pemberi_tugas" class="form-control"
                                placeholder="Nama pemberi tugas">
                            <small class="text-danger" id="error-pemberi_tugas"></small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Tim Kerja</label>
                            <input type="text" name="tim_kerja" class="form-control"
                                placeholder="Anggota tim yang terlibat">
                            <small class="text-danger" id="error-tim_kerja"></small>
                        </div>

                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="fas fa-save me-1"></i>Tambah Kegiatan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel kegiatan --}}
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white fw-bold">
                <i class="fas fa-table me-2"></i>Tabel Kegiatan Saya
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="tableKegiatan">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Kegiatan</th>
                                <th width="110">Tanggal</th>
                                <th>Deskripsi</th>
                                <th width="80">Volume</th>
                                <th width="80">Satuan</th>
                                <th width="90">Durasi</th>
                                <th width="120">Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kegiatans as $index => $kegiatan)
                                <tr id="kegiatan-{{ $kegiatan->id }}">
                                    <td class="text-center">{{ $index + $kegiatans->firstItem() }}</td>
                                    <td class="nama">{{ $kegiatan->nama_kegiatan }}</td>
                                    <td class="tanggal text-center">
                                        {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                                    <td class="deskripsi">{{ Str::limit($kegiatan->deskripsi, 50) }}</td>
                                    <td class="text-center volume">{{ $kegiatan->volume ?? '-' }}</td>
                                    <td class="text-center satuan">{{ $kegiatan->satuan ?? '-' }}</td>
                                    <td class="text-center durasi">
                                        {{ $kegiatan->durasi ? $kegiatan->durasi . ' mnt' : '-' }}</td>
                                    <td class="text-center status">
                                        <span
                                            class="badge bg-{{ $kegiatan->status_penyelesaian == 'Selesai' ? 'success' : ($kegiatan->status_penyelesaian == 'Dalam Proses' ? 'warning' : 'secondary') }}">
                                            {{ $kegiatan->status_penyelesaian }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-warning btnEdit" data-id="{{ $kegiatan->id }}"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btnHapus" data-id="{{ $kegiatan->id }}"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada kegiatan yang tercatat</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($kegiatans->hasPages())
                <div class="card-footer d-flex justify-content-center">
                    {{ $kegiatans->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEditKegiatan">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalEditLabel">
                            <i class="fas fa-edit me-2"></i>Edit Kegiatan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kegiatan" id="edit-nama_kegiatan" class="form-control"
                                    required>
                                <small class="text-danger" id="error-edit-nama_kegiatan"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" id="edit-tanggal" class="form-control" required>
                                <small class="text-danger" id="error-edit-tanggal"></small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" id="edit-deskripsi" class="form-control" rows="2" required></textarea>
                                <small class="text-danger" id="error-edit-deskripsi"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Volume</label>
                                <input type="text" name="volume" id="edit-volume" class="form-control">
                                <small class="text-danger" id="error-edit-volume"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Satuan</label>
                                <select name="satuan" id="edit-satuan" class="form-select">
                                    <option value="">Pilih Satuan</option>
                                    <option value="Dokumen">Dokumen</option>
                                    <option value="Lembar">Lembar</option>
                                    <option value="Unit">Unit</option>
                                    <option value="Paket">Paket</option>
                                    <option value="Buah">Buah</option>
                                    <option value="Set">Set</option>
                                    <option value="File">File</option>
                                </select>
                                <small class="text-danger" id="error-edit-satuan"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Durasi (menit)</label>
                                <input type="number" name="durasi" id="edit-durasi" class="form-control"
                                    min="1">
                                <small class="text-danger" id="error-edit-durasi"></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pemberi Tugas</label>
                                <input type="text" name="pemberi_tugas" id="edit-pemberi_tugas" class="form-control">
                                <small class="text-danger" id="error-edit-pemberi_tugas"></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Penyelesaian</label>
                                <select name="status_penyelesaian" id="edit-status_penyelesaian" class="form-select">
                                    <option value="">Pilih Status</option>
                                    <option value="Belum Dimulai">Belum Dimulai</option>
                                    <option value="Dalam Proses">Dalam Proses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                                <small class="text-danger" id="error-edit-status_penyelesaian"></small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tim Kerja</label>
                                <input type="text" name="tim_kerja" id="edit-tim_kerja" class="form-control"
                                    placeholder="Anggota tim yang terlibat">
                                <small class="text-danger" id="error-edit-tim_kerja"></small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .table th {
            white-space: nowrap;
        }

        .btn-group .btn {
            margin: 0 1px;
        }

        .badge {
            font-size: 0.8rem;
        }

        .card-header {
            font-weight: 600;
        }
    </style>
@endpush

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));

            // Helper function untuk clear error messages
            function clearErrors(prefix = '') {
                const fields = ['nama_kegiatan', 'tanggal', 'deskripsi', 'volume', 'satuan', 'durasi',
                    'pemberi_tugas', 'tim_kerja', 'status_penyelesaian'
                ];
                fields.forEach(field => {
                    const errorElement = document.getElementById(`error-${prefix}${field}`);
                    if (errorElement) {
                        errorElement.innerText = '';
                    }
                });
            }

            // Helper function untuk show flash message
            function showFlashMessage(message, type = 'success') {
                const flashContainer = document.getElementById('flash-message');
                flashContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
                // Auto hide after 5 seconds
                setTimeout(() => {
                    const alert = flashContainer.querySelector('.alert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }

            // Tambah kegiatan live
            document.getElementById('formTambahKegiatan').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                const data = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                // Clear previous errors
                clearErrors();

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';

                fetch("{{ url('/kegiatan') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: data
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.errors) {
                            Object.keys(res.errors).forEach(key => {
                                const errorElement = document.getElementById('error-' + key);
                                if (errorElement) {
                                    errorElement.innerText = res.errors[key][0];
                                }
                            });
                        } else if (res.success) {
                            // Tambahkan baris baru ke tabel
                            const tbody = document.querySelector('#tableKegiatan tbody');

                            // Hapus row "no data" jika ada
                            const noDataRow = tbody.querySelector('td[colspan="9"]');
                            if (noDataRow) {
                                noDataRow.closest('tr').remove();
                            }

                            const newIndex = tbody.children.length + 1;
                            const tr = document.createElement('tr');
                            tr.id = 'kegiatan-' + res.kegiatan.id;

                            const statusBadgeClass = res.kegiatan.status_penyelesaian == 'Selesai' ?
                                'success' :
                                (res.kegiatan.status_penyelesaian == 'Dalam Proses' ? 'warning' :
                                    'secondary');

                            tr.innerHTML = `
                        <td class="text-center">${newIndex}</td>
                        <td class="nama">${res.kegiatan.nama_kegiatan}</td>
                        <td class="tanggal text-center">${res.kegiatan.tanggal_formatted || res.kegiatan.tanggal}</td>
                        <td class="deskripsi">${res.kegiatan.deskripsi.length > 50 ? res.kegiatan.deskripsi.substring(0, 50) + '...' : res.kegiatan.deskripsi}</td>
                        <td class="text-center volume">${res.kegiatan.volume || '-'}</td>
                        <td class="text-center satuan">${res.kegiatan.satuan || '-'}</td>
                        <td class="text-center durasi">${res.kegiatan.durasi ? res.kegiatan.durasi + ' mnt' : '-'}</td>
                        <td class="text-center status">
                            <span class="badge bg-${statusBadgeClass}">${res.kegiatan.status_penyelesaian}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-warning btnEdit" data-id="${res.kegiatan.id}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btnHapus" data-id="${res.kegiatan.id}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                            tbody.appendChild(tr);
                            form.reset();

                            // Set default values
                            form.querySelector('input[name="tanggal"]').value = '{{ date('Y-m-d') }}';
                            form.querySelector('select[name="status_penyelesaian"]').value = 'Selesai';

                            showFlashMessage('Kegiatan berhasil ditambahkan!', 'success');
                        } else {
                            showFlashMessage('Terjadi kesalahan saat menyimpan data.', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFlashMessage('Terjadi kesalahan koneksi.', 'danger');
                    })
                    .finally(() => {
                        // Enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });

            // Event delegation untuk edit dan hapus
            document.querySelector('#tableKegiatan').addEventListener('click', function(e) {
                if (e.target.closest('.btnEdit')) {
                    const btn = e.target.closest('.btnEdit');
                    const id = btn.dataset.id;

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    fetch(`/pelajar/kegiatan/${id}/edit`)
                        .then(response => response.json())
                        .then(k => {
                            document.getElementById('edit-id').value = k.id;
                            document.getElementById('edit-nama_kegiatan').value = k.nama_kegiatan || '';
                            document.getElementById('edit-tanggal').value = k.tanggal || '';
                            document.getElementById('edit-deskripsi').value = k.deskripsi || '';
                            document.getElementById('edit-volume').value = k.volume || '';
                            document.getElementById('edit-satuan').value = k.satuan || '';
                            document.getElementById('edit-durasi').value = k.durasi || '';
                            document.getElementById('edit-pemberi_tugas').value = k.pemberi_tugas || '';
                            document.getElementById('edit-tim_kerja').value = k.tim_kerja || '';
                            document.getElementById('edit-status_penyelesaian').value = k
                                .status_penyelesaian || '';

                            clearErrors('edit-');
                            modalEdit.show();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showFlashMessage('Gagal memuat data kegiatan.', 'danger');
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-edit"></i>';
                        });

                } else if (e.target.closest('.btnHapus')) {
                    if (confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')) {
                        const btn = e.target.closest('.btnHapus');
                        const id = btn.dataset.id;

                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                        fetch(`/pelajar/kegiatan/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(r => {
                                if (r.success) {
                                    document.getElementById('kegiatan-' + r.id).remove();

                                    // Update nomor urut
                                    document.querySelectorAll('#tableKegiatan tbody tr').forEach((tr,
                                        i) => {
                                            const firstCell = tr.children[0];
                                            if (firstCell && !firstCell.hasAttribute('colspan')) {
                                                firstCell.innerText = i + 1;
                                            }
                                        });

                                    // Tampilkan pesan "no data" jika tidak ada data
                                    const tbody = document.querySelector('#tableKegiatan tbody');
                                    if (tbody.children.length === 0) {
                                        tbody.innerHTML = `
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 mb-0">Belum ada kegiatan yang tercatat</p>
                                        </td>
                                    </tr>
                                `;
                                    }

                                    showFlashMessage('Kegiatan berhasil dihapus!', 'success');
                                } else {
                                    showFlashMessage('Gagal menghapus kegiatan.', 'danger');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showFlashMessage('Terjadi kesalahan saat menghapus.', 'danger');
                            })
                            .finally(() => {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fas fa-trash"></i>';
                            });
                    }
                }
            });

            // Submit edit kegiatan
            document.getElementById('formEditKegiatan').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                const id = document.getElementById('edit-id').value;
                const data = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                // Clear previous errors
                clearErrors('edit-');

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';

                fetch(`/pelajar/kegiatan/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-HTTP-Method-Override': 'PUT'
                        },
                        body: data
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.errors) {
                            Object.keys(res.errors).forEach(k => {
                                const errorElement = document.getElementById('error-edit-' + k);
                                if (errorElement) {
                                    errorElement.innerText = res.errors[k][0];
                                }
                            });
                        } else if (res.success) {
                            // Update row in table
                            const tr = document.getElementById('kegiatan-' + id);
                            if (tr) {
                                const statusBadgeClass = res.kegiatan.status_penyelesaian == 'Selesai' ?
                                    'success' :
                                    (res.kegiatan.status_penyelesaian == 'Dalam Proses' ? 'warning' :
                                        'secondary');

                                tr.querySelector('.nama').innerText = res.kegiatan.nama_kegiatan;
                                tr.querySelector('.tanggal').innerText = res.kegiatan
                                    .tanggal_formatted || res.kegiatan.tanggal;
                                tr.querySelector('.deskripsi').innerText = res.kegiatan.deskripsi
                                    .length > 50 ?
                                    res.kegiatan.deskripsi.substring(0, 50) + '...' : res.kegiatan
                                    .deskripsi;
                                tr.querySelector('.volume').innerText = res.kegiatan.volume || '-';
                                tr.querySelector('.satuan').innerText = res.kegiatan.satuan || '-';
                                tr.querySelector('.durasi').innerText = res.kegiatan.durasi ? res
                                    .kegiatan.durasi + ' mnt' : '-';
                                tr.querySelector('.status').innerHTML =
                                    `<span class="badge bg-${statusBadgeClass}">${res.kegiatan.status_penyelesaian}</span>`;
                            }

                            modalEdit.hide();
                            showFlashMessage('Kegiatan berhasil diperbarui!', 'success');
                        } else {
                            showFlashMessage('Terjadi kesalahan saat mengupdate data.', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFlashMessage('Terjadi kesalahan koneksi.', 'danger');
                    })
                    .finally(() => {
                        // Enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });

            // Auto close alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert:not(#flash-message .alert)');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection
