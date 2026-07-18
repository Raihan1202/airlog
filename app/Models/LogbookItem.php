<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogbookItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'logbook_id', 
        'catatan',
        'tanggal_kegiatan',
        'tools',
        'teknisi', 
        'mulai', 
        'selesai'
    ];

    public function logbook()
    {
        return $this->belongsTo(Logbook::class);
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi');
    }

    public function teknisi_user()
    {
        return $this->belongsTo(User::class, 'teknisi');
    }
}
