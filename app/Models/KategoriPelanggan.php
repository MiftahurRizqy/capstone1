<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelanggan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pelanggan';
    protected $guarded = ['id'];

    /**
     * Dapatkan semua pelanggan yang termasuk dalam kategori ini (One-to-Many).
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected $casts = [
        // Tambahkan casting untuk kolom-kolom yang menyimpan daftar field sebagai JSON
        'personal_fields' => 'array',
        'perusahaan_fields' => 'array',
    ];
    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
