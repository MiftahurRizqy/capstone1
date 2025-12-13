<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pop;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PopController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:jaringan.view')->only('index');
        $this->middleware('can:jaringan.create')->only(['create', 'store']);
        $this->middleware('can:jaringan.edit')->only(['edit', 'update']);
        $this->middleware('can:jaringan.delete')->only('destroy');
    }

    /**
     * Export daftar POP sebagai CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $filename = 'pop_' . now()->format('Ymd_His') . '.csv';

        $query = Pop::query()->latest();

        $columns = ['ID', 'Nama POP', 'Kabupaten/Kota', 'Daerah', 'RT/RW', 'Dibuat'];

        return response()->streamDownload(function () use ($query, $columns) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 agar kompatibel Excel
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $columns);

            $query->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->nama_pop,
                        $r->kabupaten_kota,
                        $r->daerah,
                        $r->rt_rw,
                        optional($r->created_at)?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    /**
     * Menampilkan daftar semua Point of Presence (POP).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pops = Pop::all();
        // PERUBAHAN: Mengarahkan ke view di lokasi baru
        return view('backend.pages.jaringan.pop.index', compact('pops'));
    }

    /**
     * Menyimpan data POP baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            'nama_pop' => 'required|string|max:255|unique:pop,nama_pop',
            'kabupaten_kota' => 'required|string|max:255',
            'daerah' => 'required|string|max:255',
            'rt_rw' => 'nullable|string|max:255',
        ]);

        try {
            Pop::create($request->all());
            // Menambahkan session flash untuk menandai modal harus terbuka jika ada error
            return redirect()->back()->with('success', 'Data POP berhasil ditambahkan!')->with('modal_open', 'add_pop');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan POP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data POP: ' . $e->getMessage())->withInput()->with('modal_open', 'add_pop');
        }
    }

    /**
     * Menampilkan form untuk mengedit POP tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $pop = Pop::findOrFail($id);
        // PERUBAHAN: Mengarahkan ke view di lokasi baru
        return view('backend.pages.jaringan.pop.edit', compact('pop'));
    }

    /**
     * Memperbarui data POP yang sudah ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $pop = Pop::findOrFail($id);

        $request->validate([
            'nama_pop' => 'required|string|max:255|unique:pop,nama_pop,' . $id,
            'kabupaten_kota' => 'required|string|max:255',
            'daerah' => 'required|string|max:255',
            'rt_rw' => 'nullable|string|max:255',
        ]);

        try {
            $pop->update($request->all());
            // PERUBAHAN: Mengarahkan kembali ke index POP di lokasi baru
            return redirect()->route('admin.jaringan.pop.index')->with('success', 'Data POP berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui POP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data POP: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus data POP dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $pop = Pop::findOrFail($id);
            $pop->delete();

            // PERUBAHAN: Mengarahkan kembali ke index POP di lokasi baru
            return redirect()->back()->with('success', 'Data POP berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus POP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data POP: ' . $e->getMessage());
        }
    }
}
