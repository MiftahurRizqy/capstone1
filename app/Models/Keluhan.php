<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keluhan extends Model
{
    use HasFactory;

    protected $table = 'keluhan';
    protected $primaryKey = 'id_keluhan';

    protected $fillable = [
        'layanan_induk_id',
        'pelanggan_id',
        'tujuan',
        'prioritas',
        'keluhan1',
        'keluhan2',
        'jenis_spk',
        'via',
        'deskripsi',
        'penyelesaian',
        'disampaikan_oleh',
        'sumber',
        'tanggal_input',
    ];

    protected $casts = [
        'tanggal_input' => 'datetime',
        'buat_spk' => 'boolean',
    ];

    /**
     * Get the layanan induk that owns the keluhan.
     */
    public function layananInduk(): BelongsTo
    {
        return $this->belongsTo(LayananInduk::class, 'layanan_induk_id');
    }

    /**
     * Get the pelanggan that owns the keluhan.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
    public function spk()
{
    return $this->hasOne(Spk::class, 'keluhan_id', 'id_keluhan');
}
}