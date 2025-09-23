<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananInduk extends Model
{
    use HasFactory;

    protected $table = 'layanan_induk';

    protected $fillable = [
        'nama_layanan_induk',
    ];
}
