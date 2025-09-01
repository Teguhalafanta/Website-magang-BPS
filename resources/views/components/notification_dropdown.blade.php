<div class="dropdown">
    <!-- Tombol lonceng -->
    <a class="btn btn-light position-relative" href="#" id="notifDropdown" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        @if ($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    <!-- Dropdown isi notifikasi -->
    <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0 rounded-3"
        style="width: 350px; max-height: 400px; overflow-y: auto;">
        <h6 class="fw-bold mb-3">Notifikasi</h6>

        @forelse($notifications as $notif)
            <a href="{{ route('notifications.read', $notif->id) }}"
                class="d-flex align-items-start mb-3 text-decoration-none text-dark">
                <!-- Ikon notifikasi -->
                <div class="me-3">
                    @if ($notif->type == 'izin')
                        <i class="bi bi-envelope-fill text-primary fs-5"></i>
                    @elseif($notif->type == 'deadline')
                        <i class="bi bi-clock-fill text-danger fs-5"></i>
                    @elseif($notif->type == 'feedback')
                        <i class="bi bi-chat-dots-fill text-success fs-5"></i>
                    @else
                        <i class="bi bi-calendar-event-fill text-warning fs-5"></i>
                    @endif
                </div>

                <!-- Isi notifikasi -->
                <div class="flex-fill">
                    <div class="fw-semibold">{{ $notif->title }}</div>
                    <small class="text-muted">{{ $notif->message }}</small><br>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
            </a>
        @empty
            <div class="text-center text-muted">Tidak ada notifikasi</div>
        @endforelse

        <hr>
        <div class="text-center">
            <a href="{{ route('notifications.index') }}" class="fw-bold text-primary">Lihat semua notifikasi</a>
        </div>
    </div>
</div>
