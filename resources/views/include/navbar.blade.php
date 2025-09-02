<nav class="navbar navbar-expand-lg shadow px-4">
    <a class="navbar-brand fw-bold" href="#">Mazer</a>

    <div class="ms-auto d-flex align-items-center">
        {{-- Notifikasi --}}
        <a href="#" class="me-3 position-relative">
            <i class="bi bi-bell fs-4"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                3
            </span>
        </a>

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
