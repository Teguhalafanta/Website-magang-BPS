@extends('kerangka.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Daftar Pengajuan Magang</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM/NISN</th>
                    <th>Asal Institusi</th>
                    <th>Jurusan</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Rencana Mulai</th>
                    <th>Rencana Selesai</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $key => $p)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->nim_nisn }}</td>
                        <td>{{ $p->asal_institusi }}</td>
                        <td>{{ $p->jurusan }}</td>
                        <td>{{ $p->tempat_lahir }}</td>
                        <td>{{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td>{{ $p->rencana_mulai }}</td>
                        <td>{{ $p->rencana_selesai }}</td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->telepon }}</td>

                        {{-- Status tampil saja --}}
                        <td>
                            @if ($p->status == 'pending')
                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                            @elseif($p->status == 'diterima')
                                <span class="badge bg-success">Diterima</span>
                            @elseif($p->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                                <br>
                                <small><strong>Alasan:</strong> {{ $p->alasan }}</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Belum ada pengajuan magang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
