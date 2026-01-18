<?php

namespace App\Http\Controllers;

use App\Models\LayananEntry;
use App\Models\LayananInduk;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LayananEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:layanan.view')->only(['index', 'show']);
        $this->middleware('can:layanan.create')->only(['create', 'store']);
        $this->middleware('can:layanan.edit')->only(['edit', 'update']);
        $this->middleware('can:layanan.delete')->only('destroy');
    }

    /**
     * Menampilkan daftar Layanan Entry.
     */
    public function index()
    {
        $layananEntries = LayananEntry::with('layananInduk')->latest()->paginate(10);
        $layananInduks = LayananInduk::all();

        return view('backend.pages.layanan.entry.index', compact('layananEntries', 'layananInduks'));
    }

    /**
     * Menampilkan halaman detail untuk Layanan Entry yang spesifik.
     */
    public function show(LayananEntry $layananEntry)
    {
        return view('backend.pages.layanan.entry.show', compact('layananEntry'));
    }

    /**
     * Menyimpan Layanan Entry baru ke storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|string|max:255|unique:layanan_entry,kode',
                'nama_paket' => 'required|string|max:255',
                'status' => 'required|in:aktif,tidak aktif',
                'tipe' => 'required|in:TV,Internet,Lain-Lain',
                'kelompok_layanan' => 'required|in:Layanan Dasar,Web Hosting,Colocation',
                'layanan_induk_id' => 'nullable|exists:layanan_induk,id',
                'spk_osp_instalasi' => 'nullable|boolean',
                'spk_isp_instalasi' => 'nullable|boolean',
                'spk_osp_aktif_kembali' => 'nullable|boolean',
                'spk_isp_aktif_kembali' => 'nullable|boolean',
                'tipe_layanan_spk' => 'nullable|in:TV,Internet HFC,Internet Wireless,Wi TV,TV-DTH',
                'konfigurasi_dhcp' => 'nullable|string',
                'utilisasi_bandwidth' => 'nullable|integer',
                'biaya_setup' => 'nullable|numeric|min:0',
                'biaya_reguler_1_bulan' => 'nullable|numeric|min:0',
                'biaya_reguler_3_bulan' => 'nullable|numeric|min:0',
                'bonus_reguler_3_bulan' => 'nullable|string',
                'biaya_reguler_6_bulan' => 'nullable|numeric|min:0',
                'bonus_reguler_6_bulan' => 'nullable|string',
                'biaya_reguler_12_bulan' => 'nullable|numeric|min:0',
                'bonus_reguler_12_bulan' => 'nullable|string',
                'koneksi_tv_kabel' => 'nullable|string',
                'kompensasi_diskoneksi' => 'nullable|string',
                'redaksional_invoice' => 'nullable|string',
                'redaksional_invoice_2' => 'nullable|string',
                'account_myob_1' => 'nullable|string',
                'account_myob_2' => 'nullable|string',
                'nama_milis' => 'nullable|string',
                'deskripsi' => 'nullable|string',
            ]);

            LayananEntry::create($validated);
            // Perbaikan: Menggunakan nama route yang benar
            return redirect()->route('admin.layanan.entry.index')->with('success', 'Layanan berhasil ditambahkan!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal_open', 'add_layanan_entry_error');
        }
    }

    /**
     * Menampilkan form untuk mengedit Layanan Entry.
     */
    public function edit(LayananEntry $layananEntry)
    {
        $layananInduks = LayananInduk::all();
        return view('backend.pages.layanan.entry.edit', compact('layananEntry', 'layananInduks'));
    }

    /**
     * Memperbarui Layanan Entry di storage.
     */
    public function update(Request $request, LayananEntry $layananEntry)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:255|unique:layanan_entry,kode,' . $layananEntry->id,
            'nama_paket' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak aktif',
            'tipe' => 'required|in:TV,Internet,Lain-Lain',
            'kelompok_layanan' => 'required|in:Layanan Dasar,Web Hosting,Colocation',
            'layanan_induk_id' => 'nullable|exists:layanan_induk,id',
            'spk_osp_instalasi' => 'nullable|boolean',
            'spk_isp_instalasi' => 'nullable|boolean',
            'spk_osp_aktif_kembali' => 'nullable|boolean',
            'spk_isp_aktif_kembali' => 'nullable|boolean',
            'tipe_layanan_spk' => 'nullable|in:TV,Internet HFC,Internet Wireless,Wi TV,TV-DTH',
            'konfigurasi_dhcp' => 'nullable|string',
            'utilisasi_bandwidth' => 'nullable|integer',
            'biaya_setup' => 'nullable|numeric|min:0',
            'biaya_reguler_1_bulan' => 'nullable|numeric|min:0',
            'biaya_reguler_3_bulan' => 'nullable|numeric|min:0',
            'bonus_reguler_3_bulan' => 'nullable|string',
            'biaya_reguler_6_bulan' => 'nullable|numeric|min:0',
            'bonus_reguler_6_bulan' => 'nullable|string',
            'biaya_reguler_12_bulan' => 'nullable|numeric|min:0',
            'bonus_reguler_12_bulan' => 'nullable|string',
            'koneksi_tv_kabel' => 'nullable|string',
            'kompensasi_diskoneksi' => 'nullable|string',
            'redaksional_invoice' => 'nullable|string',
            'redaksional_invoice_2' => 'nullable|string',
            'account_myob_1' => 'nullable|string',
            'account_myob_2' => 'nullable|string',
            'nama_milis' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);
        
        $layananEntry->update($validated);
        // Perbaikan: Menggunakan nama route yang benar
        return redirect()->route('admin.layanan.entry.index')->with('success', 'Layanan berhasil diperbarui!');
    }

    /**
     * Menghapus Layanan Entry dari storage.
     */
    public function destroy(LayananEntry $layananEntry)
    {
        $layananEntry->delete();
        // Perbaikan: Menggunakan nama route yang benar
        return redirect()->route('admin.layanan.entry.index')->with('success', 'Layanan berhasil dihapus!');
    }
}