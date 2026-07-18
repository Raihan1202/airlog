<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Models\Unit;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
		if (env('APP_ENV') === 'production') {
			URL::forceScheme('https');
		}
        View::composer('layouts.sidebar', function ($view) {
            $view->with('units', Unit::all());
        });

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $notifications = DB::table('notifications')
                    ->join('user_notifications', 'notifications.id', '=', 'user_notifications.notification_id')
                    ->join('users', 'notifications.author_id', '=', 'users.id') 
                    // ------------------------------------------------
                    ->where('user_notifications.user_id', Auth::id())
                    ->select('notifications.*', 'user_notifications.status as is_read', 'users.fullname as author_name')
                    ->orderBy('notifications.created_at', 'desc')
                    ->limit(5)
                    ->get();

                $unreadCount = DB::table('user_notifications')
                    ->where('user_id', Auth::id())
                    ->where('status', 0)
                    ->count();

                $view->with('notifications', $notifications)
                    ->with('unreadCount', $unreadCount);
            }
        });
    }
}