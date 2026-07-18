<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'fullname',
        'gelar',
        'email',
        'password',
        'access_level',
        'position',
        'technician',
        'signature',
        'country',
        'phone_number',
        'address',
        'city',
        'state',
        'zip_code',
        'joined'
    ];

    protected $casts = [
        'joined' => 'datetime',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position', 'no');
    }

    public function notifications(): BelongsToMany 
    {
        return $this->belongsToMany(Notification::class, 'user_notifications')
                    ->withPivot('status')
                    ->withTimestamps()
                    ->orderByPivot('created_at', 'desc');
    }
    
    public function recentDevices() 
    {
        return $this->hasMany(RecentDevice::class)->latest('last_login');
    }
}