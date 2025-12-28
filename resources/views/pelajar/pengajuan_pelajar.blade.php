@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        {{-- Header Section --}}
        <div class="mb-3">
            <h2 class="fw-bold text-primary mb-2">Form Pengajuan Magang</h2>
            <p class="text-muted">Lengkapi data diri Anda dengan benar. Field bertanda <span
                    class="text-danger fw-bold">*</span> wajib diisi.</p>
        </div>

        {{-- Alert Info --}}
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>ℹ️ Informasi Penting:</strong>
            <ul class="mb-0 mt-2">
                <li>Pastikan semua data yang diisi sesuai dengan dokumen resmi</li>
                <li>File yang diupload maksimal 5 MB dalam format PDF/DOC/DOCX</li>
                <li>Proses verifikasi pengajuan membutuhkan waktu 3-5 hari kerja</li>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <form action="{{ route('pelajar.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Kolom kiri --}}
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">👤 Data Pribadi</h5>
                        </div>
                        <div class="card-body p-4">
                            {{-- Nama --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                                    placeholder="Contoh: Teguh Alafanta" required>
                                <small class="text-muted">Sesuai KTP/Kartu Peserta</small>
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Kelamin <span
                                        class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-control" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>👨
                                        Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>👩
                                        Perempuan</option>
                                </select>
                            </div>

                            {{-- Tempat Lahir --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tempat Lahir <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    value="{{ old('tempat_lahir') }}" placeholder="Contoh: Banda Aceh" required>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Lahir <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ old('tanggal_lahir') }}" required>
                                <small class="text-muted">Sesuai KTP/Kartu Peserta</small>
                            </div>

                            {{-- Alamat --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat Lengkap <span
                                        class="text-danger">*</span></label>
                                <textarea name="alamat" class="form-control" rows="3"
                                    placeholder="Contoh: Jl. Teuku Umar No. 123, Kecamatan Banda Raya, Banda Aceh" required>{{ old('alamat') }}</textarea>
                                <small class="text-muted">Alamat domisili saat ini</small>
                            </div>

                            {{-- Telepon --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">No. Telepon/WhatsApp</label>
                                <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}"
                                    placeholder="Contoh: 081234567890">
                                <small class="text-muted">Nomor yang dapat dihubungi</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">🎓 Data Akademik</h5>
                        </div>
                        <div class="card-body p-4">
                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control bg-light"
                                    value="{{ Auth::user()->email }}" readonly>
                                <small class="text-muted">Email tidak dapat diubah</small>
                            </div>

                            {{-- NIM / NISN --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">NIM / NISN <span class="text-danger">*</span></label>
                                <input type="text" name="nim_nisn" class="form-control" value="{{ old('nim_nisn') }}"
                                    placeholder="Contoh: 1234567890" required>
                                <small class="text-muted">Nomor Induk Mahasiswa/Siswa</small>
                            </div>

                            {{-- Asal Institusi --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Asal Institusi <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="asal_institusi" class="form-control"
                                    value="{{ old('asal_institusi') }}" placeholder="Contoh: Universitas Syiah Kuala"
                                    required>
                                <small class="text-muted">Nama sekolah/universitas lengkap</small>
                            </div>

                            {{-- Fakultas --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Fakultas</label>
                                <input type="text" name="fakultas" class="form-control"
                                    value="{{ old('fakultas') }}" placeholder="Contoh: Fakultas Teknik">
                                <small class="text-muted">Kosongkan jika tingkat SMA/SMK</small>
                            </div>

                            {{-- Jurusan --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jurusan/Program Studi <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan') }}"
                                    placeholder="Contoh: Teknik Informatika" required>
                            </div>

                            {{-- Rencana Mulai --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Mulai Magang <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="rencana_mulai" class="form-control"
                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    value="{{ old('rencana_mulai') }}" required>
                                <small class="text-muted">Tanggal mulai tidak boleh kurang dari hari ini</small>
                            </div>

                            {{-- Rencana Selesai --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Selesai Magang <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="rencana_selesai" class="form-control"
                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    value="{{ old('rencana_selesai') }}" required>
                                <small class="text-muted">Minimal 1 bulan setelah tanggal mulai</small>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">📄 Dokumen Pendukung</h5>
                        </div>
                        <div class="card-body p-4">
                            {{-- Upload Proposal --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload Proposal <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="proposal" class="form-control" accept=".pdf,.doc,.docx"
                                    required>
                                <small class="text-muted">📎 Format: PDF/DOC/DOCX (Max. 5MB)</small>
                                <div class="alert alert-light mt-2 mb-0 py-2 px-3">
                                    <small><strong>Proposal berisi:</strong> Tujuan magang, jadwal kegiatan, output yang
                                        diharapkan</small>
                                </div>
                            </div>

                            {{-- Upload Surat Pengajuan --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload Surat Pengajuan / CV</label>
                                <input type="file" name="surat_pengajuan" class="form-control"
                                    accept=".pdf,.doc,.docx">
                                <small class="text-muted">📎 Format: PDF/DOC/DOCX (Max. 5MB) - Opsional</small>
                                <div class="alert alert-light mt-2 mb-0 py-2 px-3">
                                    <small>Surat pengantar dari institusi atau CV pribadi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Persetujuan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="agreement" required>
                        <label class="form-check-label" for="agreement">
                            Saya menyatakan bahwa data yang saya isi adalah <strong>benar dan dapat
                                dipertanggungjawabkan</strong>.
                            Saya bersedia mengikuti seluruh peraturan yang berlaku selama masa magang. <span
                                class="text-danger">*</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                    <strong>Ajukan Magang Sekarang</strong>
                </button>
                <p class="text-muted mt-3 mb-0">
                    <small>Dengan mengajukan, Anda setuju dengan syarat dan ketentuan yang berlaku</small>
                </p>
            </div>

        </form>
    </div>
@endsection
