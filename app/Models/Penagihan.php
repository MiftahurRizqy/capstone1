<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id', 'kontak_penagihan', 'alamat_penagihan', 
        'kode_pos_penagihan', 'kabupaten_penagihan', 'kota_penagihan',
        'no_hp_penagihan', 'telepon_penagihan', 'fax_penagihan',
        'email_penagihan', 'cara_pembayaran', 'waktu_pembayaran',
        'invoice_instalasi', 'invoice_reguler', 'mata_uang',
        'biaya_reguler', 'kenakan_ppn', 'keterangan'
    ];

    protected $casts = [
        'kenakan_ppn' => 'boolean',
        'biaya_reguler' => 'decimal:2',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}