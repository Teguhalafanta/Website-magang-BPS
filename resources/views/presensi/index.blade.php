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

    {{-- LOGIKA PRESENSI --}}
    @php
        $pelajarPresensi = auth()->user()->pelajar->presensis ?? collect([]);
        $hariIni = now()->toDateString();
        $presensiHariIni = $pelajarPresensi->where('tanggal', $hariIni)->first();
    @endphp

    {{-- FORM PRESENSI --}}
    @if (!$presensiHariIni)
        {{-- Jika belum presensi hari ini --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <h5 class="mb-3">Presensi Hari Ini ({{ now()->format('d M Y') }})</h5>

                <form id="formMasuk" action="{{ route('pelajar.presensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jam_client" id="jamClientMasuk">

                    <p>Waktu Sekarang: <strong id="jamSekarang"></strong></p>
                    <p>Status otomatis akan ditentukan saat Anda klik Presensi Masuk.</p>

                    <button type="button" class="btn btn-success px-4" onclick="submitPresensiMasuk()">Presensi Masuk</button>
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
                    <form id="formPulang" action="{{ route('pelajar.presensi.update', $presensiHariIni->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="jam_client" id="jamClientPulang">
                        <button type="button" class="btn btn-warning px-4" onclick="submitPresensiPulang()">Presensi Pulang</button>
                    </form>
                @else
                    <span class="text-success fw-bold">Presensi Pulang Selesai ({{ $presensiHariIni->waktu_pulang }})</span>
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

{{-- SCRIPT JAM SINKRON DENGAN CLIENT --}}
<script>
function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString('en-GB', { hour12: false });
}

// Update jam di tampilan setiap detik
setInterval(() => {
    document.getElementById('jamSekarang').textContent = getCurrentTime();
}, 1000);

function submitPresensiMasuk() {
    document.getElementById('jamClientMasuk').value = getCurrentTime();
    document.getElementById('formMasuk').submit();
}

function submitPresensiPulang() {
    document.getElementById('jamClientPulang').value = getCurrentTime();
    document.getElementById('formPulang').submit();
}
</script>
@endsection
