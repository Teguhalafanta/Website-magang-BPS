<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Magang</title>
    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Magang BPS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    {{-- Menu Mahasiswa --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mahasiswa.index') }}">Mahasiswa</a>
                    </li>

                    {{-- Menu Absensi --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('absensi.index') }}">Absensi</a>
                    </li>

                    {{-- Menu Kegiatan --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kegiatan.index') }}">Kegiatan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten Dinamis --}}
    <main class="container my-4">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
