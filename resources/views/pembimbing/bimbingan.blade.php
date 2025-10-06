@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Daftar Peserta Bimbingan</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Asal Institusi</th>
                    <th>Jurusan</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelajars as $key => $p)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->asal_institusi }}</td>
                        <td>{{ $p->jurusan }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#detailModal{{ $p->id }}">
                                Detail
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Detail Peserta -->
                    <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1"
                        aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="detailModalLabel{{ $p->id }}">
                                        Detail Peserta - {{ $p->nama }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Nama Lengkap:</strong>
                                                    {{ $p->nama }}</li>
                                                <li class="list-group-item"><strong>Jenis Kelamin:</strong>
                                                    {{ $p->jenis_kelamin }}</li>
                                                <li class="list-group-item"><strong>Tempat Lahir:</strong>
                                                    {{ $p->tempat_lahir }}</li>
                                                <li class="list-group-item"><strong>Tanggal Lahir:</strong>
                                                    {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                                </li>
                                                <li class="list-group-item"><strong>Alamat:</strong>
                                                    {{ $p->alamat }}</li>
                                                <li class="list-group-item"><strong>No. Telepon:</strong>
                                                    {{ $p->telepon }}</li>
                                                <li class="list-group-item"><strong>Email:</strong>
                                                    {{ $p->email }}</li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>NIM / NISN:</strong>
                                                    {{ $p->nim_nisn }}</li>
                                                <li class="list-group-item"><strong>Asal Institusi:</strong>
                                                    {{ $p->asal_institusi }}</li>
                                                <li class="list-group-item"><strong>Fakultas:</strong>
                                                    {{ $p->fakultas }}</li>
                                                <li class="list-group-item"><strong>Jurusan:</strong>
                                                    {{ $p->jurusan }}</li>
                                                <li class="list-group-item"><strong>Rencana Mulai:</strong>
                                                    {{ $p->rencana_mulai }}</li>
                                                <li class="list-group-item"><strong>Rencana Selesai:</strong>
                                                    {{ $p->rencana_selesai }}</li>
                                                <li class="list-group-item">
                                                    <strong>Proposal:</strong>
                                                    @if ($p->proposal)
                                                        <a href="{{ asset('storage/' . $p->proposal) }}"
                                                            target="_blank">Lihat Proposal</a>
                                                    @else
                                                        -
                                                    @endif
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Surat Pengajuan:</strong>
                                                    @if ($p->surat_pengajuan)
                                                        <a href="{{ asset('storage/' . $p->surat_pengajuan) }}"
                                                            target="_blank">Lihat Surat</a>
                                                    @else
                                                        -
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada peserta bimbingan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
