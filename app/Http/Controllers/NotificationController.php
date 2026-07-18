<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = DB::table('notifications')
            ->join('user_notifications', 'notifications.id', '=', 'user_notifications.notification_id')
            ->where('user_notifications.user_id', auth()->id())
            ->select(
                'notifications.*', 
                'user_notifications.id as pivot_id', 
                'user_notifications.status as is_read',
                'user_notifications.created_at as received_at'
            )
            ->orderBy('notifications.created_at', 'desc')
            ->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $notifications]);
        }

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount(Request $request)
    {
        $count = DB::table('user_notifications')
            ->where('user_id', auth()->id())
            ->where('status', 0)
            ->count();

        return response()->json(['success' => true, 'count' => $count]);
    }
	
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::find($id);

        DB::table('user_notifications')
            ->where('notification_id', $id)
            ->where('user_id', auth()->id())
            ->update(['status' => 1, 'updated_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Read',
                'redirect_url' => $notification ? $notification->link : null
            ]);
        }

        if ($notification && !empty($notification->link)) {
            return redirect($notification->link); 
        }

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        DB::table('user_notifications')
            ->where('user_id', auth()->id())
            ->where('status', 0) // Hanya update yang belum dibaca
            ->update(['status' => 1, 'updated_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'All marked as read']);
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'link' => 'nullable|string'
        ]);

        $notification = Notification::create([
            'author_id' => auth()->id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'link' => $validated['link'] ?? null,
        ]);

        foreach ($validated['user_ids'] as $userId) {
            DB::table('user_notifications')->insert([
                'user_id' => $userId,
                'notification_id' => $notification->id,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Created'], 201);
    }

    public function destroy(Request $request, $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not found'], 404);
            }
            return back()->with('errorMessage', 'Notifikasi tidak ditemukan.');
        }

        $user = auth()->user();
        
        if ($user->access_level == 2 || $user->id == $notification->author_id) {
            
            DB::table('user_notifications')->where('notification_id', $id)->delete();
            
            $notification->delete();
			
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Notification deleted']);
            }

            return back()->with('successMessage', 'Notifikasi berhasil dihapus.');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return back()->with('errorMessage', 'Anda tidak berhak menghapus notifikasi ini.');
    }
}