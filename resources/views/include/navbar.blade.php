<nav class="navbar navbar-expand-lg shadow px-4">
    <a class="navbar-brand fw-bold" href="#">Mazer</a>

    <div class="ms-auto d-flex align-items-center">
        {{-- Notifikasi Dropdown --}}
        <div class="nav-item dropdown me-3">
            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell fs-4"></i>
                @if (Auth::user()->unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                <li>
                    <h6 class="dropdown-header">Notifikasi</h6>
                </li>
                @forelse($notifikasi as $notif)
                    <li>
                        <a href="{{ $notif->data['url'] ?? '#' }}" class="dropdown-item">
                            {{ $notif->data['pesan'] }}
                            <br>
                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        </a>
                    </li>
                @empty
                    <li><span class="dropdown-item">Tidak ada notifikasi</span></li>
                @endforelse
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a href="{{ route('notifications.index') }}" class="dropdown-item text-center">Lihat semua</a></li>
            </ul>
        </div>

        {{-- Profile dropdown --}}
        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center text-decoration-none"
                data-bs-toggle="dropdown">
                <img src="/img/user.png" alt="user" width="32" height="32" class="rounded-circle me-2">
                <span>Admin</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Profil</a></li>
                <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
