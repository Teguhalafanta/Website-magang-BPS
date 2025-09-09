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
                        <td>
                            {{-- pakai accessor kalau ada --}}
                            {{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td>{{ $p->rencana_mulai }}</td>
                        <td>{{ $p->rencana_selesai }}</td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->telepon }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Belum ada pengajuan magang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
