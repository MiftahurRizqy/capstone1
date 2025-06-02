<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pop extends Model
{
    use HasFactory;

    protected $table = 'pop';
    
    protected $fillable = [
        'nama_pop',
        'kabupaten_kota',
        'daerah',
        'rt_rw'
    ];
}