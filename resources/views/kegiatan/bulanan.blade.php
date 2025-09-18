@extends('kerangka.master')

@section('content')
    <div class="container">
        <h3>Kegiatan Bulanan</h3>

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
            <thead>
                <tr>
                    <th>No</th>
                    <th>Uraian Kegiatan</th>
                    <th>Satuan</th>
                    <th>Target</th>
                    <th>Realisasi</th>
                    <th>Persentase</th>
                    <th>Tingkat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatans as $i => $kegiatan)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $kegiatan->nama_kegiatan ?? '-' }}</td>
                        <td>{{ $kegiatan->satuan ?? '-' }}</td>
                        <td>{{ $kegiatan->target ?? 0 }}</td>
                        <td>{{ $kegiatan->realisasi ?? 0 }}</td>
                        <td>
                            @php
                                $target = $kegiatan->target ?? 0;
                                $realisasi = $kegiatan->realisasi ?? 0;
                                $persen = $target > 0 ? round(($realisasi / $target) * 100, 2) : 0;
                            @endphp
                            {{ $persen }}%
                        </td>
                        <td>{{ $kegiatan->tingkat ?? '-' }}</td>
                        <td>
                            <a href="{{ route('pelajar.kegiatan.edit', $kegiatan->id) }}"
                                class="btn btn-sm btn-outline-primary" title="Edit">
                                ✏️
                            </a>
                            <form action="{{ route('pelajar.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada kegiatan di bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
