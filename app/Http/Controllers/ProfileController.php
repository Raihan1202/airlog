<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $notifications = $user->notifications()->latest()->take(5)->get();
        $unreadCount = $user->notifications()->whereNull('read_at')->count();

        return view('profile.show', compact('user', 'notifications', 'unreadCount'));
    }

    public function notifications(User $user)
    {
        $allNotifications = $user->notifications()->latest()->paginate(15);

        return view('profile.notifications', compact('user', 'allNotifications'));
    }

    public function generateQrCode(User $user)
    {
        $profileUrl = route('profile.show', $user->name);
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($profileUrl);

        return redirect($qrApiUrl);
    }
}