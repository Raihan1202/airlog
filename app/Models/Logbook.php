<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LogbookTeknisi;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id', 'date', 'shift', 'created_by', 'approved_by', 'is_approved', 'catatan', 'signed_by', 'signed_at', 'judul'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function items()
    {
        return $this->hasMany(LogbookItem::class);
    }

    // public function teknisi()
    // {
    //     return $this->hasMany(LogbookTeknisi::class);
    // }
}
