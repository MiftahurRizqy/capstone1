<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Layanan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:invoice.view')->only(['index', 'show', 'printInvoice']);
        $this->middleware('can:invoice.create')->only(['create', 'store', 'getLayananByPelanggan', 'searchPelanggan']);
        $this->middleware('can:invoice.edit')->only(['edit', 'update']);
        $this->middleware('can:invoice.delete')->only('destroy');
    }
    /**
     * Menampilkan daftar semua invoice dan data pendukung untuk modal.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['pelanggan', 'layanan.layananEntry'])->latest();

        // Menambahkan filter pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nomor_invoice', 'like', "%{$searchTerm}%")
                    ->orWhereHas('pelanggan', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('nama_lengkap', 'like', "%{$searchTerm}%")
                            ->orWhere('nomor_pelanggan', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $invoices = $query->paginate(10); // Menggunakan paginasi
        $pelanggan = Pelanggan::all();
        $layanan = collect(); // Dropdown akan diisi dinamis

        return view('backend.pages.invoice.index', compact('invoices', 'pelanggan', 'layanan'));
    }

    /**
     * Menyimpan invoice baru ke database.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'pelanggan_id'      => 'required|exists:pelanggan,id',
            'layanan_id'        => 'required|exists:layanan,id',
            'tipe'              => 'required|in:Instalasi,Reguler,Deposit,Droping,lain-lain',
            'jatuh_tempo'       => 'required|date',
            'total_biaya'       => 'nullable|numeric|min:0',
            'status'            => 'required|in:belum bayar,lunas',
            'metode_pembayaran' => 'nullable|string|max:255',
            'keterangan'        => 'nullable|string',
        ];
        // Laravel secara otomatis akan mengalihkan kembali dengan pesan error jika validasi gagal
        $request->validate($rules);

        DB::beginTransaction();
        try {
            $currentMonthYear = now()->format('m/y');
            $latestInvoice = Invoice::where('nomor_invoice', 'like', "INV-%/{$currentMonthYear}")->latest()->first();
            $newNumber = $latestInvoice ? ((int) substr($latestInvoice->nomor_invoice, 4, 3)) + 1 : 1;
            $nomor_invoice = 'INV-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '/' . $currentMonthYear;

            $invoiceData = $request->all();
            $invoiceData['nomor_invoice'] = $nomor_invoice;

            Invoice::create($invoiceData);
            DB::commit();

            return redirect()->route('admin.invoice.index')->with('success', 'Invoice berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat invoice: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mengambil daftar layanan berdasarkan ID pelanggan.
     * @param  int  $pelangganId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLayananByPelanggan(int $pelangganId)
    {
        try {
            $layanan = Layanan::where('pelanggan_id', $pelangganId)
                ->with('layananEntry')
                ->get(['id', 'layanan_entry_id']);
            return response()->json($layanan);
        } catch (\Exception $e) {
            Log::error('Error fetching layanan for pelanggan ID ' . $pelangganId . ': ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch services.'], 500);
        }
    }

    /**
     * Mencari pelanggan berdasarkan query.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPelanggan(Request $request)
    {
        $query = $request->get('q');
        $pelanggan = Pelanggan::where('nama_lengkap', 'like', "%{$query}%")
            ->orWhere('nama_perusahaan', 'like', "%{$query}%")
            ->orWhere('nomor_pelanggan', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        $results = $pelanggan->map(function ($p) {
            return [
                'id' => $p->id,
                'text' => ($p->nama_lengkap ?? $p->nama_perusahaan) . " ({$p->nomor_pelanggan})",
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Menampilkan detail invoice.
     * @param  \App\Models\Invoice $invoice
     * @return \Illuminate\View\View
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['pelanggan', 'layanan.layananEntry']);
        return view('backend.pages.invoice.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $pelanggan = Pelanggan::all();

        // PERBAIKAN: Hanya memuat layanan yang terkait dengan pelanggan dari invoice ini.
        $layanan = Layanan::where('pelanggan_id', $invoice->pelanggan_id)->with('layananEntry')->get();

        $invoice->load(['pelanggan', 'layanan']);
        return view('backend.pages.invoice.edit', compact('invoice', 'pelanggan', 'layanan'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $rules = [
            'nomor_invoice'     => 'required|unique:invoice,nomor_invoice,' . $invoice->id,
            'pelanggan_id'      => 'required|exists:pelanggan,id',
            'layanan_id'        => 'required|exists:layanan,id',
            'tipe'              => 'required|in:Instalasi,Reguler,Deposit,Droping,lain-lain',
            'jatuh_tempo'       => 'required|date',
            'tanggal_bayar'     => 'nullable|date',
            'total_biaya'       => 'nullable|numeric|min:0',
            'status'            => 'required|in:belum bayar,lunas',
            'metode_pembayaran' => 'nullable|string|max:255',
            'keterangan'        => 'nullable|string',
        ];
        $request->validate($rules);

        DB::beginTransaction();
        try {
            $invoice->update($request->all());
            DB::commit();

            return redirect()->route('admin.invoice.index')->with('success', 'Invoice berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui invoice: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal memperbarui invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return redirect()->route('admin.invoice.index')->with('success', 'Invoice berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus invoice: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal menghapus invoice: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan invoice untuk dicetak.
     * @param  \App\Models\Invoice $invoice
     * @return \Illuminate\View\View
     */
    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['pelanggan.penagihan', 'layanan.layananEntry']);
        return view('backend.pages.invoice.invoice_print', compact('invoice'));
    }
}
