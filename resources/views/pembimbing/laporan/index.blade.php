@extends('kerangka.master')

@section('content')
    <div class="container my-4">

        <h3 class="fw-bold mb-3">Verifikasi Laporan Akhir Peserta Bimbingan</h3>
        <p class="text-muted">Silakan cek laporan akhir peserta sebelum disetujui atau ditolak.</p>

        <div class="card shadow-sm rounded-3">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <input type="text" class="form-control w-50" placeholder="Cari Pelajar...">
                    <select class="form-select w-auto">
                        <option>10 data</option>
                        <option>25 data</option>
                        <option>50 data</option>
                    </select>
                </div>

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelajar</th>
                            <th>Status</th>
                            <th>Lihat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporans as $index => $laporan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $laporan->user->pelajar->nama }}</td>

                                <td>
                                    @if ($laporan->status == 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif ($laporan->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-success">Disetujui</span>
                                    @endif
                                </td>

                                <td>
                                    <button class="btn btn-outline-primary btn-sm"
                                        onclick="tampilLaporan('{{ asset('uploads/laporan/' . $laporan->file) }}')">
                                        Lihat Laporan
                                    </button>
                                </td>

                                <td>
                                    @if ($laporan->status == 'menunggu')
                                        <form action="{{ route('pembimbing.laporan.setujui', $laporan->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Setujui</button>
                                        </form>

                                        <form action="{{ route('pembimbing.laporan.tolak', $laporan->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada laporan dikirim.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PREVIEW LAPORAN --}}
        <div id="preview-box" class="card shadow-sm rounded mt-4" style="display:none;">
            <div class="card-header fw-bold">Preview Laporan</div>
            <div class="card-body">
                <iframe id="frame-laporan" src="" width="100%" height="600px"
                    style="border:1px solid #ccc;"></iframe>
            </div>
        </div>

    </div>

    <script>
        function tampilLaporan(url) {
            document.getElementById('preview-box').style.display = 'block';
            document.getElementById('frame-laporan').src = url;
            window.scrollTo({
                top: document.getElementById('preview-box').offsetTop - 80,
                behavior: 'smooth'
            });
        }
    </script>
@endsection
