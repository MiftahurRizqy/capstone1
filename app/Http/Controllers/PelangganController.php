<?php
// app/Http/Controllers/PelangganController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Penagihan;
use App\Models\Pop; // Pastikan semua model di-import
use Illuminate\Support\Facades\Log;


class PelangganController extends Controller
{
    // Hapus properti $table, ini adalah properti Model, bukan Controller.

    /**
     * Menampilkan daftar pelanggan dengan tipe 'personal'.
     * @return \Illuminate\View\View
     */
    public function personal()
    {
        // Eager load relasi 'pop' untuk menghindari N+1 query problem
        $pelanggan = Pelanggan::where('tipe', 'personal')->with('pop')->get();
        $pops = Pop::all(); // Diperlukan untuk form atau dropdown di view
        return view('backend.pages.pelanggan.personal', compact('pelanggan', 'pops'));
    }

    /**
     * Menampilkan daftar pelanggan dengan tipe 'perusahaan'.
     * @return \Illuminate\View\View
     */
    public function perusahaan()
    {
        // Eager load relasi 'pop' untuk menghindari N+1 query problem
        $pelanggan = Pelanggan::where('tipe', 'perusahaan')->with('pop')->get();
        $pops = Pop::all(); // Diperlukan untuk form atau dropdown di view
        return view('backend.pages.pelanggan.perusahaan', compact('pelanggan', 'pops'));
    }

    /**
     * Menyimpan data pelanggan baru (personal atau perusahaan) beserta layanan dan penagihan.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Aturan validasi dasar
        $rules = [
            'member_card' => 'required|string|max:255|unique:pelanggan,member_card',
            'tipe' => 'required|in:personal,perusahaan',
            'pop_id' => 'required|exists:pop,id', // Pastikan pop_id ada di tabel pop
            'alamat' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'kabupaten' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'wilayah' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'nama_kontak' => 'required|string|max:255',
            'tipe_identitas' => 'nullable|string|max:50',
            'nomor_identitas' => 'nullable|string|max:255',
            'reseller' => 'boolean',
        ];

        // Aturan validasi spesifik untuk 'personal'
        if ($request->tipe === 'personal') {
            $rules['nama_lengkap'] = 'required|string|max:255';
            $rules['tanggal_lahir'] = 'nullable|date';
            $rules['jenis_kelamin'] = 'nullable|in:L,P';
            $rules['pekerjaan'] = 'nullable|string|max:255';
        }

        // Aturan validasi spesifik untuk 'perusahaan'
        if ($request->tipe === 'perusahaan') {
            $rules['nama_perusahaan'] = 'required|string|max:255';
            $rules['jenis_usaha'] = 'nullable|string|max:255';
            $rules['account_manager'] = 'nullable|string|max:255';
            $rules['telepon_perusahaan'] = 'nullable|string|max:20';
            $rules['fax'] = 'nullable|string|max:20';
            $rules['email'] = 'nullable|email|max:255';
            $rules['npwp'] = 'nullable|string|max:255';
        }

        // Aturan validasi untuk Layanan (jika ada)
        if ($request->has('jenis_layanan')) {
            $rules['homepass'] = 'nullable|string|max:255';
            $rules['jenis_layanan'] = 'required|string|max:255';
            $rules['mulai_kontrak'] = 'required|date';
            $rules['selesai_kontrak'] = 'required|date|after_or_equal:mulai_kontrak';
            $rules['perjanjian_trial'] = 'boolean';
            $rules['email_alternatif_1'] = 'nullable|email|max:255';
            $rules['email_alternatif_2'] = 'nullable|email|max:255';
            $rules['pembelian_modem'] = 'boolean';
            $rules['jumlah_tv_kabel'] = 'nullable|integer|min:0';
        }

        // Aturan validasi untuk Penagihan (jika ada)
        if ($request->has('kontak_penagihan')) {
            $rules['kontak_penagihan'] = 'required|string|max:255';
            $rules['alamat_penagihan'] = 'required|string|max:255';
            $rules['kode_pos_penagihan'] = 'required|string|max:10';
            $rules['kabupaten_penagihan'] = 'required|string|max:255';
            $rules['kota_penagihan'] = 'required|string|max:255';
            $rules['no_hp_penagihan'] = 'required|string|max:20';
            $rules['telepon_penagihan'] = 'nullable|string|max:20';
            $rules['fax_penagihan'] = 'nullable|string|max:20';
            $rules['email_penagihan'] = 'nullable|email|max:255';
            $rules['cara_pembayaran'] = 'required|string|max:255';
            $rules['waktu_pembayaran'] = 'required|string|max:255';
            $rules['invoice_instalasi'] = 'nullable|string|max:255';
            $rules['invoice_reguler'] = 'required|string|max:255';
            $rules['mata_uang'] = 'required|in:IDR';
            $rules['biaya_reguler'] = 'required|numeric|min:0';
            $rules['kenakan_ppn'] = 'boolean';
            $rules['keterangan'] = 'nullable|string';
        }

        $request->validate($rules);

        try {
            // Siapkan data untuk Pelanggan
            $pelangganData = $request->only([
                'member_card', 'tipe', 'pop_id', 'alamat', 'kode_pos', 'kabupaten',
                'kota', 'wilayah', 'no_hp', 'nama_kontak', 'tipe_identitas',
                'nomor_identitas', 'nama_lengkap', 'tanggal_lahir', 'jenis_kelamin',
                'pekerjaan', 'nama_perusahaan', 'jenis_usaha', 'account_manager',
                'telepon_perusahaan', 'fax', 'email', 'npwp'
            ]);
            // Pastikan nilai boolean diambil dengan benar
            $pelangganData['reseller'] = $request->has('reseller');

            // Simpan data pelanggan
            $pelanggan = Pelanggan::create($pelangganData);

            // Simpan data layanan jika ada
            if ($request->has('jenis_layanan')) {
                $layananData = $request->only([
                    'homepass', 'jenis_layanan', 'mulai_kontrak', 'selesai_kontrak',
                    'email_alternatif_1', 'email_alternatif_2', 'jumlah_tv_kabel'
                ]);
                $layananData['pelanggan_id'] = $pelanggan->id;
                $layananData['perjanjian_trial'] = $request->has('perjanjian_trial');
                $layananData['pembelian_modem'] = $request->has('pembelian_modem');

                Layanan::create($layananData);
            }

            // Simpan data penagihan jika ada
            // Disarankan untuk selalu menyimpan data penagihan jika pelanggan dibuat,
            // atau tambahkan validasi yang lebih ketat jika ini opsional.
            if ($request->has('kontak_penagihan')) {
                $penagihanData = $request->only([
                    'kontak_penagihan', 'alamat_penagihan', 'kode_pos_penagihan',
                    'kabupaten_penagihan', 'kota_penagihan', 'no_hp_penagihan',
                    'telepon_penagihan', 'fax_penagihan', 'email_penagihan',
                    'cara_pembayaran', 'waktu_pembayaran', 'invoice_instalasi',
                    'invoice_reguler', 'mata_uang', 'biaya_reguler', 'keterangan'
                ]);
                $penagihanData['pelanggan_id'] = $pelanggan->id;
                $penagihanData['kenakan_ppn'] = $request->has('kenakan_ppn');

                Penagihan::create($penagihanData);
            }

            return redirect()->back()->with('success', 'Data pelanggan berhasil disimpan.');
        } catch (\Exception $e) {
            // Log error untuk debugging lebih lanjut
            Log::error('Gagal menyimpan data pelanggan: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit untuk pelanggan tertentu.
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Eager load semua relasi yang mungkin diperlukan di form edit
        $pelanggan = Pelanggan::with(['layanan', 'penagihan', 'pop'])->findOrFail($id);
        $pops = Pop::all(); // Diperlukan untuk dropdown POP di form edit
        return view('backend.pages.pelanggan.edit', compact('pelanggan', 'pops'));
    }

    /**
     * Memperbarui data pelanggan yang sudah ada.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Aturan validasi dasar untuk update
        $rules = [
            // member_card harus unik kecuali untuk record yang sedang diedit
            'member_card' => 'required|string|max:255|unique:pelanggan,member_card,' . $id,
            'tipe' => 'required|in:personal,perusahaan',
            'pop_id' => 'required|exists:pop,id',
            'alamat' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'kabupaten' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'wilayah' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'nama_kontak' => 'required|string|max:255',
            'tipe_identitas' => 'nullable|string|max:50',
            'nomor_identitas' => 'nullable|string|max:255',
            'reseller' => 'boolean',
        ];

        // Aturan validasi spesifik untuk 'personal'
        if ($request->tipe === 'personal') {
            $rules['nama_lengkap'] = 'required|string|max:255';
            $rules['tanggal_lahir'] = 'nullable|date';
            $rules['jenis_kelamin'] = 'nullable|in:L,P';
            $rules['pekerjaan'] = 'nullable|string|max:255';
            // Set kolom perusahaan menjadi null jika tipe berubah dari perusahaan ke personal
            $request->merge([
                'nama_perusahaan' => null, 'jenis_usaha' => null, 'account_manager' => null,
                'telepon_perusahaan' => null, 'fax' => null, 'email' => null, 'npwp' => null
            ]);
        }

        // Aturan validasi spesifik untuk 'perusahaan'
        if ($request->tipe === 'perusahaan') {
            $rules['nama_perusahaan'] = 'required|string|max:255';
            $rules['jenis_usaha'] = 'nullable|string|max:255';
            $rules['account_manager'] = 'nullable|string|max:255';
            $rules['telepon_perusahaan'] = 'nullable|string|max:20';
            $rules['fax'] = 'nullable|string|max:20';
            $rules['email'] = 'nullable|email|max:255';
            $rules['npwp'] = 'nullable|string|max:255';
            // Set kolom personal menjadi null jika tipe berubah dari personal ke perusahaan
            $request->merge([
                'nama_lengkap' => null, 'tanggal_lahir' => null, 'jenis_kelamin' => null,
                'pekerjaan' => null
            ]);
        }

        // Aturan validasi untuk Layanan (jika ada)
        // Jika form layanan dikirim, validasi
        if ($request->has('jenis_layanan')) {
            $rules['homepass'] = 'nullable|string|max:255';
            $rules['jenis_layanan'] = 'required|string|max:255';
            $rules['mulai_kontrak'] = 'required|date';
            $rules['selesai_kontrak'] = 'required|date|after_or_equal:mulai_kontrak';
            $rules['perjanjian_trial'] = 'boolean';
            $rules['email_alternatif_1'] = 'nullable|email|max:255';
            $rules['email_alternatif_2'] = 'nullable|email|max:255';
            $rules['pembelian_modem'] = 'boolean';
            $rules['jumlah_tv_kabel'] = 'nullable|integer|min:0';
        }

        // Aturan validasi untuk Penagihan (jika ada)
        if ($request->has('kontak_penagihan')) {
            $rules['kontak_penagihan'] = 'required|string|max:255';
            $rules['alamat_penagihan'] = 'required|string|max:255';
            $rules['kode_pos_penagihan'] = 'required|string|max:10';
            $rules['kabupaten_penagihan'] = 'required|string|max:255';
            $rules['kota_penagihan'] = 'required|string|max:255';
            $rules['no_hp_penagihan'] = 'required|string|max:20';
            $rules['telepon_penagihan'] = 'nullable|string|max:20';
            $rules['fax_penagihan'] = 'nullable|string|max:20';
            $rules['email_penagihan'] = 'nullable|email|max:255';
            $rules['cara_pembayaran'] = 'required|string|max:255';
            $rules['waktu_pembayaran'] = 'required|string|max:255';
            $rules['invoice_instalasi'] = 'nullable|string|max:255';
            $rules['invoice_reguler'] = 'required|string|max:255';
            $rules['mata_uang'] = 'required|in:IDR';
            $rules['biaya_reguler'] = 'required|numeric|min:0';
            $rules['kenakan_ppn'] = 'boolean';
            $rules['keterangan'] = 'nullable|string';
        }

        $request->validate($rules);

        try {
            // Siapkan data untuk Pelanggan
            $pelangganData = $request->only([
                'member_card', 'tipe', 'pop_id', 'alamat', 'kode_pos', 'kabupaten',
                'kota', 'wilayah', 'no_hp', 'nama_kontak', 'tipe_identitas',
                'nomor_identitas', 'nama_lengkap', 'tanggal_lahir', 'jenis_kelamin',
                'pekerjaan', 'nama_perusahaan', 'jenis_usaha', 'account_manager',
                'telepon_perusahaan', 'fax', 'email', 'npwp'
            ]);
            $pelangganData['reseller'] = $request->has('reseller');

            // Update data pelanggan
            $pelanggan->update($pelangganData);

            // Update atau buat data layanan
            if ($request->has('jenis_layanan')) {
                $layananData = $request->only([
                    'homepass', 'jenis_layanan', 'mulai_kontrak', 'selesai_kontrak',
                    'email_alternatif_1', 'email_alternatif_2', 'jumlah_tv_kabel'
                ]);
                $layananData['perjanjian_trial'] = $request->has('perjanjian_trial');
                $layananData['pembelian_modem'] = $request->has('pembelian_modem');

                // Update jika sudah ada, buat baru jika belum ada
                $pelanggan->layanan()->updateOrCreate(['pelanggan_id' => $pelanggan->id], $layananData);
            } else {
                // Jika data layanan tidak dikirim, dan sebelumnya ada, hapus
                $pelanggan->layanan()->delete();
            }

            // Update atau buat data penagihan
            if ($request->has('kontak_penagihan')) {
                $penagihanData = $request->only([
                    'kontak_penagihan', 'alamat_penagihan', 'kode_pos_penagihan',
                    'kabupaten_penagihan', 'kota_penagihan', 'no_hp_penagihan',
                    'telepon_penagihan', 'fax_penagihan', 'email_penagihan',
                    'cara_pembayaran', 'waktu_pembayaran', 'invoice_instalasi',
                    'invoice_reguler', 'mata_uang', 'biaya_reguler', 'keterangan'
                ]);
                $penagihanData['kenakan_ppn'] = $request->has('kenakan_ppn');

                // Update jika sudah ada, buat baru jika belum ada
                $pelanggan->penagihan()->updateOrCreate(['pelanggan_id' => $pelanggan->id], $penagihanData);
            } else {
                // Jika data penagihan tidak dikirim, dan sebelumnya ada, hapus
                $pelanggan->penagihan()->delete();
            }

            return redirect()->back()->with('success', 'Data pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui data pelanggan: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data pelanggan.
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->delete(); // Otomatis menghapus layanan dan penagihan berkat onDelete('cascade') di migrasi

            return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data pelanggan: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function show($id)
{
    // Eager load semua relasi yang mungkin diperlukan di halaman detail
    $pelanggan = Pelanggan::with(['layanan', 'penagihan', 'pop'])->findOrFail($id);
    return view('backend.pages.pelanggan.show', compact('pelanggan'));
}
}