<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Penagihan;
use App\Models\LayananEntry;
use App\Models\Pop;
use App\Models\KategoriPelanggan; // Wajib di-import
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:pelanggan.view')->only('index');
        $this->middleware('can:pelanggan.create')->only(['create', 'store']);
        $this->middleware('can:pelanggan.edit')->only(['edit', 'update']);
        $this->middleware('can:pelanggan.delete')->only('destroy');
    }
    /**
     * Menampilkan daftar semua pelanggan dan menyediakan data untuk form.
     * Menggantikan metode personal() dan perusahaan().
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil semua data master yang diperlukan
        $pops = Pop::all();
        $layananEntries = LayananEntry::all();
        // Ambil semua kategori yang tersedia (default + kategori baru)
        $kategoriPelanggan = KategoriPelanggan::all(); 
        
        // Dapatkan daftar field yang tersedia dari KategoriController
        $availableFields = KategoriController::getFieldsForView();

        // **KUNCI PERBAIKAN STABILITAS ALPINE.JS:** Olah data di Controller
        $kategoriDataForAlpine = $kategoriPelanggan->keyBy('id')->map(function($kategori) {
            // Karena Model menggunakan $casts, $kategori->personal_fields sudah berupa array
            return [
                'nama' => $kategori->nama,
                'personal_fields' => $kategori->personal_fields ?? [], 
                'perusahaan_fields' => $kategori->perusahaan_fields ?? [],
            ];
        });


        $query = Pelanggan::with(['pop', 'layanan.layananEntry', 'kategori'])
            ->latest();

        // Filter berdasarkan kategori_pelanggan_id (dropdown)
        if ($request->filled('kategori_pelanggan_id')) {
            $query->where('kategori_pelanggan_id', $request->kategori_pelanggan_id);
        }

        // Filter pencarian umum (nomor pelanggan, member card, nama, no hp)
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pelanggan', 'like', "%{$search}%")
                  ->orWhere('member_card', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('nama_kontak', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $pelanggan = $query->paginate(10)->appends($request->query()); 
        
        return view('backend.pages.pelanggan.index', compact(
            'pelanggan', 
            'pops', 
            'layananEntries', 
            'kategoriPelanggan',
            'availableFields', 
            'kategoriDataForAlpine' // KIRIM DATA YANG SUDAH DIOLAH
        ));
    }

    // ... (Fungsi store - Perhatikan VALIDASI. Di sini kita tetap validasi berdasarkan nama kategori lama) ...
    public function store(Request $request)
    {
        $kategori = KategoriPelanggan::find($request->kategori_pelanggan_id);

        if (!$kategori) {
             return redirect()->back()->with('error', 'Kategori pelanggan tidak valid.')->withInput();
        }

        // Aturan validasi dasar
        $rules = [
            'member_card' => 'required|string|max:255|unique:pelanggan,member_card',
            'kategori_pelanggan_id' => 'required|exists:kategori_pelanggan,id', 
            // ... (lanjutan validasi umum) ...
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

        // VALIDASI BERDASARKAN NAMA KATEGORI LAMA (untuk kompatibilitas)
        // Jika Anda ingin ini lebih dinamis, Anda harus mengiterasi $kategori->personal_fields
        if (strtolower($kategori->nama) === 'personal') {
            $rules['nama_lengkap'] = 'required|string|max:255';
            $rules['tanggal_lahir'] = 'nullable|date';
            $rules['jenis_kelamin'] = 'nullable|in:L,P';
            $rules['pekerjaan'] = 'nullable|string|max:255';
        }

        if (strtolower($kategori->nama) === 'perusahaan') {
            $rules['nama_perusahaan'] = 'required|string|max:255';
            $rules['jenis_usaha'] = 'nullable|string|max:255';
            $rules['account_manager'] = 'nullable|string|max:255';
            $rules['telepon_perusahaan'] = 'nullable|string|max:20';
            $rules['fax'] = 'nullable|string|max:20';
            $rules['email'] = 'nullable|email|max:255';
            $rules['npwp'] = 'nullable|string|max:255';
        }

        // ... (lanjutan validasi layanan dan penagihan) ...
        if ($request->filled('layanan_entry_id')) {
             $rules['homepass'] = 'nullable|string|max:255';
             $rules['layanan_entry_id'] = 'required|exists:layanan_entry,id';
             $rules['mulai_kontrak'] = 'required|date';
             $rules['selesai_kontrak'] = 'required|date|after_or_equal:mulai_kontrak';
             $rules['perjanjian_trial'] = 'boolean';
             $rules['pembelian_modem'] = 'boolean';
             $rules['jumlah_tv_kabel'] = 'nullable|integer|min:0';
             $rules['email_alternatif_1'] = 'nullable|email|max:255';
             $rules['email_alternatif_2'] = 'nullable|email|max:255';
         }

        if ($request->filled('kontak_penagihan')) {
            $rules['kontak_penagihan'] = 'required|string|max:255';
            // ... (lanjutan validasi penagihan) ...
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

        // ... (Logika penyimpanan DB::beginTransaction, create pelanggan, layanan, penagihan) ...
        
        DB::beginTransaction();

        try {
            // Logika penomoran pelanggan otomatis
            $latestPelanggan = Pelanggan::latest('id')->first();
            $startNumber = 1770;
            $newNumber = $latestPelanggan ? ((int) substr($latestPelanggan->nomor_pelanggan, 3)) + 1 : $startNumber;
            $nomor_pelanggan = 'CMN' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Siapkan data untuk Pelanggan
            $pelangganData = $request->only([
                'member_card', 'kategori_pelanggan_id', 'pop_id', 'alamat', 'kode_pos', 'kabupaten', 
                'kota', 'wilayah', 'no_hp', 'nama_kontak', 'tipe_identitas',
                'nomor_identitas', 'nama_lengkap', 'tanggal_lahir', 'jenis_kelamin',
                'pekerjaan', 'nama_perusahaan', 'jenis_usaha', 'account_manager',
                'telepon_perusahaan', 'fax', 'email', 'npwp'
            ]);

            $pelangganData['nomor_pelanggan'] = $nomor_pelanggan;
            $pelangganData['reseller'] = $request->has('reseller');

            // Simpan data pelanggan
            $pelanggan = Pelanggan::create($pelangganData);

            // Simpan data layanan jika ada
            if ($request->filled('layanan_entry_id')) {
                $layananData = $request->only([
                    'homepass', 'layanan_entry_id', 'mulai_kontrak', 'selesai_kontrak',
                    'email_alternatif_1', 'email_alternatif_2', 'jumlah_tv_kabel'
                ]);
                $layananData['pelanggan_id'] = $pelanggan->id;
                $layananData['perjanjian_trial'] = $request->has('perjanjian_trial');
                $layananData['pembelian_modem'] = $request->has('pembelian_modem');

                Layanan::create($layananData);
            }

            // Simpan data penagihan jika ada
            if ($request->filled('kontak_penagihan')) {
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
            
            DB::commit(); // Commit transaksi jika semua berhasil

            return redirect()->back()->with('success', 'Data pelanggan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            Log::error('Gagal menyimpan data pelanggan: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
    
    // ... (Fungsi edit - Ulangi logika pengolahan data kategori) ...
    public function edit($id)
    {
        $pelanggan = Pelanggan::with(['layanan.layananEntry', 'penagihan', 'pop', 'kategori'])->findOrFail($id);
        $pops = Pop::all();
        $layananEntries = LayananEntry::all();
        $kategoriPelanggan = KategoriPelanggan::all(); 
        $availableFields = KategoriController::getFieldsForView();

        // Olah data untuk Alpine
        $kategoriDataForAlpine = $kategoriPelanggan->keyBy('id')->map(function($kategori) {
            return [
                'nama' => $kategori->nama,
                'personal_fields' => $kategori->personal_fields ?? [], 
                'perusahaan_fields' => $kategori->perusahaan_fields ?? [],
            ];
        });
        
        return view('backend.pages.pelanggan.edit', compact(
            'pelanggan', 
            'pops', 
            'layananEntries', 
            'kategoriPelanggan',
            'availableFields',
            'kategoriDataForAlpine'
        ));
    }

    /**
     * Memperbarui data pelanggan yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        // Ambil nama kategori yang dipilih (misal: 'Personal' atau 'Perusahaan')
        $kategori = KategoriPelanggan::find($request->kategori_pelanggan_id);

        if (!$kategori) {
             return redirect()->back()->with('error', 'Kategori pelanggan tidak valid.')->withInput();
        }

        // Aturan validasi dasar untuk update
        $rules = [
            'member_card' => 'required|string|max:255|unique:pelanggan,member_card,' . $id,
            'kategori_pelanggan_id' => 'required|exists:kategori_pelanggan,id', // PERUBAHAN
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

        // Aturan validasi spesifik berdasarkan NAMA kategori
        if (strtolower($kategori->nama) === 'personal') {
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

        if (strtolower($kategori->nama) === 'perusahaan') {
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
        
        // ... (Validasi Layanan dan Penagihan tetap sama)
        if ($request->filled('layanan_entry_id')) {
             $rules['homepass'] = 'nullable|string|max:255';
             $rules['layanan_entry_id'] = 'required|exists:layanan_entry,id';
             $rules['mulai_kontrak'] = 'required|date';
             $rules['selesai_kontrak'] = 'required|date|after_or_equal:mulai_kontrak';
             $rules['perjanjian_trial'] = 'boolean';
             $rules['pembelian_modem'] = 'boolean';
             $rules['jumlah_tv_kabel'] = 'nullable|integer|min:0';
             $rules['email_alternatif_1'] = 'nullable|email|max:255';
             $rules['email_alternatif_2'] = 'nullable|email|max:255';
         }

        if ($request->filled('kontak_penagihan')) {
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
        
        DB::beginTransaction();

        try {
            $pelangganData = $request->only([
                'member_card', 'kategori_pelanggan_id', 'pop_id', 'alamat', 'kode_pos', 'kabupaten', // PERUBAHAN
                'kota', 'wilayah', 'no_hp', 'nama_kontak', 'tipe_identitas',
                'nomor_identitas', 'nama_lengkap', 'tanggal_lahir', 'jenis_kelamin',
                'pekerjaan', 'nama_perusahaan', 'jenis_usaha', 'account_manager',
                'telepon_perusahaan', 'fax', 'email', 'npwp', 'reseller'
            ]);
            
            $pelangganData['reseller'] = $request->has('reseller');

            // Update data pelanggan
            $pelanggan->update($pelangganData);

            // Update atau buat data layanan
            if ($request->filled('layanan_entry_id')) {
                $layananData = $request->only([
                    'homepass', 'layanan_entry_id', 'mulai_kontrak', 'selesai_kontrak',
                    'email_alternatif_1', 'email_alternatif_2', 'jumlah_tv_kabel'
                ]);
                $layananData['perjanjian_trial'] = $request->has('perjanjian_trial');
                $layananData['pembelian_modem'] = $request->has('pembelian_modem');

                $pelanggan->layanan()->updateOrCreate(['pelanggan_id' => $pelanggan->id], $layananData);
            } else {
                $pelanggan->layanan()->delete();
            }

            // Update atau buat data penagihan
            if ($request->filled('kontak_penagihan')) {
                $penagihanData = $request->only([
                    'kontak_penagihan', 'alamat_penagihan', 'kode_pos_penagihan',
                    'kabupaten_penagihan', 'kota_penagihan', 'no_hp_penagihan',
                    'telepon_penagihan', 'fax_penagihan', 'email_penagihan',
                    'cara_pembayaran', 'waktu_pembayaran', 'invoice_instalasi',
                    'invoice_reguler', 'mata_uang', 'biaya_reguler', 'keterangan'
                ]);
                $penagihanData['kenakan_ppn'] = $request->has('kenakan_ppn');

                $pelanggan->penagihan()->updateOrCreate(['pelanggan_id' => $pelanggan->id], $penagihanData);
            } else {
                $pelanggan->penagihan()->delete();
            }
            
            DB::commit();

            return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data pelanggan: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data pelanggan.
     */
    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->delete();

            return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data pelanggan: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function show(Pelanggan $pelanggan)
    {
        // Muat relasi yang diperlukan untuk view
        $pelanggan->load(['layanan.layananEntry', 'penagihan', 'pop', 'kategori']);

        return view('backend.pages.pelanggan.show', compact('pelanggan'));
    }
}
