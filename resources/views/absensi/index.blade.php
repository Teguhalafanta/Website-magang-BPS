@extends('kerangka.master')

@section('content')
    <div class="container">
        <h3>Daftar Absensi</h3>

        <!-- Tombol tambah absensi -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahAbsensiModal">
            Tambah Absensi
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelajar</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Shift</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensis as $index => $absen)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $absen->pelajar->nama ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $absen->status }}</td>
                        <td>{{ $absen->keterangan ?? '-' }}</td>
                        <td>{{ ucfirst($absen->shift) }}</td>
                        <td>
                            <!-- Tambah tombol edit/hapus sesuai kebutuhan -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Absensi -->
    <div class="modal fade" id="tambahAbsensiModal" tabindex="-1" aria-labelledby="tambahAbsensiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('absensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pelajar_id" value="{{ auth()->user()->pelajar->id ?? '' }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahAbsensiModalLabel">Tambah Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpha">Alpha</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="shift" class="form-label">Shift</label>
                            <select name="shift" class="form-control" required>
                                <option value="">Pilih Shift</option>
                                <option value="Pagi">Pagi</option>
                                <option value="Siang">Siang</option>
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
@endsection
