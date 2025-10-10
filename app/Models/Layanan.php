<?php
// app/Models/Layanan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'layanan';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'pelanggan_id',
        'layanan_entry_id',
        'homepass',
        'mulai_kontrak',
        'selesai_kontrak',
        'perjanjian_trial',
        'email_alternatif_1',
        'email_alternatif_2',
        'pembelian_modem',
        'jumlah_tv_kabel',
    ];

    // Kolom yang harus di-cast ke tipe data tertentu
    protected $casts = [
        'mulai_kontrak' => 'date',
        'selesai_kontrak' => 'date',
        'perjanjian_trial' => 'boolean',
        'pembelian_modem' => 'boolean',
    ];

    /**
     * Definisi relasi: Satu Layanan dimiliki oleh satu Pelanggan.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

        public function layananEntry()
    {
        return $this->belongsTo(LayananEntry::class, 'layanan_entry_id', 'id');
    }
}