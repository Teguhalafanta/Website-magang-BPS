<nav class="navbar navbar-expand-lg shadow px-4">
    {{-- Tombol Hamburger --}}
    <button class="btn border-0 me-3 burger-btn" type="button">
        <i class="bi bi-justify fs-3 text-primary"></i>
    </button>

    {{-- Logo / Brand --}}
    <a class="navbar-brand fw-bold" href="#">Badan Pusat Statistik</a>

    <div class="ms-auto d-flex align-items-center">
        {{-- Notifikasi Dropdown --}}
        <div class="nav-item dropdown me-3">
            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell fs-4"></i>
                @if (Auth::user()->unreadNotifications->count() > 0)
                    <span id="notifBadge"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown"
                style="min-width: 300px;">
                <li>
                    <h6 class="dropdown-header">Notifikasi</h6>
                </li>

                @forelse($notifikasi as $notif)
                    <li>
                        <a href="#"
                            class="dropdown-item notif-item {{ is_null($notif->read_at) ? 'fw-bold' : '' }}"
                            data-id="{{ $notif->id }}" data-url="{{ $notif->data['url'] ?? '#' }}">
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

        {{-- Toggle Theme dengan Ikon di Dalam Switch --}}
        <div class="form-check form-switch m-0">
            <input class="form-check-input theme-switch" type="checkbox" id="toggle-dark">
            <label class="form-check-label" for="toggle-dark"></label>

        </div>

        {{-- Profile Dropdown --}}
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-dark" data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="none" viewBox="0 0 16 16"
                    stroke="currentColor">
                    <path d="M8 8a3 3 0 100-6 3 3 0 000 6z" />
                    <path fill-rule="evenodd" d="M14 14s-1-4-6-4-6 4-6 4 1 0 6 0 6 0 6 0z" />
                </svg>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item fw-bold text-dark" href="{{ route('profile.show') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor"
                            class="bi bi-person me-2" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 100-6 3 3 0 000 6z" />
                            <path fill-rule="evenodd" d="M14 14s-1-4-6-4-6 4-6 4 1 0 6 0 6 0 6 0z" />
                        </svg>
                        Profil
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item fw-bold text-dark">
                            <i class="bi bi-box-arrow-right me-2" style="font-size:1.25em;"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const notifItems = document.querySelectorAll(".notif-item");
            const notifBadge = document.getElementById("notifBadge");

            notifItems.forEach(item => {
                item.addEventListener("click", async (e) => {
                    e.preventDefault();
                    const notifId = item.dataset.id;
                    const url = item.dataset.url;

                    try {
                        const response = await fetch(`/notifications/${notifId}/read`, {
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        });

                        if (response.ok) {
                            // Ubah teks jadi normal (tidak bold)
                            item.classList.remove("fw-bold");

                            // Kurangi badge angka
                            if (notifBadge) {
                                let count = parseInt(notifBadge.textContent);
                                if (count > 1) {
                                    notifBadge.textContent = count - 1;
                                } else {
                                    notifBadge.remove();
                                }
                            }

                            // Buka URL jika ada
                            if (url && url !== "#") {
                                window.location.href = url;
                            }
                        }
                    } catch (err) {
                        console.error("Gagal menandai notifikasi:", err);
                    }
                });
            });
        });
    </script>
</nav>
