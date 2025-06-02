<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Penagihan;
use App\Models\Pop;


class PelangganController extends Controller
{
    protected $table = 'pelanggan'; // Tambahkan ini untuk mengatasi error tabel tidak ditemukan

    public function personal()
    {
        $pelanggan = Pelanggan::where('tipe', 'personal')->get();
        $pops = Pop::all();
        return view('backend.pages.pelanggan.personal', compact('pelanggan', 'pops'));
    }

    public function perusahaan()
    {
        $pelanggan = Pelanggan::where('tipe', 'perusahaan')->get();
        $pops = Pop::all();
        return view('backend.pages.pelanggan.perusahaan', compact('pelanggan', 'pops'));
    }

    // Simpan data pelanggan baru
    public function store(Request $request)
    {
        $request->validate([
            'member_card' => 'required|unique:pelanggan',
            'nama_lengkap' => 'required_if:tipe,personal',
            'nama_perusahaan' => 'required_if:tipe,perusahaan',
            'no_hp' => 'required',
            'alamat' => 'required',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);

        try {
            // Simpan data pelanggan
            $pelanggan = Pelanggan::create([
                'member_card' => $request->member_card,
                'tipe' => $request->tipe,
                'pop' => $request->pop,
                'alamat' => $request->alamat,
                'kode_pos' => $request->kode_pos,
                'kabupaten' => $request->kabupaten,
                'kota' => $request->kota,
                'wilayah' => $request->wilayah,
                'no_hp' => $request->no_hp,
                'nama_kontak' => $request->nama_kontak,
                'tipe_identitas' => $request->tipe_identitas,
                'nomor_identitas' => $request->nomor_identitas,
                'reseller' => $request->has('reseller'),
                'nama_lengkap' => $request->nama_lengkap,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pekerjaan' => $request->pekerjaan,
                'nama_perusahaan' => $request->nama_perusahaan,
                'jenis_usaha' => $request->jenis_usaha,
                'account_manager' => $request->account_manager,
                'telepon_perusahaan' => $request->telepon_perusahaan,
                'fax' => $request->fax,
                'email' => $request->email,
                'npwp' => $request->npwp,
            ]);

            // Simpan data layanan jika ada
            if ($request->has('jenis_layanan')) {
                Layanan::create([
                    'pelanggan_id' => $pelanggan->id,
                    'homepass' => $request->homepass,
                    'jenis_layanan' => $request->jenis_layanan,
                    'mulai_kontrak' => $request->mulai_kontrak,
                    'selesai_kontrak' => $request->selesai_kontrak,
                    'perjanjian_trial' => $request->has('perjanjian_trial'),
                    'email_alternatif_1' => $request->email_alternatif_1,
                    'email_alternatif_2' => $request->email_alternatif_2,
                    'pembelian_modem' => $request->has('pembelian_modem'),
                    'jumlah_tv_kabel' => $request->jumlah_tv_kabel ?? 0,
                ]);
            }

            // Simpan data penagihan jika ada
            if ($request->has('kontak_penagihan')) {
                Penagihan::create([
                    'pelanggan_id' => $pelanggan->id,
                    'kontak_penagihan' => $request->kontak_penagihan,
                    'alamat_penagihan' => $request->alamat_penagihan,
                    'kode_pos_penagihan' => $request->kode_pos_penagihan,
                    'kabupaten_penagihan' => $request->kabupaten_penagihan,
                    'kota_penagihan' => $request->kota_penagihan,
                    'no_hp_penagihan' => $request->no_hp_penagihan,
                    'telepon_penagihan' => $request->telepon_penagihan,
                    'fax_penagihan' => $request->fax_penagihan,
                    'email_penagihan' => $request->email_penagihan,
                    'cara_pembayaran' => $request->cara_pembayaran,
                    'waktu_pembayaran' => $request->waktu_pembayaran,
                    'invoice_instalasi' => $request->invoice_instalasi,
                    'invoice_reguler' => $request->invoice_reguler,
                    'mata_uang' => $request->mata_uang,
                    'biaya_reguler' => $request->biaya_reguler,
                    'kenakan_ppn' => $request->has('kenakan_ppn'),
                    'keterangan' => $request->keterangan,
                ]);
            }

            return redirect()->back()->with('success', 'Data pelanggan berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // Tambahkan method lain seperti edit, update, delete sesuai kebutuhan
    public function edit($id)
    {
        $pelanggan = Pelanggan::with(['layanan', 'penagihan'])->findOrFail($id);
        return view('backend.pages.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        // Implementasi update
    }

    public function destroy($id)
    {
        // Implementasi delete
    }
}
