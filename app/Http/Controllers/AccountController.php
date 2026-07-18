<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function settings(Request $request)
    {
        return view('account.settings', [
            'user' => $request->user()
        ]);
    }

    public function security(Request $request)
    {
        $user = $request->user();
        $recentDevices = $user->recentDevices()->latest('last_login')->take(5)->get();

        return view('account.security', [
            'user' => $user,
            'recentDevices' => $recentDevices
        ]);
    }

	public function updateDetails(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);
        
        return back()->with('successMessage', 'Account details updated successfully!');
    }
	
	public function updatePassword(Request $request)
	{
		$validated = $request->validate([
			'current_password' => ['required', 'current_password'],
			'password' => [
				'required',
				\Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols(),
				'confirmed'
			],
		]);

		$request->user()->update([
			'password' => Hash::make($validated['password']),
		]);

		return back()->with('successMessage', 'Password updated successfully!');
	}

	private function isPasswordStrongEnough($password)
	{
		return preg_match('/[A-Z]/', $password) &&
			   preg_match('/[a-z]/', $password) &&
			   preg_match('/\d/', $password) &&
			   preg_match('/[\W_]/', $password);
	}
}