<?php
// app/Models/Pop.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pop extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'pop';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'nama_pop',
        'kabupaten_kota',
        'daerah',
        'rt_rw',
    ];

    /**
     * Definisi relasi: Satu POP memiliki banyak Pelanggan.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'pop_id', 'id');
    }
}