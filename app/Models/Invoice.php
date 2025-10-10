<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini.
     *
     * @var string
     */
    protected $table = 'invoice';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'nomor_invoice',
        'pelanggan_id',
        'layanan_id',
        'tipe',
        'jatuh_tempo',
        'tanggal_bayar',
        'status',
        'total_biaya',
        'mata_uang',
        'keterangan',
        'metode_pembayaran',
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu.
     *
     * @var array
     */
    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'total_biaya' => 'decimal:2',
    ];

    /**
     * Definisi relasi: Satu Invoice dimiliki oleh satu Pelanggan.
     *
     * @return BelongsTo
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Definisi relasi: Satu Invoice dimiliki oleh satu Layanan.
     *
     * @return BelongsTo
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }
}