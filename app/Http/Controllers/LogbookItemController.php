<?php

namespace App\Http\Controllers;

use App\Models\LogbookItem;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class LogbookItemController extends Controller
{
    public function index(Request $request, $unit_id, $logbook_id)
    {
        try {
            $logbook = Logbook::find($logbook_id);
            if (!$logbook) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Logbook tidak ditemukan'], 404);
                }
                return back()->with('errorMessage', 'Logbook tidak ditemukan');
            }

            $query = LogbookItem::with('teknisi_user')
                        ->where('logbook_id', $logbook_id);

            if ($request->has('item_id') && !empty($request->item_id)) {
                $query->where('id', $request->item_id);
            }

            $items = $query->orderBy('created_at', 'asc')->get();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'count' => $items->count(),
                    'data' => $items
                ]);
            }
            
            return redirect()->route('logbook.edit.content', ['unit_id' => $unit_id, 'logbook_id' => $logbook_id]);

        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Gagal memuat items.');
        }
    }

    public function store(Request $request, $unit_id, $logbook_id)
    {
        try {
            $logbook = Logbook::find($logbook_id);
            if (!$logbook) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Logbook induk tidak ditemukan'], 404);
                return back()->with('errorMessage', 'Logbook tidak ditemukan');
            }

            $currentItemsCount = LogbookItem::where('logbook_id', $logbook_id)->count();
            if ($currentItemsCount >= 10) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Maksimal 10 item per logbook.'], 422);
                }
                return redirect()->back()->with('errorMessage', 'Maksimal 10 content per logbook sudah tercapai!');
            }

            $validator = Validator::make($request->all(), [
                'catatan' => 'required|string|min:5|max:1000',
                'tanggal_kegiatan' => 'required|date',
                'tools' => 'required|string|max:255',
                'teknisi' => 'required|integer|exists:users,id',
                'mulai' => 'required',
                'selesai' => 'required',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
                }
                return back()->with('errorMessage', $validator->errors()->first())->withInput();
            }

            $logbookItem = new LogbookItem();
            $logbookItem->logbook_id = $logbook_id;
            $logbookItem->catatan = $request->catatan;
            $logbookItem->tanggal_kegiatan = $request->tanggal_kegiatan;
            $logbookItem->tools = $request->tools;
            $logbookItem->teknisi = $request->teknisi;
            $logbookItem->mulai = $request->mulai;
            $logbookItem->selesai = $request->selesai;
            $logbookItem->save();

            Cache::forget('logbook_items_' . $logbook_id);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item berhasil ditambahkan',
                    'data' => $logbookItem
                ], 201);
            }

            return redirect()->route('logbook.edit.content', ['unit_id' => $unit_id, 'logbook_id' => $logbook_id])
                             ->with('successMessage', 'Item logbook berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Store Item Error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->with('errorMessage', 'Terjadi kesalahan sistem.');
        }
    }

    public function update(Request $request, $unit_id, $logbook_id, $item_id)
    {
        try {
            $logbookItem = LogbookItem::where('logbook_id', $logbook_id)->find($item_id);
            if (!$logbookItem) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
                return back()->with('errorMessage', 'Item tidak ditemukan');
            }

            $validator = Validator::make($request->all(), [
                'catatan' => 'required|string|min:5|max:1000',
                'tanggal_kegiatan' => 'required|date',
                'tools' => 'required|string|max:255',
                'teknisi' => 'required|integer|exists:users,id',
                'mulai' => 'required',
                'selesai' => 'required',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
                return back()->with('errorMessage', $validator->errors()->first());
            }

            $logbookItem->update([
                'catatan' => $request->catatan,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'tools' => $request->tools,
                'teknisi' => $request->teknisi,
                'mulai' => $request->mulai,
                'selesai' => $request->selesai
            ]);

            Cache::forget('logbook_items_' . $logbook_id);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item berhasil diperbarui',
                    'data' => $logbookItem
                ]);
            }

            return redirect()->route('logbook.edit.content', ['unit_id' => $unit_id, 'logbook_id' => $logbook_id])
                             ->with('successMessage', 'Item logbook berhasil diperbarui!');
                             
        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Gagal update data.');
        }
    }

    public function destroy(Request $request, $unit_id, $logbook_id, $item_id)
    {
        try {
            $user = $request->user();

            $logbookItem = LogbookItem::where('logbook_id', $logbook_id)->find($item_id);
            
            if (!$logbookItem) {
                return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
            }

            $isAdmin = $user->access_level == 2;
            $isAuthorItem = $logbookItem->teknisi == $user->id;
            
            $logbook = Logbook::find($logbook_id);
            $isLogbookCreator = $logbook && $logbook->created_by == $user->id;

            if (!$isAdmin && !$isAuthorItem && !$isLogbookCreator) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized. Anda tidak memiliki izin menghapus item ini.'
                ], 403);
            }
            // ---------------------

            $logbookItem->delete();
            Cache::forget('logbook_items_' . $logbook_id);

            return response()->json([
                'success' => true, 
                'message' => 'Item berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }
    
    public function getByTeknisi(Request $request) {
        try {
            $userId = $request->user()->id;
            $items = LogbookItem::where('teknisi', $userId)->with(['logbook.unit'])->latest()->paginate(10);
            return response()->json(['success' => true, 'data' => $items]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function teknisiSummary(Request $request) {
        try {
            $userId = $request->user()->id;
            $totalTasks = LogbookItem::where('teknisi', $userId)->count();
            return response()->json(['success' => true, 'data' => ['total_tasks' => $totalTasks]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}