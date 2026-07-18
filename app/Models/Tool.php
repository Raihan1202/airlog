<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $table = 'alat';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['name'];
}