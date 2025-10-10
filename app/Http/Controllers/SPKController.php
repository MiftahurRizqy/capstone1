<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\Spk;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpkController extends Controller
{
    /**
     * Tampilkan daftar SPK.
     */
    public function index()
    {
        $spk = Spk::with(['keluhan.pelanggan', 'layananInduk'])->latest()->paginate(10);
        return view('backend.pages.spk.index', compact('spk'));
    }
    
    /**
     * Menampilkan detail SPK yang spesifik.
     */
    public function show(string $spk)
    {
        $nomor_spk = urldecode($spk);
        $spk = Spk::where('nomor_spk', $nomor_spk)->with(['keluhan.pelanggan', 'layananInduk', 'pop'])->firstOrFail();
        return view('backend.pages.spk.show', compact('spk'));
    }

    /**
     * Menampilkan form untuk mengedit SPK yang spesifik.
     */
    public function edit(string $spk)
    {
        $nomor_spk = urldecode($spk);
        $spk = Spk::where('nomor_spk', $nomor_spk)->with(['keluhan.pelanggan', 'layananInduk', 'pop'])->firstOrFail();
        
        $pelaksanaOptions = User::all();
        // Menambahkan opsi status untuk form edit
        $statusOptions = ['dijadwalkan', 'dalam_pengerjaan', 'reschedule', 'selesai_sebagian', 'selesai'];
        return view('backend.pages.spk.edit', compact('spk', 'pelaksanaOptions', 'statusOptions'));
    }

    /**
     * Memperbarui SPK yang spesifik di storage.
     */
    public function update(Request $request, string $spk)
    {
        $nomor_spk = urldecode($spk);
        $spk = Spk::where('nomor_spk', $nomor_spk)->firstOrFail();
        
        try {
            // Memperbarui validasi untuk kolom 'status'
            $validated = $request->validate([
                'tipe' => 'required|in:instalasi,migrasi,survey,dismantle,lain-lain',
                'status' => 'required|in:dijadwalkan,dalam_pengerjaan,reschedule,selesai_sebagian,selesai',
                'kelengkapan_kerja' => 'nullable|string',
                'keterangan' => 'required|string',
                'rencana_pengerjaan' => 'required|date',
                'pelaksana_1' => 'required|string',
                'pelaksana_2' => 'nullable|string',
                'koordinator' => 'nullable|string',
            ]);

            // Menambahkan logika untuk 'selesai_at' jika status berubah menjadi 'selesai'
            if ($request->status === 'selesai') {
                $validated['selesai_at'] = now();
            } else {
                $validated['selesai_at'] = null;
            }
            
            $spk->update($validated);
            
            return redirect()->route('admin.spk.index')->with('success', 'SPK berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }
    }

    /**
     * Menghapus SPK dari storage.
     */
    public function destroy(string $spk)
    {
        $nomor_spk = urldecode($spk);
        $spk = Spk::where('nomor_spk', $nomor_spk)->firstOrFail();
        
        $spk->delete();
        return redirect()->route('admin.spk.index')->with('success', 'SPK berhasil dihapus.');
    }
public function printSpk(string $spk) // Ganti $nomor_spk menjadi $spk
{
    // Menggunakan $spk sesuai nama di route, lalu di-decode
    $nomor_spk = urldecode($spk); 
    
    $spk = Spk::where('nomor_spk', $nomor_spk)
             ->with(['keluhan.pelanggan', 'layananInduk', 'pop', 'user'])
             ->firstOrFail();
             
    return view('backend.pages.spk.spk_print', compact('spk'));
}
}
