@extends('kerangka.master')

@section('content')
    <div class="container py-2">
        <h3 class="fw-bold text-primary mb-3">Kegiatan Bulanan</h3>

        <form method="GET" action="{{ route('pelajar.kegiatan.bulanan') }}" class="row g-2 align-items-end mb-4">
            <div class="col-md-4 col-sm-6">
                <label for="bulan" class="form-label">Pilih Bulan:</label>
                <input type="month" id="bulan" name="bulan" class="form-control"
                    value="{{ request('bulan', \Carbon\Carbon::now()->format('Y-m')) }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Uraian Kegiatan</th>
                    <th class="text-center">Deskripsi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatans as $i => $kegiatan)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ $kegiatan->tanggal ? \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $kegiatan->nama_kegiatan ?? '-' }}</td>
                        <td>{{ $kegiatan->deskripsi }}</td>
                        <td class="text-center">

                            <!-- Tombol Edit -->
                            <a href="{{ route('pelajar.kegiatan.edit', $kegiatan->id) }}" class="btn btn-sm btn-warning"
                                title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('pelajar.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada kegiatan di bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
