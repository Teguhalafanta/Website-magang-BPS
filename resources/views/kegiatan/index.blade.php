@extends('kerangka.master')

@section('title', 'Daftar Kegiatan Saya')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4 text-primary">Daftar Kegiatan Saya</h2>

        {{-- Flash message --}}
        <div id="flash-message"></div>

        {{-- Form tambah kegiatan --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white fw-bold">Tambah Kegiatan Baru</div>
            <div class="card-body">
                <form id="formTambahKegiatan">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control" required>
                            <small class="text-danger" id="error-nama_kegiatan"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                            <small class="text-danger" id="error-tanggal"></small>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" class="form-control" required>
                            <small class="text-danger" id="error-deskripsi"></small>
                        </div>

                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary fw-bold">Tambah Kegiatan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel kegiatan --}}
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white fw-bold">Tabel Kegiatan Saya</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0" id="tableKegiatan">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kegiatans as $index => $kegiatan)
                            <tr id="kegiatan-{{ $kegiatan->id }}">
                                <td>{{ $index + $kegiatans->firstItem() }}</td>
                                <td class="nama">{{ $kegiatan->nama_kegiatan }}</td>
                                <td class="tanggal text-center">{{ $kegiatan->tanggal }}</td>
                                <td class="deskripsi">{{ $kegiatan->deskripsi }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning me-1 btnEdit"
                                        data-id="{{ $kegiatan->id }}">Edit</button>
                                    <button class="btn btn-sm btn-danger btnHapus"
                                        data-id="{{ $kegiatan->id }}">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex justify-content-end">
                {{ $kegiatans->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditKegiatan">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalEditLabel">Edit Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="edit-nama_kegiatan" class="form-control"
                                required>
                            <small class="text-danger" id="error-edit-nama_kegiatan"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="edit-tanggal" class="form-control" required>
                            <small class="text-danger" id="error-edit-tanggal"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" id="edit-deskripsi" class="form-control" required>
                            <small class="text-danger" id="error-edit-deskripsi"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- AJAX Script --}}
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));

            // Tambah kegiatan live
            document.getElementById('formTambahKegiatan').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                const data = new FormData(form);

                ['nama_kegiatan', 'tanggal', 'deskripsi'].forEach(id => document.getElementById('error-' +
                    id).innerText = '');

                fetch("{{ route('kegiatan.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: data
                    }).then(res => res.json())
                    .then(res => {
                        if (res.errors) {
                            Object.keys(res.errors).forEach(key => {
                                document.getElementById('error-' + key).innerText = res.errors[
                                    key][0];
                            });
                        } else {
                            // Tambahkan baris baru ke tabel
                            const tbody = document.querySelector('#tableKegiatan tbody');
                            const index = tbody.children.length + 1;
                            const tr = document.createElement('tr');
                            tr.id = 'kegiatan-' + res.kegiatan.id;
                            tr.innerHTML = `
                <td>${index}</td>
                <td class="nama">${res.kegiatan.nama_kegiatan}</td>
                <td class="tanggal text-center">${res.kegiatan.tanggal}</td>
                <td class="deskripsi">${res.kegiatan.deskripsi}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning me-1 btnEdit" data-id="${res.kegiatan.id}">Edit</button>
                    <button class="btn btn-sm btn-danger btnHapus" data-id="${res.kegiatan.id}">Hapus</button>
                </td>`;
                            tbody.appendChild(tr);
                            form.reset();
                        }
                    });
            });

            // Edit kegiatan live (modal sama seperti sebelumnya)
            document.querySelector('#tableKegiatan').addEventListener('click', function(e) {
                if (e.target.classList.contains('btnEdit')) {
                    const id = e.target.dataset.id;
                    fetch(`/kegiatan/${id}/edit`).then(res => res.json())
                        .then(k => {
                            document.getElementById('edit-id').value = k.id;
                            document.getElementById('edit-nama_kegiatan').value = k.nama_kegiatan;
                            document.getElementById('edit-tanggal').value = k.tanggal;
                            document.getElementById('edit-deskripsi').value = k.deskripsi;
                            modalEdit.show();
                        });
                } else if (e.target.classList.contains('btnHapus')) {
                    if (confirm('Hapus kegiatan ini?')) {
                        const id = e.target.dataset.id;
                        fetch(`/kegiatan/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(res => res.json())
                            .then(r => {
                                if (r.success) {
                                    document.getElementById('kegiatan-' + r.id).remove();
                                    // update index
                                    document.querySelectorAll('#tableKegiatan tbody tr').forEach((tr,
                                        i) => {
                                        tr.children[0].innerText = i + 1
                                    });
                                }
                            });
                    }
                }
            });

            // Submit edit live
            document.getElementById('formEditKegiatan').addEventListener('submit', function(e) {
                e.preventDefault();
                const id = document.getElementById('edit-id').value;
                const data = new FormData(e.target);
                ['nama_kegiatan', 'tanggal', 'deskripsi'].forEach(k => document.getElementById(
                    'error-edit-' + k).innerText = '');

                fetch(`/kegiatan/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-HTTP-Method-Override': 'PUT'
                        },
                        body: data
                    }).then(res => res.json())
                    .then(res => {
                        if (res.errors) {
                            Object.keys(res.errors).forEach(k => {
                                document.getElementById('error-edit-' + k).innerText = res
                                    .errors[k][0];
                            });
                        } else {
                            // update row
                            const tr = document.getElementById('kegiatan-' + id);
                            tr.querySelector('.nama').innerText = res.kegiatan.nama_kegiatan;
                            tr.querySelector('.tanggal').innerText = res.kegiatan.tanggal;
                            tr.querySelector('.deskripsi').innerText = res.kegiatan.deskripsi;
                            modalEdit.hide();
                        }
                    });
            });
        });
    </script>
@endsection
