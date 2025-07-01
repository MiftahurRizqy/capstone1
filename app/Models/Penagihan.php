<?php
// app/Models/Penagihan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penagihan extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'penagihan';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'pelanggan_id',
        'kontak_penagihan',
        'alamat_penagihan',
        'kode_pos_penagihan',
        'kabupaten_penagihan',
        'kota_penagihan',
        'no_hp_penagihan',
        'telepon_penagihan',
        'fax_penagihan',
        'email_penagihan',
        'cara_pembayaran',
        'waktu_pembayaran',
        'invoice_instalasi',
        'invoice_reguler',
        'mata_uang',
        'biaya_reguler',
        'kenakan_ppn',
        'keterangan',
    ];

    // Kolom yang harus di-cast ke tipe data tertentu
    protected $casts = [
        'biaya_reguler' => 'decimal:2', // Cast ke decimal dengan 2 angka di belakang koma
        'kenakan_ppn' => 'boolean',
    ];

    /**
     * Definisi relasi: Satu Penagihan dimiliki oleh satu Pelanggan.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }
}