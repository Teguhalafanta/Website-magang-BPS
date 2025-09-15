@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Pengajuan Magang</h2>

        <div class="mb-3">
            <a href="{{ route('pelajar.pengajuan.create') }}" class="btn btn-primary">
                + Tambah Pengajuan
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Asal Institusi</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $key => $p)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->asal_institusi }}</td>
                        <td>{{ $p->jurusan }}</td>
                        <td>
                            @if ($p->status == 'diajukan')
                                <span class="badge bg-warning">Diajukan</span>
                            @elseif($p->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($p->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>

                        <!-- aksi -->
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#detailModal{{ $p->id }}">
                                Detail
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Detail -->
                    <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1"
                        aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel{{ $p->id }}">
                                        Detail Pengajuan - {{ $p->nama }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nama:</strong> {{ $p->nama }}</p>
                                    <p><strong>NIM/NISN:</strong> {{ $p->nim_nisn }}</p>
                                    <p><strong>Asal Institusi:</strong> {{ $p->asal_institusi }}</p>
                                    <p><strong>Jurusan:</strong> {{ $p->jurusan }}</p>
                                    <p><strong>Tempat Lahir:</strong> {{ $p->tempat_lahir }}</p>
                                    <p><strong>Tanggal Lahir:</strong>
                                        {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                    </p>
                                    <p><strong>Rencana Mulai:</strong> {{ $p->rencana_mulai }}</p>
                                    <p><strong>Rencana Selesai:</strong> {{ $p->rencana_selesai }}</p>
                                    <p><strong>Email:</strong> {{ $p->email }}</p>
                                    <p><strong>Telepon:</strong> {{ $p->telepon }}</p>
                                    <p>
                                        <strong>Status:</strong>
                                        @if ($p->status == 'diajukan')
                                            <span class="badge bg-primary">Diajukan</span>
                                        @elseif($p->status == 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($p->status == 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                            <br>
                                            <small><strong>Alasan:</strong> {{ $p->alasan }}</small>
                                        @endif
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada pengajuan magang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
