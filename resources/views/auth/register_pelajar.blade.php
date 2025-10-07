<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BPS Provinsi Aceh</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

</head>

<body>
    <!-- Header Bar -->
    <div class="header-bar">
        <div class="nav-tabs">
            <a href="{{ route('login') }}" class="nav-tab" data-tab="login">
                <i class="bi bi-box-arrow-in-right"></i>
                Login
            </a>
            <a href="{{ route('register') }}" class="nav-tab active" data-tab="register">
                <i class="bi bi-person-plus"></i>
                Register
            </a>
        </div>
    </div>

    <!-- Background animasi -->
    <div class="background">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <!-- Container -->
    <div class="main-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-section">
                <h1>Aplikasi Magang</h1>
                <img src="{{ asset('assets/images/logo/pngegg.png') }}" alt="Logo BPS">
                <h1>Badan Pusat Statistik Provinsi Aceh</h1>
            </div>

            <!-- Register Form -->
            <div id="register-form">
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <!-- Username -->
                    <div class="form-group">
                        <div class="input-container">
                            <input type="text" name="username" placeholder="Username" value="{{ old('username') }}"
                                required>
                            <div class="input-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>
                        @error('username')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <div class="input-container">
                            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}"
                                required>
                            <div class="input-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <div class="input-container">
                            <input type="password" name="password" placeholder="Password" required>
                            <div class="input-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group">
                        <div class="input-container">
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                                required>
                            <div class="input-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        Sign Up
                        <div class="loading"></div>
                    </button>
                </form>
            </div>

            <div class="divider">
                <span>Sudah punya akun?</span>
            </div>

            <div class="footer-links">
                <p><a href="{{ route('login') }}">Sign In</a></p>
                <p><a href="#">Forgot Password?</a></p>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/pages/auth.js') }}"></script>

</body>

</html>
