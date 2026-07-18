<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RecentDevice;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed'], 422);
        }

        $throttleKey = 'login_api:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'status' => 'error',
                'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.'
            ], 429);
        }

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginType, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 60); 
            
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        RateLimiter::clear($throttleKey);

        try {
            $agent = new Agent();
            
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $deviceType = $agent->isDesktop() ? 'Desktop' : ($agent->isPhone() ? 'Phone' : 'Tablet');
            $platform = $agent->platform() ?: 'Unknown OS';
            $browser = $agent->browser() ?: 'Unknown Browser';

            $existingDevice = RecentDevice::where('user_id', $user->id)
                ->where('ip_address', $ip)
                ->where('user_agent', $userAgent)
                ->first();

            if ($existingDevice) {
                $existingDevice->update(['last_login' => now()]);
            } else {
                RecentDevice::create([
                    'user_id' => $user->id,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'device_type' => $deviceType,
                    'os' => $platform,
                    'browser' => $browser,
                    'country' => 'Indonesia',
                    'last_login' => now(),
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Gagal simpan recent device di API: " . $e->getMessage());
        }

        $tokenName = $request->device_name ?? ($platform . ' ' . $browser . ' App');
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user->load('position'),
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load(['position', 'recentDevices']);
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'fullname' => 'sometimes|string|max:255', 
            'gelar' => 'nullable|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'country' => 'nullable|string',
            'signature' => 'nullable|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        
        if ($request->has('signature')) {
            $base64Image = $validatedData['signature'];
			
            if (!empty($base64Image)) {
                $user->signature = $base64Image; 
            } else {
                $user->signature = null;
            }
        }
        
        if (isset($validatedData['fullname'])) {
            $user->fullname = $validatedData['fullname'];
            $user->name = $validatedData['fullname'];
        }
        
        if (isset($validatedData['gelar'])) $user->gelar = $validatedData['gelar'];
        if (isset($validatedData['email'])) $user->email = $validatedData['email'];
        if (isset($validatedData['phone_number'])) $user->phone_number = $validatedData['phone_number'];
        if (isset($validatedData['address'])) $user->address = $validatedData['address'];
        if (isset($validatedData['city'])) $user->city = $validatedData['city'];
        if (isset($validatedData['state'])) $user->state = $validatedData['state'];
        if (isset($validatedData['zip_code'])) $user->zip_code = $validatedData['zip_code'];
        if (isset($validatedData['country'])) $user->country = $validatedData['country'];


        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $user->load('position')
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Current password incorrect'], 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['status' => 'success', 'message' => 'Password changed successfully']);
    }
}