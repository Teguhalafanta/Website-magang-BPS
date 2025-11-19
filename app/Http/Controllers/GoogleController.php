<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect user ke halaman login Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google setelah login berhasil.
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah ada di database berdasarkan google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            // Jika belum ada, cek apakah email-nya sudah pernah terdaftar
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();

                if (!$user) {
                    // Jika belum ada user dengan email tsb, buat user baru
                    $user = User::create([
                        'username' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'google_token' => $googleUser->token,
                        'password' => bcrypt(Str::random(16)),
                        'role' => 'pelajar',
                    ]);
                } else {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'google_token' => $googleUser->token,
                    ]);
                }
            }

            // Login user ke sistem
            Auth::login($user);

            // Redirect ke dashboard pelajar
            return redirect()->intended('pelajar/dashboard');
        } catch (\Exception $e) {
            // Jika terjadi error, kembali ke halaman login
            return redirect('/login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
