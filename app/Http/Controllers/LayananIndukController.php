<?php

namespace App\Http\Controllers;

use App\Models\LayananInduk;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LayananIndukController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:layanan.view')->only('index');
        $this->middleware('can:layanan.create')->only(['create', 'store']);
        $this->middleware('can:layanan.edit')->only(['edit', 'update']);
        $this->middleware('can:layanan.delete')->only('destroy');
    }

    /**
     * Menampilkan daftar Layanan Induk.
     */
    public function index(Request $request)
    {
        $query = LayananInduk::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_layanan_induk', 'like', '%' . $search . '%');
        }

        $layananInduks = $query->orderBy('id', 'asc')->paginate(10);
        return view('backend.pages.layanan.induk.index', compact('layananInduks'));
    }

    /**
     * Menyimpan Layanan Induk baru ke storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_layanan_induk' => 'required|string|max:255|unique:layanan_induk,nama_layanan_induk',
            ], [
                'nama_layanan_induk.required' => 'Nama Layanan Induk wajib diisi.',
                'nama_layanan_induk.unique' => 'Nama Layanan Induk sudah ada.',
            ]);

            LayananInduk::create($validated);
            return redirect()->route('admin.layanan.induk.index')->with('success', 'Data Layanan Induk berhasil ditambahkan');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal_open', 'add_layanan_induk_error');
        }
    }

    /**
     * Menampilkan form untuk mengedit Layanan Induk yang spesifik.
     */
    public function edit(LayananInduk $layananInduk)
    {
        return view('backend.pages.layanan.induk.edit', compact('layananInduk'));
    }

    /**
     * Memperbarui Layanan Induk yang spesifik di storage.
     */
    public function update(Request $request, LayananInduk $layananInduk)
    {
        $validated = $request->validate([
            'nama_layanan_induk' => 'required|string|max:255|unique:layanan_induk,nama_layanan_induk,' . $layananInduk->id,
        ], [
            'nama_layanan_induk.required' => 'Nama Layanan Induk wajib diisi.',
            'nama_layanan_induk.unique' => 'Nama Layanan Induk sudah ada.',
        ]);

        $layananInduk->update($validated);
        // PERBAIKAN: Menggunakan nama route yang benar
        return redirect()->route('admin.layanan.induk.index')->with('success', 'Data Layanan Induk berhasil diperbarui');
    }

    /**
     * Menghapus Layanan Induk dari storage.
     */
    public function destroy(LayananInduk $layananInduk)
    {
        $layananInduk->delete();
        // PERBAIKAN: Menggunakan nama route yang benar
        return redirect()->route('admin.layanan.induk.index')->with('success', 'Data Layanan Induk berhasil dihapus');
    }
}