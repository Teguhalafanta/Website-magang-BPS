<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BPS Provinsi Aceh</title>
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/png">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">

</head>

<body>
    <!-- Header Bar -->
    <div class="header-bar">
        <div class="nav-tabs">
            <a href="{{ route('login') }}" class="nav-tab {{ request()->routeIs('login') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-right"></i>
                Login
            </a>
            <a href="{{ route('register') }}" class="nav-tab {{ request()->routeIs('register') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i>
                Register
            </a>
        </div>
    </div>

    <!-- Animated Background -->
    <div class="background">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <h1>Aplikasi Magang</h1>
                <img src="{{ asset('assets/images/logo/pngegg.png') }}" alt="Logo"
                    style="width: 70px; margin-bottom: 10px;">
                <h1>Badan Pusat Statistik Provinsi Aceh</h1>
            </div>

            <!-- Login Form -->
            <div id="login-form">
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="form-group">
                        <div class="input-container">
                            <input type="email" id="email" name="email" placeholder="Email"
                                value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-container">
                            <input type="password" id="password" name="password" placeholder="Password" required>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Keep me logged in</label>
                    </div>

                    <button type="submit" class="btn-primary">
                        Log In
                        <div class="loading"></div>
                    </button>

                    <a href="{{ route('login.sso') }}" class="btn-sso">
                        <i class="bi bi-shield-check"></i>
                        Log in with SSO
                    </a>
                </form>
            </div>



            <div class="divider">
                <span id="divider-text">Belum punya akun?</span>
            </div>

            <div class="footer-links">
                <p><a href="{{ route('register') }}">Sign up</a></p>
                <p><a href="#">Forgot Password?</a></p>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/pages/auth.js') }}"></script>

</body>

</html>
