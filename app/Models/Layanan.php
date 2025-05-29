<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id', 'homepass', 'jenis_layanan', 'mulai_kontrak',
        'selesai_kontrak', 'perjanjian_trial', 'email_alternatif_1',
        'email_alternatif_2', 'pembelian_modem', 'jumlah_tv_kabel'
    ];

    protected $casts = [
        'mulai_kontrak' => 'date',
        'selesai_kontrak' => 'date',
        'perjanjian_trial' => 'boolean',
        'pembelian_modem' => 'boolean',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}