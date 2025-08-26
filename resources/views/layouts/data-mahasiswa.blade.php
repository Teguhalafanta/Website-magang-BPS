<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Data Mahasiswa')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom styles -->
    <style>
        body {
            background-color: #f1f3f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #1e1e2f;
            position: fixed;
            top: 0;
            left: 0;
            padding: 2rem 1rem;
            color: white;
            z-index: 1000;
        }

        .sidebar h4 {
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .sidebar .nav-link {
            color: #ccc;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(to right, #0d6efd, #6610f2);
            color: white;
            padding: 1.5rem 2rem;
            border-bottom: 2px solid #0b5ed7;
        }

        .main {
            padding: 2rem;
        }

        /* Responsive: collapse sidebar on small screens */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
                transition: all 0.3s;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar {
                display: inline-block;
                cursor: pointer;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <h4>Magang BPS</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}" href="{{ route('mahasiswa.index') }}">
                <i class="fas fa-user-graduate"></i> Mahasiswa
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}" href="{{ route('absensi.index') }}">
                <i class="fas fa-calendar-check"></i> Absensi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-tasks"></i> Kegiatan
            </a>
        </li>
    </ul>
</nav>

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <header class="header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">@yield('title')</h2>
        <span class="d-md-none toggle-sidebar text-white fs-4" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </span>
    </header>

    <!-- Page Content -->
    <main class="main">
        @yield('content')
    </main>
</div>

<!-- Optional JS -->
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }
</script>

</body>
</html>
