<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tools = Tool::all();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $tools
                ]);
            }

            return view('tools.index', compact('tools'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching tools: ' . $e->getMessage());
            return back()->with('errorMessage', 'Gagal memuat data alat.');
        }
    }

    public function update(Request $request)
    {
        try {
			if (auth()->user()->access_level != 2) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Akses ditolak. Hanya Admin.'], 403);
                return back()->with('errorMessage', 'Anda tidak memiliki akses admin.');
            }
            $validator = Validator::make($request->all(), [
                'tools_name' => 'required|string|max:255',
                'peralatan_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal: ' . $validator->errors()->first()
                    ], 422);
                }
                return back()->with('errorMessage', $validator->errors()->first());
            }

            if ($request->peralatan_id > 0) {
                $tool = Tool::find($request->peralatan_id);
                if (!$tool) {
                    throw new \Exception('Peralatan tidak ditemukan.');
                }
                $tool->name = $request->tools_name;
                $tool->save();
                $message = 'Peralatan berhasil diperbarui';
            } else {
                $tool = Tool::create(['name' => $request->tools_name]);
                $message = 'Peralatan baru berhasil ditambahkan';
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $tool
                ]);
            }

            return back()->with('successMessage', $message);

        } catch (\Exception $e) {
            Log::error('Tool Error: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('errorMessage', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
			if (auth()->user()->access_level != 2) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Akses ditolak. Hanya Admin.'], 403);
                return back()->with('errorMessage', 'Anda tidak memiliki akses admin.');
            }
            $validator = Validator::make($request->all(), [
                'peralatan_id' => 'required|integer|exists:alat,id',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Alat tidak ditemukan'], 404);
                }
                return back()->with('errorMessage', 'Alat tidak ditemukan.');
            }

            $tool = Tool::find($request->peralatan_id);
            $tool->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Peralatan berhasil dihapus'
                ]);
            }

            return back()->with('successMessage', 'Peralatan berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Delete Tool Error: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return back()->with('errorMessage', 'Gagal menghapus data.');
        }
    }
}