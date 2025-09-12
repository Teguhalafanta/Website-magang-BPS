@extends('kerangka.master')

@section('content')
    <div class="container">
        <h3>Kegiatan Harian</h3>
        <p>Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>

        <!-- Tombol tambah kegiatan -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal">
            Tambah Kegiatan
        </button>

        <!-- Tabel Kegiatan -->
        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Volume</th>
                    <th>Satuan</th>
                    <th>Durasi (menit)</th>
                    <th>Pemberi Tugas</th>
                    <th>Tim Kerja</th>
                    <th>Status</th>
                    <th>Bukti Dukung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatans as $index => $kegiatan)
                    <tr>
                        <td>{{ $loop->iteration }}</td> 
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                        <td>{{ $kegiatan->tanggal }}</td>
                        <td>{{ $kegiatan->deskripsi }}</td>
                        <td>{{ $kegiatan->volume }}</td>
                        <td>{{ $kegiatan->satuan }}</td>
                        <td>{{ $kegiatan->durasi }}</td>
                        <td>{{ $kegiatan->pemberi_tugas }}</td>
                        <td>{{ $kegiatan->tim_kerja }}</td>
                        <td>{{ $kegiatan->status }}</td>
                        <td>
                            @if ($kegiatan->bukti_dukung)
                                <a href="{{ asset('storage/' . $kegiatan->bukti_dukung) }}" target="_blank">Lihat Bukti</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pelajar.kegiatan.edit', $kegiatan->id) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('pelajar.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin hapus kegiatan ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Belum ada kegiatan yang tercatat</td>
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
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
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
                            <label for="volume" class="form-label">Volume</label>
                            <input type="number" class="form-control" id="volume" name="volume" min="0"
                                value="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" name="satuan">
                        </div>
                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi (menit)</label>
                            <input type="number" class="form-control" id="durasi" name="durasi" min="0">
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
                                <option value="Belum">Belum</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        {{-- Upload Bukti Dukung --}}
                        <div class="mb-3">
                            <label class="form-label">Bukti Dukung</label>
                            <input type="file" name="bukti_dukung" class="form-control"
                                accept=".pdf,.doc,.docx,.img,.jpg,.png" required>
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
