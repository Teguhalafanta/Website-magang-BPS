@extends('kerangka.master')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 text-center">Presensi Pelajar</h3>

        {{-- ALERT JIKA ADA PESAN --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- FORM PRESENSI MASUK --}}
        @php
            $pelajarPresensi = auth()->user()->pelajar->presensis ?? collect([]);
            $hariIni = now()->toDateString();
            $presensiHariIni = $pelajarPresensi->where('tanggal', $hariIni)->first();
        @endphp

        @if (!$presensiHariIni)
            {{-- Jika belum presensi hari ini --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="mb-3">Presensi Hari Ini ({{ now()->format('d M Y') }})</h5>
                    <form action="{{ route('pelajar.presensi.store') }}" method="POST">
                        @csrf
                        @php
                            $now = now();
                            $jamMasuk = \Carbon\Carbon::createFromTime(7, 30);
                            $status = $now->gt($jamMasuk) ? 'Terlambat' : 'Tepat Waktu';
                        @endphp

                        <input type="hidden" name="tanggal" value="{{ $now->toDateString() }}">
                        <input type="hidden" name="waktu_datang" value="{{ $now->format('H:i:s') }}">
                        <input type="hidden" name="status" value="{{ $status }}">

                        <p>Waktu Sekarang: <strong>{{ $now->format('H:i:s') }}</strong></p>
                        <p>Status:
                            <span class="{{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">
                                {{ $status }}
                            </span>
                        </p>

                        <button type="submit" class="btn btn-success px-4">Presensi Masuk</button>
                    </form>
                </div>
            </div>
        @else
            {{-- Jika sudah presensi hari ini --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h5>Presensi Hari Ini ({{ $hariIni }})</h5>
                    <p>Sudah melakukan presensi masuk pukul
                        <strong>{{ $presensiHariIni->waktu_datang }}</strong>
                    </p>

                    @if (!$presensiHariIni->waktu_pulang)
                        <form action="{{ route('pelajar.presensi.update', $presensiHariIni->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning px-4">Presensi Pulang</button>
                        </form>
                    @else
                        <span class="text-success fw-bold">Presensi Pulang Selesai</span>
                    @endif
                </div>
            </div>
        @endif

        {{-- TABEL RIWAYAT PRESENSI --}}
        <h4 class="mb-3">Riwayat Presensi</h4>
        <table class="table table-bordered table-striped text-center">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Waktu Datang</th>
                    <th>Waktu Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelajarPresensi->sortByDesc('tanggal') as $p)
                    <tr>
                        <td>{{ $p->tanggal }}</td>
                        <td>{{ $p->waktu_datang }}</td>
                        <td>{{ $p->waktu_pulang ?? '-' }}</td>
                        <td>
                            @if ($p->status == 'Terlambat')
                                <span class="badge bg-danger">{{ $p->status }}</span>
                            @else
                                <span class="badge bg-success">{{ $p->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data presensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
