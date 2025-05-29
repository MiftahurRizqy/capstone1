<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_card', 'tipe', 'pop', 'alamat', 'kode_pos', 'kabupaten', 
        'kota', 'wilayah', 'no_hp', 'nama_kontak', 'tipe_identitas', 
        'nomor_identitas', 'reseller', 'nama_lengkap', 'tanggal_lahir', 
        'jenis_kelamin', 'pekerjaan', 'nama_perusahaan', 'jenis_usaha', 
        'account_manager', 'telepon_perusahaan', 'fax', 'email', 'npwp'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'reseller' => 'boolean',
    ];

    public function layanan()
    {
        return $this->hasMany(Layanan::class);
    }

    public function penagihan()
    {
        return $this->hasOne(Penagihan::class);
    }
}