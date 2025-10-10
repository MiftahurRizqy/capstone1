<?php
// app/Models/Pelanggan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'pelanggan';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'nomor_pelanggan',
        'member_card',
        'kategori_pelanggan_id', 
        'pop_id', // Perbaikan: Menggunakan pop_id sesuai migrasi
        'alamat',
        'kode_pos',
        'kabupaten',
        'kota',
        'wilayah',
        'no_hp',
        'nama_kontak',
        'tipe_identitas',
        'nomor_identitas',
        'reseller',
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'nama_perusahaan',
        'jenis_usaha',
        'account_manager',
        'telepon_perusahaan',
        'fax',
        'email',
        'npwp',
    ];

    // Kolom yang harus di-cast ke tipe data tertentu
    protected $casts = [
        'tanggal_lahir' => 'date',
        'reseller' => 'boolean',
    ];

    /**
     * Definisi relasi: Satu Pelanggan memiliki satu POP.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
        public function kategori()
    {
        // Secara default Eloquent akan mencari 'kategori_pelanggan_id'
        return $this->belongsTo(KategoriPelanggan::class, 'kategori_pelanggan_id');
    }
    public function pop()
    {
        return $this->belongsTo(Pop::class, 'pop_id', 'id');
    }

    /**
     * Definisi relasi: Satu Pelanggan memiliki banyak Layanan.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function layanan()
    {
        return $this->hasMany(Layanan::class, 'pelanggan_id', 'id');
    }

    /**
     * Definisi relasi: Satu Pelanggan memiliki satu Penagihan.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function penagihan()
    {
        return $this->hasOne(Penagihan::class, 'pelanggan_id', 'id');
    }
    
}