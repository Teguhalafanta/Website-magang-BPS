@extends('kerangka.master')

@section('content')
    <div class="container">
        <h3>Kegiatan Harian</h3>
        <p>Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>

        <!-- Tombol tambah kegiatan -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal">
            Tambah Kegiatan
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Kegiatan</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Deskripsi</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Bukti Dukung</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatans as $index => $kegiatan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $kegiatan->deskripsi }}</td>
                        <td>
                            @if ($kegiatan->status_penyelesaian === 'Selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif ($kegiatan->status_penyelesaian === 'Dalam Proses')
                                <span class="badge bg-warning text-dark">Proses</span>
                            @elseif ($kegiatan->status_penyelesaian === 'Belum Dimulai')
                                <span class="badge bg-secondary">Belum Dimulai</span>
                            @else
                                <span class="badge bg-dark">Tidak Diketahui</span>
                            @endif
                        </td>
                        <td>
                            @if ($kegiatan->bukti_dukung)
                                <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}" target="_blank"
                                    class="btn btn-sm btn-primary">Lihat</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pelajar.kegiatan.edit', $kegiatan->id) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('pelajar.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada kegiatan hari ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Kegiatan -->
    <div class="modal fade" id="tambahKegiatanModal" tabindex="-1" aria-labelledby="tambahKegiatanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('pelajar.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahKegiatanModalLabel">Tambah Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Tanggal otomatis hari ini -->
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="pemberi_tugas" class="form-label">Pemberi Tugas</label>
                            <input type="text" class="form-control" id="pemberi_tugas" name="pemberi_tugas">
                        </div>

                        <div class="mb-3">
                            <label for="tim_kerja" class="form-label">Tim Kerja</label>
                            <input type="text" class="form-control" id="tim_kerja" name="tim_kerja">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Belum Dimulai">Belum Dimulai</option>
                                <option value="Dalam Proses">Dalam Proses</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bukti Dukung</label>
                            <input type="file" name="bukti_dukung" class="form-control" accept=".pdf,.doc,.docx,image/*">
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
@endsection
