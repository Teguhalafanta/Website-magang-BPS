@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Absensi</h2>

        {{-- Tabel Absensi --}}
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Tabel Absensi</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelajar</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $index => $absen)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $absen->pelajar->nama }}</td>
                                <td>{{ $absen->tanggal }}</td>
                                <td>{{ $absen->status }}</td>
                                <td>{{ $absen->keterangan ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('absensi.edit', $absen->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('absensi.destroy', $absen->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end">
                    {{ $absensis->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
