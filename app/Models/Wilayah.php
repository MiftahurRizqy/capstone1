<?php
// app/Models/Wilayah.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';

    // ID adalah auto-increment integer, jadi tidak perlu public $incrementing = false; atau protected $keyType = 'string';

    // Kolom-kolom yang bisa diisi secara massal
    protected $fillable = [
        'nama',
        'tipe',
        'deskripsi',
        'parent_id', // WAJIB ada di fillable karena diisi di seeder dan controller
        'provinsi_nama',
        'kabupaten_nama',
        'kecamatan_nama',
        'kelurahan_nama',
        'external_provinsi_id',
        'external_kabupaten_id',
        'external_kecamatan_id',
        'external_kelurahan_id',
    ];

    // Relasi untuk hirarki internal di tabel yang sama
    public function parent()
    {
        return $this->belongsTo(Wilayah::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Wilayah::class, 'parent_id');
    }

    // Scopes untuk mempermudah query berdasarkan tipe wilayah
    public function scopeProvinsi($query)
    {
        return $query->where('tipe', 'provinsi');
    }

    public function scopeKabupaten($query)
    {
        return $query->where('tipe', 'kabupaten');
    }

    public function scopeKecamatan($query)
    {
        return $query->where('tipe', 'kecamatan');
    }

    public function scopeKelurahan($query)
    {
        return $query->where('tipe', 'kelurahan');
    }

    public function scopeBagian($query)
    {
        return $query->where('tipe', 'bagian');
    }
}