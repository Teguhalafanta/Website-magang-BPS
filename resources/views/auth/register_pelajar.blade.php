<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @include('include.style')
</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo d-flex align-items-center justify-content-center mb-4">
                        <a href="index.html" class="d-flex align-items-center text-decoration-none">
                            <img src="{{ asset('template/assets/images/logo/pngegg.png') }}" alt="Logo"
                                style="width: 80px; height: auto; margin-right: 15px;">
                            <span style="font-size: 20px; font-weight: bold; color: #333;">
                                Badan Pusat Statistik Provinsi Aceh
                            </span>
                        </a>
                    </div>
                    <h1 class="auth-title">Sign Up Pelajar</h1>
                    <p class="auth-subtitle mb-5">
                        Daftarkan akun Anda untuk mengikuti program magang di BPS Provinsi Aceh.
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

                        <!-- Nama Lengkap -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="nama"
                                class="form-control @error('nama') is-invalid @enderror form-control-xl"
                                placeholder="Nama Lengkap" value="{{ old('nama') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            @error('nama')
                                <small class="btn btn-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- NIM (Primary Key) -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="nim"
                                class="form-control @error('nim') is-invalid @enderror form-control-xl"
                                placeholder="NIM" value="{{ old('nim') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-card-list"></i>
                            </div>
                            @error('nim')
                                <small class="btn btn-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Asal Universitas -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="asal_univ"
                                class="form-control @error('asal_univ') is-invalid @enderror form-control-xl"
                                placeholder="Asal Universitas" value="{{ old('asal_univ') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            @error('asal_univ')
                                <small class="btn btn-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Jurusan -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="jurusan"
                                class="form-control @error('jurusan') is-invalid @enderror form-control-xl"
                                placeholder="Jurusan" value="{{ old('jurusan') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            @error('jurusan')
                                <small class="btn btn-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Program Studi -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="prodi"
                                class="form-control @error('prodi') is-invalid @enderror form-control-xl"
                                placeholder="Program Studi" value="{{ old('prodi') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-book"></i>
                            </div>
                            @error('prodi')
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

                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign Up</button>
                    </form>

                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">
                            Already have an account?
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
