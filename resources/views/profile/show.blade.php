@extends('kerangka.master')

@section('title', 'Profile')

@section('content')
    <div class="container mt-4">

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
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
                    @if ($user->pelajar->foto ?? false)
                        <img src="{{ asset('storage/' . $user->pelajar->foto) }}" class="rounded-circle border"
                            width="150" height="150" alt="{{ $user->pelajar->nama }}">
                    @else
                        <i class="fas fa-user-circle text-secondary" style="font-size: 150px;"></i>
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
                                <td class="fw-bold">Email</td>
                                <td>{{ optional($user->pelajar)->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">No HP</td>
                                <td>{{ optional($user->pelajar)->telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Role</td>
                                <td>{{ ucfirst($user->role) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <!-- Informasi Magang -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold">Informasi Magang</h5>
                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editMagangModal">
                    <i class="fas fa-pen"></i>
                </button>
            </div>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="fw-bold">Tanggal Mulai & Selesai</td>
                        <td>{{ optional($user->pelajar)->rencana_mulai ?? '-' }} -
                            {{ optional($user->pelajar)->rencana_selesai ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Divisi</td>
                        <td>{{ optional($user->pelajar)->division ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Pembimbing</td>
                        <td>{{ optional($user->pelajar)->mentor ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Status Magang</td>
                        <td>
                            @if (optional($user->pelajar)->status === 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Data Pribadi -->
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
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
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

    <!-- Modal Edit Informasi Magang -->
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
                            <input type="date" class="form-control" name="start_date"
                                value="{{ $user->start_date }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $user->end_date }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Divisi</label>
                            <input type="text" class="form-control" name="division" value="{{ $user->division }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pembimbing</label>
                            <input type="text" class="form-control" name="mentor" value="{{ $user->mentor }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Magang</label>
                            <select name="status" class="form-select">
                                <option value="aktif" {{ $user->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak" {{ $user->status === 'tidak' ? 'selected' : '' }}>Tidak Aktif
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

    <!-- Modal Ubah Foto -->
    <div class="modal fade" id="ubahFotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Ubah Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="photo" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Efek fade-out */
        .fade-out {
            opacity: 0;
            transition: opacity 0.8s ease;
        }
    </style>

    <script>
        // Fungsi fade out + close
        function fadeOutAndClose(alertNode) {
            alertNode.classList.add('fade-out');
            setTimeout(() => {
                let bsAlert = new bootstrap.Alert(alertNode);
                bsAlert.close();
            }, 800); // sesuai durasi transition
        }

        // Auto-close alert sukses setelah 3 detik
        setTimeout(() => {
            document.querySelectorAll('.alert-success').forEach(alertNode => {
                fadeOutAndClose(alertNode);
            });
        }, 3000);

        // Auto-close alert error setelah 6 detik
        setTimeout(() => {
            document.querySelectorAll('.alert-danger').forEach(alertNode => {
                fadeOutAndClose(alertNode);
            });
        }, 6000);

        // Override tombol close manual biar tetap fade dulu
        document.querySelectorAll('.alert .btn-close').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // cegah langsung hilang
                let alertNode = this.closest('.alert');
                fadeOutAndClose(alertNode);
            });
        });
    </script>
@endsection
