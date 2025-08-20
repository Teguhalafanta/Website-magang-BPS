<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    @include('include.style')
</head>

<body>
    <div id="auth" class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-5 shadow-lg text-center">
            <h1 class="mb-4">Sign Up</h1>
            <p>Pilih jenis akun untuk mendaftar:</p>

            <div class="d-flex justify-content-around mt-4">
                <a href="{{ route('register.pelajar') }}" class="btn btn-primary btn-lg">
                    Mahasiswa
                </a>
                <a href="{{ route('register.dosen') }}" class="btn btn-success btn-lg">
                    Dosen
                </a>
            </div>

            <div class="mt-5">
                <p>Sudah punya akun? <a href="{{ route('login') }}" class="font-bold">Log in</a></p>
            </div>
        </div>
    </div>
</body>

</html>
