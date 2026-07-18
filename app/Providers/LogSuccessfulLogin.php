<?php

namespace App\Providers;

use App\Providers\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Jenssegers\Agent\Agent; // <-- Import Agent
use App\Models\RecentDevice; // <-- Import model

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $agent = new Agent();
        RecentDevice::create([
            'user_id' => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => $agent->device(),
            'os' => $agent->platform(),
            'browser' => $agent->browser(),
            'last_login' => now(),
            // Anda bisa menambahkan integrasi GeoIP untuk 'country'
        ]);
    }
}
