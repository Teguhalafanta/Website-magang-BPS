<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- {{-- css login --}} -->
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">
</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo d-flex align-items-center justify-content-center mb-4">
                        <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
                            <img src="{{ asset('assets/images/logo/pngegg.png') }}" alt="Logo"
                                style="width: 80px; height: auto; margin-right: 15px;">
                            <span style="font-size: 20px; font-weight: bold; color: #333;">
                                Badan Pusat Statistik Provinsi Aceh
                            </span>
                        </a>
                    </div>
                    <h1 class="auth-title">Sign Up</h1>
                    <p class="auth-subtitle mb-5">
                        Daftarkan akun Anda untuk mengakses sistem magang BPS Provinsi Aceh.
                    </p>

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <!-- Username -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror form-control-xl"
                                placeholder="Username" value="{{ old('username') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            @error('username')
                            <small class="btn btn-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror form-control-xl"
                                placeholder="Email" value="{{ old('email') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            @error('email')
                            <small class="btn btn-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror form-control-xl"
                                placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            @error('password')
                            <small class="btn btn-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password_confirmation"
                                class="form-control form-control-xl"
                                placeholder="Konfirmasi Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign Up</button>
                    </form>

                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="font-bold">Log in</a>.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right"></div>
            </div>
        </div>

    </div>
</body>

</html>