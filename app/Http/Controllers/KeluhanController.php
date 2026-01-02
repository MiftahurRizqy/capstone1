<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\LayananInduk; // Perbaikan: Menggunakan model LayananInduk
use App\Models\Pelanggan;
use Illuminate\Validation\ValidationException;
use App\Models\Spk;
use Illuminate\Support\Str; 


class KeluhanController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:keluhan.view')->only('index');
        $this->middleware('can:keluhan.create')->only(['create', 'store']);
        $this->middleware('can:keluhan.edit')->only(['edit', 'update']);
        $this->middleware('can:keluhan.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Keluhan::with(['pelanggan', 'layananInduk'])->oldest();

        // Filter pencarian umum
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('keluhan1', 'like', "%{$search}%")
                  ->orWhere('keluhan2', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($subq) use ($search) {
                      $subq->where('nama_lengkap', 'like', "%{$search}%")
                           ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                           ->orWhere('nomor_pelanggan', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan prioritas
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $keluhan = $query->paginate(10)->appends($request->query());
        $layananInduks = LayananInduk::all();
        $pelanggan = Pelanggan::all();

        return view('backend.pages.keluhan.index', compact('keluhan', 'layananInduks', 'pelanggan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layananInduks = LayananInduk::all(); // Perbaikan: Mengubah nama variabel
        $pelanggan = Pelanggan::all();
        return view('backend.pages.keluhan.create', compact('layananInduks', 'pelanggan'));
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'layanan_induk_id' => 'required|exists:layanan_induk,id', // Perbaikan: Menggunakan kolom layanan_induk_id
                'pelanggan_id' => 'required|exists:pelanggan,id',
                'tujuan' => 'required',
                'prioritas' => 'required',
                'keluhan1' => 'required',
                'keluhan2' => 'nullable',
                'jenis_spk' => 'required|in:Tidak,SPK OSP,SPK VOIP,SPK TS',
                'via' => 'required',
                'deskripsi' => 'required',
                'penyelesaian' => 'nullable',
                'disampaikan_oleh' => 'required',
                'sumber' => 'required',
                'tanggal_input' => 'required|date',
            ]);
            // 1. Buat data keluhan
            $keluhan = Keluhan::create($validated);

            // 2. Periksa apakah SPK perlu dibuat
            if ($keluhan->jenis_spk !== 'Tidak') {
                $this->createSpkFromKeluhan($keluhan);
                $message = 'Data keluhan dan SPK berhasil dibuat.';
            } else {
                $message = 'Data keluhan berhasil ditambahkan.';
            }

            return redirect()->route('admin.keluhan.index')->with('success', $message);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal_open', 'add_keluhan_error');
        }
    }

    /**
     * Metode untuk membuat SPK secara otomatis.
     */
    protected function createSpkFromKeluhan(Keluhan $keluhan)
    {
        $keluhan->load('pelanggan');
        $pelanggan = $keluhan->pelanggan;

        // Logika untuk membuat nomor SPK otomatis
        $lastSpk = Spk::orderByDesc('nomor_spk')->first();
        $lastNumber = $lastSpk ? intval(substr($lastSpk->nomor_spk, 3, 4)) : 0;
        $newNumber = Str::padLeft($lastNumber + 1, 4, '0');
        
        // PERBAIKAN: Mengambil jenis SPK tanpa awalan 'SPK '
        $jenisSpkSingkat = Str::after($keluhan->jenis_spk, 'SPK ');
        
        $bulanTahun = now()->format('m/y');
        $nomor_spk = "SPK{$newNumber}/{$jenisSpkSingkat}/{$bulanTahun}";

        // Tentukan tipe SPK berdasarkan jenis SPK dari keluhan
        $tipeSpk = '';
        if ($jenisSpkSingkat === 'OSP') {
            $tipeSpk = 'instalasi';
        } elseif ($jenisSpkSingkat === 'VOIP') {
            $tipeSpk = 'migrasi';
        } elseif ($jenisSpkSingkat === 'TS') {
            $tipeSpk = 'survey';
        }

        Spk::create([
            'nomor_spk' => $nomor_spk,
            'keluhan_id' => $keluhan->id_keluhan,
            'pelanggan_id' => $keluhan->pelanggan_id,
            'nomor_pelanggan' => $pelanggan->nomor_pelanggan,
            'nama_lengkap' => $pelanggan->nama_lengkap,
            'nama_perusahaan' => $pelanggan->nama_perusahaan,
            'layanan_induk_id' => $keluhan->layanan_induk_id,
            'pop_id' => $pelanggan->pop_id,
            'alamat' => $pelanggan->alamat,
            'keterangan' => $keluhan->deskripsi, // Mengambil dari deskripsi keluhan
            'tipe' => $tipeSpk, // Menggunakan tipe SPK yang ditentukan
            'status' => 'dijadwalkan', // Status awal SPK
            'pelaksana_1' => '', // Bisa diisi nanti
            'rencana_pengerjaan' => now(),
            'user_id' => auth()->id(), // Mengambil user yang sedang login
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Keluhan $keluhan)
    {
        $keluhan->load(['pelanggan', 'layananInduk']);
        return view('backend.pages.keluhan.show', compact('keluhan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keluhan $keluhan)
    {
        $layananInduks = LayananInduk::all(); // Perbaikan: Mengubah nama variabel
        $pelanggan = Pelanggan::all();
        return view('backend.pages.keluhan.edit', compact('keluhan', 'layananInduks', 'pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Keluhan $keluhan)
    {
        $validated = $request->validate([
            'layanan_induk_id' => 'required|exists:layanan_induk,id', // Perbaikan: Menggunakan kolom layanan_induk_id
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'tujuan' => 'required',
            'prioritas' => 'required',
            'keluhan1' => 'required',
            'keluhan2' => 'nullable',
            'jenis_spk' => 'required|in:Tidak,SPK OSP,SPK VOIP,SPK TS',
            'via' => 'required',
            'deskripsi' => 'required',
            'penyelesaian' => 'nullable',
            'disampaikan_oleh' => 'required',
            'sumber' => 'required',
            'tanggal_input' => 'required|date',
        ]);

        $keluhan->update($validated);
        return redirect()->route('admin.keluhan.index')->with('success', 'Data keluhan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keluhan $keluhan)
    {
        $keluhan->delete();
        return redirect()->route('admin.keluhan.index')->with('success', 'Data keluhan berhasil dihapus');
    }
}