@extends('kerangka.master')

@section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-3">
            <i class="bi bi-bell-fill text-danger"></i> Daftar Notifikasi
        </h2>

        {{-- Alert Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" id="flash-alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tombol Tandai Semua Dibaca --}}
        <form action="{{ route('notifications.readAll') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-success btn-sm shadow-sm">
                <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
            </button>
        </form>

        {{-- Tabel Notifikasi --}}
        <div class="shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th class="py-2 px-3 text-center" style="width: 5%;">No</th>
                            <th class="py-2 px-3 text-center" style="width: 50%;">Pesan</th>
                            <th class="py-2 px-3 text-center" style="width: 15%;">Status</th>
                            <th class="py-2 px-3 text-center" style="width: 20%;">Tanggal</th>
                            <th class="py-2 px-3 text-center" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allNotif as $index => $notif)
                            <tr class="{{ is_null($notif->read_at) ? 'fw-bold table-light' : '' }}">
                                <td class="px-3 text-center">{{ $index + 1 }}</td>
                                <td>{{ $notif->data['pesan'] ?? 'Pesan kosong' }}</td>
                                <td class="px-3 text-center">
                                    @if (is_null($notif->read_at))
                                        <span class="badge bg-danger bg-gradient"></i>Baru</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-50">
                                            <i class="bi bi-check2 me-1"></i>Dibaca
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 text-center">{{ $notif->created_at->format('d M Y H:i') }}</td>
                                <td class="px-3 text-center">
                                    @php
                                        $link = $notif->data['url'] ?? null;
                                    @endphp

                                    @if ($link)
                                        <a href="{{ route('notifications.read', $notif->id) }}"
                                            class="btn btn-sm btn-primary shadow-sm">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="bi bi-x-circle"></i> Tidak ada
                                        </button>
                                    @endif
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
                    setTimeout(() => alert.remove(), 150);
                }, 3000);
            }
        });
    </script>
@endsection
