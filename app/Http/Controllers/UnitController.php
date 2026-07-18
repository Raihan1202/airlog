<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        try {
            $units = Unit::all();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'data' => $units]);
            }
            
            return view('manage.units.index', compact('units')); 

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('errorMessage', 'Gagal memuat data unit.');
        }
    }

    public function store(Request $request)
    {
        if (auth()->user()->access_level != 2) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            return back()->with('errorMessage', 'Hanya Admin yang bisa menambah unit.');
        }

        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255|unique:units,nama',
            ], [
                'nama.required' => 'Nama unit wajib diisi.',
                'nama.unique' => 'Nama unit sudah ada.',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
                }
                return back()->with('errorMessage', $validator->errors()->first());
            }

            $unit = Unit::create([
                'nama' => $request->nama
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Unit berhasil dibuat', 'data' => $unit], 201);
            }

            return back()->with('successMessage', 'Unit berhasil dibuat!');

        } catch (\Exception $e) {
            Log::error('Unit Store Error: ' . $e->getMessage());
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Gagal membuat unit.');
        }
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->access_level != 2) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            return back()->with('errorMessage', 'Unauthorized action.');
        }

        try {
            $unit = Unit::find($id);

            if (!$unit) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Unit not found'], 404);
                return back()->with('errorMessage', 'Unit tidak ditemukan');
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255|unique:units,nama,' . $id,
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
                return back()->with('errorMessage', $validator->errors()->first());
            }

            $unit->update(['nama' => $request->nama]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Unit updated', 'data' => $unit]);
            }
            return back()->with('successMessage', 'Unit berhasil diperbarui');

        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Gagal update unit.');
        }
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->user()->access_level != 2) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            return back()->with('errorMessage', 'Unauthorized action.');
        }

        try {
            $unit = Unit::find($id);

            if (!$unit) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Unit not found'], 404);
                return back()->with('errorMessage', 'Unit tidak ditemukan');
            }

            $unit->delete();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Unit deleted']);
            }
            return back()->with('successMessage', 'Unit berhasil dihapus');

        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Gagal menghapus unit.');
        }
    }
}