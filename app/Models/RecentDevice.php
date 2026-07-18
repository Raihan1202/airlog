<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentDevice extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'device_type', 'os', 'browser', 'country', 'last_login'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
