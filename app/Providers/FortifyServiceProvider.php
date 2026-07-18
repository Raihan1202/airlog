<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
		Fortify::ignoreRoutes();
        // pakai view login custom
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // custom authenticate
        Fortify::authenticateUsing(function (Request $request) {
            // 🔹 Validasi captcha
            $captcha = $request->input('g-recaptcha-response');

            if (!$captcha) {
                return null; // captcha tidak dicentang
            }

            $response = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => env('RECAPTCHA_SECRET'),
                    'response' => $captcha,
                    'remoteip' => $request->ip(),
                ]
            );

            if (!$response->json('success')) {
                return null; // captcha gagal
            }

            // 🔹 Cek user berdasarkan username
            $user = User::where('username', $request->username)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });
    }
}
