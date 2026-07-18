<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $positions = Position::paginate(10);
            
            if ($request->wantsJson()) {
                 return response()->json(['success' => true, 'data' => $positions]);
            }

            return view('positions.index', compact('positions'));
        } catch (\Exception $e) {
             return back()->with('errorMessage', 'Gagal memuat data jabatan.');
        }
    }

    public function update(Request $request)
    {
        try {
			if (auth()->user()->access_level != 2) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
                return back()->with('errorMessage', 'Hanya admin yang boleh mengubah jabatan.');
            }
            $validator = Validator::make($request->all(), [
                'position_name' => 'required|string|max:255',
                'position_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
                }
                return back()->with('errorMessage', $validator->errors()->first());
            }

            if ($request->position_id > 0) {
                $position = Position::find($request->position_id);
                if (!$position) {
                    throw new \Exception('Posisi tidak ditemukan');
                }
                $position->name = $request->position_name;
                $position->save();
                $message = 'Posisi berhasil diperbarui.';
            } else {
                $position = new Position();
                $position->name = $request->position_name;
                $position->save();
                $message = 'Posisi baru berhasil ditambahkan.';
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return back()->with('successMessage', $message);

        } catch (\Exception $e) {
            Log::error('Position Update Error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('errorMessage', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
			if (auth()->user()->access_level != 2) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
                return back()->with('errorMessage', 'Hanya admin yang boleh mengubah jabatan.');
            }
            $validator = Validator::make($request->all(), [
                'position_id' => 'required|integer|exists:positions,no',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Posisi tidak ditemukan'], 404);
                }
                return back()->with('errorMessage', 'Posisi tidak ditemukan.');
            }

            $position = Position::find($request->position_id);
            $position->delete();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Posisi berhasil dihapus']);
            }

            return back()->with('successMessage', 'Posisi berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Position Delete Error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('errorMessage', 'Gagal menghapus data.');
        }
    }
}