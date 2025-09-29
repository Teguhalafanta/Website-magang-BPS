@extends('kerangka.master')

@section('title', 'Profile')

@section('content')
    <div class="container mt-4">

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Alert Error --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Card Profil --}}
        <div class="card shadow-sm border-0 p-4">
            <h4 class="fw-bold mb-4">Profil Mahasiswa Magang</h4>
            <div class="row">
                <!-- Foto Profil -->
                <div class="col-md-3 text-center">
                    {{-- Tampilkan foto profil --}}
                    @if (Auth::user()->foto)
                        <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('images/default-avatar.png') }}"
                            alt="Foto Profil" class="rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Default Foto" class="rounded-circle border"
                            width="150" height="150">
                    @endif
                    <br>
                    <!-- Tombol Ubah Foto -->
                    <button class="btn btn-sm btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#ubahFotoModal">
                        Ubah Foto
                    </button>
                </div>

                <!-- Data Pribadi -->
                <div class="col-md-9">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">Data Pribadi</h5>
                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editPribadiModal">
                            <i class="fas fa-pen"></i>
                        </button>
                    </div>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="fw-bold">Nama</td>
                                <td>{{ optional($user->pelajar)->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">NIM / NISN</td>
                                <td>{{ optional($user->pelajar)->nim_nisn ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Fakultas</td>
                                <td>{{ optional($user->pelajar)->fakultas ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jurusan</td>
                                <td>{{ optional($user->pelajar)->jurusan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Asal Institusi</td>
                                <td>{{ optional($user->pelajar)->asal_institusi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Telepon</td>
                                <td>{{ optional($user->pelajar)->telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <!-- Informasi Magang -->
            @if ($user->role === 'pelajar')
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold">Informasi Magang</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editMagangModal">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="fw-bold">Tanggal Mulai</td>
                            <td>{{ optional($user->pelajar)->rencana_mulai ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Selesai</td>
                            <td>{{ optional($user->pelajar)->rencana_selesai ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Pembimbing / Mentor</td>
                            <td>{{ optional($user->pelajar)->mentor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status Magang</td>
                            <td>
                                @if (optional($user->pelajar)->status === 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif(optional($user->pelajar)->status === 'tidak aktif')
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @else
                                    <span class="badge bg-warning">Belum Ditentukan</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- ==================== MODAL EDIT DATA PRIBADI ==================== --}}
    <div class="modal fade" id="editPribadiModal" tabindex="-1" aria-labelledby="editPribadiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="editPribadiLabel">Edit Data Pribadi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control"
                                value="{{ optional($user->pelajar)->nama }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM / NISN</label>
                            <input type="text" name="nim_nisn" class="form-control"
                                value="{{ optional($user->pelajar)->nim_nisn }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fakultas</label>
                            <input type="text" name="fakultas" class="form-control"
                                value="{{ optional($user->pelajar)->fakultas }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control"
                                value="{{ optional($user->pelajar)->jurusan }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asal Institusi</label>
                            <input type="text" name="asal_institusi" class="form-control"
                                value="{{ optional($user->pelajar)->asal_institusi }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control"
                                value="{{ optional($user->pelajar)->telepon }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL EDIT INFORMASI MAGANG ==================== --}}
    <div class="modal fade" id="editMagangModal" tabindex="-1" aria-labelledby="editMagangLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('magang.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="editMagangLabel">Edit Informasi Magang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="rencana_mulai" class="form-control"
                                value="{{ optional($user->pelajar)->rencana_mulai }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="rencana_selesai" class="form-control"
                                value="{{ optional($user->pelajar)->rencana_selesai }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pembimbing / Mentor</label>
                            <input type="text" name="mentor" class="form-control"
                                value="{{ optional($user->pelajar)->mentor }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Magang</label>
                            <select name="status" class="form-select">
                                <option value="aktif"
                                    {{ optional($user->pelajar)->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif"
                                    {{ optional($user->pelajar)->status === 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL UBAH FOTO ==================== --}}
    <div class="modal fade" id="ubahFotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('profile.updateFoto') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Ubah Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Efek Fade Out --}}
    <style>
        .fade-out {
            opacity: 0;
            transition: opacity 0.8s ease;
        }
    </style>

    <script>
        function fadeOutAndClose(alertNode) {
            alertNode.classList.add('fade-out');
            setTimeout(() => {
                let bsAlert = new bootstrap.Alert(alertNode);
                bsAlert.close();
            }, 800);
        }

        setTimeout(() => {
            document.querySelectorAll('.alert-success').forEach(alertNode => fadeOutAndClose(alertNode));
        }, 3000);

        setTimeout(() => {
            document.querySelectorAll('.alert-danger').forEach(alertNode => fadeOutAndClose(alertNode));
        }, 6000);

        document.querySelectorAll('.alert .btn-close').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let alertNode = this.closest('.alert');
                fadeOutAndClose(alertNode);
            });
        });
    </script>
@endsection
