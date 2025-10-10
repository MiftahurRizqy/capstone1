<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spk extends Model
{
    use HasFactory;

    protected $table = 'spk';
    protected $primaryKey = 'nomor_spk';
    public $incrementing = false; // Karena primary key-nya string, bukan integer
    protected $keyType = 'string';

    protected $fillable = [
        'nomor_spk',
        'keluhan_id',
        'pelanggan_id',
        'nomor_pelanggan',
        'nama_lengkap',
        'nama_perusahaan',
        'layanan_induk_id',
        'pop_id',
        'alamat',
        'wilayah_id',
        'kelengkapan_kerja',
        'keterangan',
        'tipe',
        'status',
        'pelaksana_1',
        'pelaksana_2',
        'koordinator',
        'selesai_at',
        'rencana_pengerjaan',
        'user_id',
    ];

    protected $casts = [
        'selesai_at' => 'datetime',
        'rencana_pengerjaan' => 'datetime',
    ];

    public function keluhan(): BelongsTo
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id', 'id_keluhan');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function layananInduk(): BelongsTo
    {
        return $this->belongsTo(LayananInduk::class, 'layanan_induk_id');
    }

    public function pop(): BelongsTo
    {
        return $this->belongsTo(Pop::class, 'pop_id');
    }
public function user(): BelongsTo
{
    return $this->belongsTo(\App\Models\User::class, 'user_id'); 
}
}