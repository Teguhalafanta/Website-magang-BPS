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
            <h4 class="fw-bold mb-4">Profil {{ ucfirst($user->role) }}</h4>
            <div class="row">
                <div class="col-md-3 text-center">
                    {{-- Tampilkan foto profil --}}
                    @if ($user->foto && Storage::disk('public')->exists($user->foto))
                        <img src="{{ Storage::url($user->foto) }}" alt="Foto Profil" class="rounded-circle border mb-2"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Default Foto"
                            class="rounded-circle border mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                    <br>
                    <!-- Tombol Ubah Foto -->
                    <button class="btn btn-sm btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#ubahFotoModal">
                        <i class="bi bi-camera me-1"></i> Ubah Foto
                    </button>
                </div>

                <!-- Data Pribadi -->
                <div class="col-md-9">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">Data Pribadi</h5>
                        <button class="btn btn-sm btn-dark rounded-circle" data-bs-toggle="modal"
                            data-bs-target="#editPribadiModal" title="Edit Data Pribadi">
                            <i class="bi bi-person-lines-fill fs-6 text-white"></i>
                        </button>
                    </div>

                    {{-- Data Pribadi Berdasarkan Role --}}
                    @if ($user->role === 'pelajar')
                        <table class="table table-borderless">
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
                        </table>
                </div>
                <hr>

                {{-- Informasi Magang --}}
                <h5 class="fw-bold mb-3">Informasi Magang</h5>
                <table class="table table-borderless">
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
                            @php
                                $pelajar = $user->pelajar;
                                $status = $pelajar->statusMagangOtomatis ?? 'belum ditentukan';
                                $badge =
                                    $pelajar->badgeClass ??
                                    'bg-secondary-subtle text-secondary fw-semibold px-3 py-1 rounded-pill';
                            @endphp
                            <span class="{{ $badge }}">{{ ucfirst($status) }}</span>
                        </td>
                    </tr>
                </table>
            @elseif($user->role === 'pembimbing')
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Nama</td>
                        <td>{{ $user->username ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Email</td>
                        <td>{{ $user->email ?? '-' }}</td>
                    </tr>
                </table>
            @else
                {{-- Untuk Admin --}}
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Nama</td>
                        <td>{{ $user->username ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Email</td>
                        <td>{{ $user->email ?? '-' }}</td>
                    </tr>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- ==================== MODAL EDIT DATA PRIBADI ==================== --}}
    <div class="modal fade" id="editPribadiModal" tabindex="-1" aria-labelledby="editPribadiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('profile.update') }}" method="POST" class="w-100">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="editPribadiLabel">Edit Data Pribadi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- ===== Field Nama (semua role punya) ===== --}}
                            <div class="mb-3">
                                <div class="form-group has-icon-left">
                                    <label for="nama">Nama</label>
                                    <div class="position-relative">
                                        <input type="text" id="nama" name="nama" class="form-control"
                                            value="{{ $user->role === 'pelajar' ? optional($user->pelajar)->nama : $user->username }}"
                                            required>
                                        <div class="form-control-icon">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- ===== Field khusus Pelajar ===== --}}
                            @if ($user->role === 'pelajar')
                                <div class="mb-3">
                                    <div class="form-group has-icon-left">
                                        <label for="nim_nisn">NIM / NISN</label>
                                        <div class="position-relative">
                                            <input type="text" id="nim_nisn" name="nim_nisn" class="form-control"
                                                value="{{ optional($user->pelajar)->nim_nisn }}">
                                            <div class="form-control-icon">
                                                <i class="bi bi-card-text"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-group has-icon-left">
                                        <label for="fakultas">Fakultas</label>
                                        <div class="position-relative">
                                            <input type="text" id="fakultas" name="fakultas" class="form-control"
                                                value="{{ optional($user->pelajar)->fakultas }}">
                                            <div class="form-control-icon">
                                                <i class="bi bi-building"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-group has-icon-left">
                                        <label for="jurusan">Jurusan</label>
                                        <div class="position-relative">
                                            <input type="text" id="jurusan" name="jurusan" class="form-control"
                                                value="{{ optional($user->pelajar)->jurusan }}">
                                            <div class="form-control-icon">
                                                <i class="bi bi-book"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-group has-icon-left">
                                        <label for="asal_institusi">Asal Institusi</label>
                                        <div class="position-relative">
                                            <input type="text" id="asal_institusi" name="asal_institusi"
                                                class="form-control"
                                                value="{{ optional($user->pelajar)->asal_institusi }}">
                                            <div class="form-control-icon">
                                                <i class="bi bi-bank"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-group has-icon-left">
                                        <label for="telepon">Telepon</label>
                                        <div class="position-relative">
                                            <input type="text" id="telepon" name="telepon" class="form-control"
                                                value="{{ optional($user->pelajar)->telepon }}">
                                            <div class="form-control-icon">
                                                <i class="bi bi-telephone"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- ===== Email (readonly) ===== --}}
                            <div class="mb-3">
                                <div class="form-group has-icon-left">
                                    <label for="email">Email</label>
                                    <div class="position-relative">
                                        <input type="email" id="email" name="email"
                                            class="form-control bg-light" value="{{ $user->email }}" readonly>
                                        <div class="form-control-icon">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="bi bi-x-circle me-1"></i>Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan</button>
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
                                    {{ optional($user->pelajar)->status === 'aktif' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="tidak aktif"
                                    {{ optional($user->pelajar)->status === 'tidak aktif' ? 'selected' : '' }}>
                                    Tidak Aktif
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
        document.addEventListener("DOMContentLoaded", function() {
            const notifLinks = document.querySelectorAll('.notif-link');
            notifLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;

                    fetch(`/notifications/${id}/read`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Hapus bold & update badge
                                const li = this.closest('.notif-item');
                                li.classList.remove('fw-bold');

                                const badge = document.querySelector('.bi-bell + span');
                                if (badge) {
                                    let count = parseInt(badge.textContent) - 1;
                                    if (count > 0) badge.textContent = count;
                                    else badge.remove();
                                }

                                // Redirect ke URL jika tersedia
                                if (data.url) window.location.href = data.url;
                            }
                        });
                });
            });
        });
    </script>
@endsection
