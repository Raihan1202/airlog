<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Models\RecentDevice; 
use Jenssegers\Agent\Agent;  
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }
	
	public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Proses Login Bawaan Laravel
        $request->authenticate();

        $request->session()->regenerate();

        // 2. [TAMBAHAN BARU] Logika Simpan Recent Device (Sama persis dengan API)
        try {
            $user = $request->user();
            $agent = new Agent(); // Panggil library deteksi

            // Ambil detail
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $deviceType = $agent->isDesktop() ? 'Desktop' : ($agent->isPhone() ? 'Phone' : 'Tablet');
            $platform = $agent->platform() ?: 'Unknown OS';
            $browser = $agent->browser() ?: 'Unknown Browser';

            // Cek Duplikat
            $existingDevice = RecentDevice::where('user_id', $user->id)
                ->where('ip_address', $ip)
                ->where('user_agent', $userAgent)
                ->first();

            if ($existingDevice) {
                // Update jam login jika device sama
                $existingDevice->update(['last_login' => now()]);
            } else {
                // Buat baru jika device baru
                RecentDevice::create([
                    'user_id' => $user->id,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'device_type' => $deviceType,
                    'os' => $platform,
                    'browser' => $browser,
                    'country' => 'Indonesia', // Static dulu (bisa dikembangkan nanti)
                    'last_login' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Error silent agar user tetap bisa login meski pencatatan device gagal
            Log::error("Gagal mencatat device di Web Login: " . $e->getMessage());
        }

        // 3. Redirect ke Dashboard
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request)
    {
        // Logout dan hapus session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
