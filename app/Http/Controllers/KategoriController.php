<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriPelanggan;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    // --- DAFTAR FIELD YANG DIKELOLA ---
    
    // Field spesifik untuk pelanggan Personal
    private const PERSONAL_FIELDS = [
        'nama_lengkap' => 'Nama Lengkap', 
        'tanggal_lahir' => 'Tanggal Lahir', 
        'jenis_kelamin' => 'Jenis Kelamin', 
        'pekerjaan' => 'Pekerjaan',
    ];

    // Field spesifik untuk pelanggan Perusahaan
    private const PERUSAHAAN_FIELDS = [
        'nama_perusahaan' => 'Nama Perusahaan', 
        'jenis_usaha' => 'Jenis Usaha', 
        'account_manager' => 'Account Manager', 
        'telepon_perusahaan' => 'Telepon Perusahaan', 
        'fax' => 'Fax', 
        'npwp' => 'NPWP',
    ];

    /**
     * Menyediakan daftar field untuk digunakan di View.
     */
    public static function getFieldsForView()
    {
        return [
            'personal' => self::PERSONAL_FIELDS,
            'perusahaan' => self::PERUSAHAAN_FIELDS,
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = KategoriPelanggan::all();
        return response()->json($kategori);
    }
    
    public function store(Request $request)
    {
        // Ambil daftar kunci field yang valid
        $validPersonalFields = array_keys(self::PERSONAL_FIELDS);
        $validPerusahaanFields = array_keys(self::PERUSAHAAN_FIELDS);

        // Validasi
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_pelanggan,nama',
            // Gunakan implisit Rule untuk array input
            'personal_fields.*' => 'nullable|string|in:' . implode(',', $validPersonalFields),
            'perusahaan_fields.*' => 'nullable|string|in:' . implode(',', $validPerusahaanFields),
        ]);

        try {
            $data = $request->only('nama');
            
            // Simpan array field terpilih (akan diubah jadi JSON string otomatis jika Model tidak pakai $casts)
            // Karena Model sudah pakai $casts, Laravel akan mengubah array ini jadi JSON di DB.
            $data['personal_fields'] = $request->input('personal_fields', []);
            $data['perusahaan_fields'] = $request->input('perusahaan_fields', []);


            KategoriPelanggan::create($data);

            // Redirect kembali dengan session 'form_target' untuk logic reload di Blade
            return redirect()->back()
                ->with('success', 'Kategori pelanggan berhasil ditambahkan.')
                ->with('form_target', 'kategori');
            
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan kategori pelanggan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput()
                ->with('form_target', 'kategori');
        }
    }

    /**
     * Menghapus kategori pelanggan.
     * @param  \App\Models\KategoriPelanggan  $kategori
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(KategoriPelanggan $kategori)
    {
        try {
            // Karena migrasi menggunakan onDelete('restrict'), 
            // Eloquent akan mencegah penghapusan jika ada pelanggan terkait.
            $kategori->delete();

            return redirect()->back()->with('success', 'Kategori pelanggan berhasil dihapus.');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Foreign Key Constraint violation (asumsi: error 23000)
            if ($e->getCode() == '23000') { 
                return redirect()->back()->with('error', 'Gagal menghapus kategori: Terdapat pelanggan yang menggunakan kategori ini.')->withInput();
            }
            
            Log::error('Gagal menghapus kategori pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Gagal menghapus kategori pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

}
