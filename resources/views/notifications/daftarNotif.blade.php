{{-- resources/views/notifications/index.blade.php --}}
@extends('kerangka.master')

@section('title', 'Daftar Notifikasi')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>📩 Daftar Notifikasi</h4>
            <a href="{{ route('notifications.readAll') }}" class="btn btn-sm btn-primary">
                Tandai Semua Dibaca
            </a>
        </div>

        <div class="list-group shadow-sm rounded-3">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                        {{ $notification->read_at ? '' : 'fw-bold bg-light' }}">

                    <div>
                        <div>{{ $notification->data['message'] ?? 'Notifikasi tanpa pesan' }}</div>
                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>

                    @if (!$notification->read_at)
                        <span class="badge bg-danger rounded-pill">Baru</span>
                    @endif
                </a>
            @empty
                <div class="text-center py-5 text-muted">
                    Tidak ada notifikasi
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $notifications->links() }} 
        </div>
    </div>
@endsection
