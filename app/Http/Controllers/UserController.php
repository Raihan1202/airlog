<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function technicians()
    {
        $technicians = User::where('technician', 1)->get(['id', 'name', 'fullname', 'gelar']);

        return response()->json([
            'success' => true,
            'data' => $technicians
        ]);
    }

    public function positions()
    {
        $positions = Position::all();

        return response()->json([
            'success' => true,
            'data' => $positions
        ]);
    }

    public function index(Request $request)
    {
        if (auth()->user()->access_level != 2) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Access denied.'], 403);
            }
            abort(403, 'Access denied. Only Staff and Admin can manage users.');
        }

        $users = User::with('position')->paginate(10);
        $positions = Position::all();

        foreach ($users as $user) {
            $user->makeHidden(['password', 'remember_token']);
        }

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return view('users.index', compact('users', 'positions'));
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);

            $nameParts = explode(' ', $user->fullname, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gelar' => $user->gelar,
                    'username' => $user->name,
                    'email' => $user->email,
                    'position' => $user->position,
                    'country' => $user->country,
                    'address1' => $user->address,
                    'address2' => '',
                    'phone_number' => $user->phone_number,
                    'city' => $user->city,
                    'state' => $user->state,
                    'zip_code' => $user->zip_code,
                    'technician' => $user->technician,
                    'access_level' => $user->access_level,
                    'signature' => $user->signature,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }

    public function store(Request $request)
    {
        if (auth()->user()->access_level != 2) {
            return response()->json(['success' => false, 'message' => 'Hanya Admin yang bisa menambah user.'], 403);
        }

        try {
            $customAttributes = [
                'modalAddressFirstName' => 'First Name',
                'modalAddressLastName'  => 'Last Name',
                'modalUsername'         => 'Username',
                'modalAddressEmail'     => 'Email',
                'position'              => 'Position',
                'signature'             => 'Signature',
            ];

            $validated = $request->validate([
                'modalAddressFirstName' => 'required|string|max:255',
                'modalAddressLastName'  => 'required|string|max:255',
                'modalGelar'            => 'nullable|string|max:50',
                'modalUsername'         => 'required|alpha_num|unique:users,name|max:255',
                'modalAddressEmail'     => 'required|email|unique:users,email|max:255',
                'position'              => 'required|exists:positions,no',
                'modalAddressCountry'   => 'required|string|max:255',
                'modalAddressAddress1'  => 'required|string|max:255',
                'modalAddressAddress2'  => 'nullable|string|max:255',
                'modalPhoneNumber'      => 'required|string|max:20',
                'modalAddressCity'      => 'required|string|max:255',
                'modalAddressState'     => 'required|string|max:255',
                'modalAddressZipCode'   => 'required|string|max:10',
                'signature'             => 'required|string',
                'customRadioIcon-01'    => 'required|integer|in:0,1,2',
            ], [
                'modalAddressEmail.unique' => 'Email address is already in use.',
                'modalUsername.unique'     => 'Username is already taken.',
                'position.required'        => 'Position is required.',
                'position.exists'          => 'Selected position is invalid.',
            ], $customAttributes);

            $fullName = $validated['modalAddressFirstName'] . ' ' . $validated['modalAddressLastName'];

            $user = User::create([
                'name'            => strtolower($validated['modalUsername']),
                'fullname'        => $fullName,
                'gelar'           => $validated['modalGelar'] ?? '',
                'email'           => $validated['modalAddressEmail'],
                'password'        => Hash::make('password123'),
                'access_level'    => $validated['customRadioIcon-01'],
                'position'        => $validated['position'],
                'profile_picture' => 'default.png',
                'technician'      => $request->has('technician') ? 1 : 0,
                'signature'       => $validated['signature'],
                'country'         => $validated['modalAddressCountry'],
                'phone_number'    => $validated['modalPhoneNumber'],
                'address'         => $validated['modalAddressAddress1'] . ' ' . ($validated['modalAddressAddress2'] ?? ''),
                'city'            => $validated['modalAddressCity'],
                'state'           => $validated['modalAddressState'],
                'zip_code'        => $validated['modalAddressZipCode'],
                'joined'          => now(),
            ]);

            $user->makeHidden(['password', 'remember_token']);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $user
            ]);

        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return response()->json([
                'success' => false,
                'message' => $firstError,
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Create User Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'System error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (auth()->user()->access_level != 2) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            $user = User::findOrFail($id);

            $customAttributes = [
                'editFirstName' => 'First Name',
                'editLastName'  => 'Last Name',
                'editUsername'  => 'Username',
                'editEmail'     => 'Email',
                'position'      => 'Position',
                'editSignature' => 'Signature',
            ];

            $validated = $request->validate([
                'editFirstName'   => 'required|string|max:255',
                'editLastName'    => 'required|string|max:255',
                'editGelar'       => 'nullable|string|max:50',
                'editUsername'    => 'required|string|unique:users,name,' . $id . ',id|max:255',
                'editEmail'       => 'required|email|unique:users,email,' . $id . ',id|max:255',
                'position'        => 'required|exists:positions,no',
                'editCountry'     => 'required|string|max:255',
                'editAddress1'    => 'required|string|max:255',
                'editAddress2'    => 'nullable|string|max:255',
                'editPhoneNumber' => 'required|string|max:20',
                'editCity'        => 'required|string|max:255',
                'editState'       => 'required|string|max:255',
                'editZipCode'     => 'required|string|max:10',
                'editSignature'   => 'required|string',
                'editRadioIcon-01'=> 'required|integer|in:0,1,2',
            ], [
                'editEmail.unique' => 'Email address is already in use.',
                'editUsername.unique' => 'Username is already taken.',
                'position.required' => 'Position is required.',
                'position.exists' => 'Selected position is invalid.',
            ], $customAttributes);

            $fullName = $validated['editFirstName'] . ' ' . $validated['editLastName'];
			$technician = $request->wantsJson()
				? (int) $request->input('editTechnician', 0)
				: ($request->has('editTechnician') ? 1 : 0);

            $user->update([
                'name'         => strtolower($validated['editUsername']),
                'fullname'     => $fullName,
                'gelar'        => $validated['editGelar'] ?? '',
                'email'        => $validated['editEmail'],
                'access_level' => $validated['editRadioIcon-01'],
                'position'     => $validated['position'],
                'technician'   => $technician,
                'signature'    => $validated['editSignature'],
                'country'      => $validated['editCountry'],
                'phone_number' => $validated['editPhoneNumber'],
                'address'      => $validated['editAddress1'] . ' ' . ($validated['editAddress2'] ?? ''),
                'city'         => $validated['editCity'],
                'state'        => $validated['editState'],
                'zip_code'     => $validated['editZipCode'],
            ]);

            $user->makeHidden(['password', 'remember_token']);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);

        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return response()->json([
                'success' => false,
                'message' => $firstError,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update User Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'System error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            if (auth()->user()->access_level != 2) {
                return response()->json(['success' => false, 'message' => 'Hanya Admin yang bisa menghapus user.'], 403);
            }
            if (Auth::id() == $id) {
                return response()->json(['success' => false, 'message' => 'You cannot delete yourself.'], 403);
            }

            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus user'], 500);
        }
    }
}
