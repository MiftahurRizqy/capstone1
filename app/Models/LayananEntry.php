<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananEntry extends Model
{
    use HasFactory;

    protected $table = 'layanan_entry';

    protected $fillable = [
        'kode', 'nama_paket', 'status', 'tipe', 'kelompok_layanan', 'layanan_induk_id',
        'spk_osp_instalasi', 'spk_isp_instalasi', 'spk_osp_aktif_kembali', 'spk_isp_aktif_kembali',
        'tipe_layanan_spk', 'konfigurasi_dhcp', 'utilisasi_bandwidth', 'biaya_setup',
        'biaya_reguler_1_bulan', 'biaya_reguler_3_bulan', 'bonus_reguler_3_bulan',
        'biaya_reguler_6_bulan', 'bonus_reguler_6_bulan', 'biaya_reguler_12_bulan',
        'bonus_reguler_12_bulan', 'koneksi_tv_kabel', 'kompensasi_diskoneksi',
        'redaksional_invoice', 'redaksional_invoice_2', 'account_myob_1',
        'biaya_reguler_1', 'account_myob_2', 'biaya_reguler_2',
        'nama_milis', 'deskripsi'
    ];

    protected $casts = [
        'spk_osp_instalasi' => 'boolean',
        'spk_isp_instalasi' => 'boolean',
        'spk_osp_aktif_kembali' => 'boolean',
        'spk_isp_aktif_kembali' => 'boolean',
    ];

    public function layananInduk()
    {
        return $this->belongsTo(LayananInduk::class);
    }
}