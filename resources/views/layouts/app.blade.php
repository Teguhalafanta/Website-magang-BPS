<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Aplikasi Magang')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        .sidebar {
            min-width: 220px;
            background-color: #343a40;
            color: white;
            padding: 1rem;
            height: 100vh;
            position: fixed;
        }

        .sidebar .brand {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 0.75rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #0d6efd;
        }

        .content {
            margin-left: 220px;
            padding: 2rem;
            flex: 1;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>

<body>

    <nav class="sidebar">
        <div class="brand">Magang BPS</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('mahasiswa.index') }}"
            class="{{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}">Mahasiswa</a>
        <a href="{{ route('absensi.index') }}" class="{{ request()->routeIs('absensi.*') ? 'active' : '' }}">Absensi</a>
        <a href="{{ route('kegiatan.index') }}"
            class="{{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">Kegiatan</a>
        <!-- Tambah menu lain jika perlu -->
    </nav>

    <main class="content">
        <!-- Topbar -->
        <div class="d-flex justify-content-end mb-3">
            <x-notification-dropdown :notifications="$notifications" :unreadCount="$unreadCount" />
        </div>


        <!-- Konten utama -->
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
