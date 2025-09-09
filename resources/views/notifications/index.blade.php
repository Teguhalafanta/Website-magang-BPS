@extends('kerangka.master')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">📌 Daftar Notifikasi</h3>

        {{-- Alert Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="flash-alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tombol Tandai Semua Dibaca --}}
        <form action="{{ route('notifications.readAll') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">
                ✅ Tandai Semua Dibaca
            </button>
        </form>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Pesan</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allNotif as $index => $notif)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $notif->data['pesan'] ?? 'Pesan kosong' }}</td>
                                <td>
                                    @if (is_null($notif->read_at))
                                        <span class="badge bg-danger">Baru</span>
                                    @else
                                        <span class="badge bg-success">Dibaca</span>
                                    @endif
                                </td>
                                <td>{{ $notif->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('notifications.read', $notif->id) }}" class="btn btn-sm btn-primary">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada notifikasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Auto-close Alert --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alert = document.getElementById("flash-alert");
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove("show");
                    alert.classList.add("fade");
                }, 3000); // 3 detik
            }
        });
    </script>
@endsection
