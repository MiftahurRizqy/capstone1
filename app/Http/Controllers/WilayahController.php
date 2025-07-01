<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WilayahController extends Controller
{
    private $apiBaseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api/';

    public function index()
    {
        $provinsi = [];
        try {
            $response = Http::get($this->apiBaseUrl . 'provinces.json');
            if ($response->successful()) {
                $provinsi = $response->json();
                Log::info('Provinces fetched successfully from API for index page.');
            } else {
                Log::error('Failed to retrieve provinces from API for index page: ' . $response->status() . ' Body: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('API connection error for provinces on index page: ' . $e->getMessage());
        }

        $bagian = Wilayah::bagian()->with('parent')->get();

        return view('backend.pages.jaringan.wilayah.index', compact('provinsi', 'bagian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'external_provinsi_id' => 'required|string',
            'external_kabupaten_id' => 'required|string',
            'external_kecamatan_id' => 'required|string',
            'external_kelurahan_id' => 'required|string',
            'nama_bagian' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $provinsiId = $request->input('external_provinsi_id');
        $kabupatenId = $request->input('external_kabupaten_id');
        $kecamatanId = $request->input('external_kecamatan_id');
        $kelurahanId = $request->input('external_kelurahan_id');

        $provinsiNama = null;
        $kabupatenNama = null;
        $kecamatanNama = null;
        $kelurahanNama = null;

        try {
            $provinsiNama = $this->getGeoNameById($provinsiId, 'provinsi');
            if (is_null($provinsiNama)) {
                throw new \Exception("Gagal mendapatkan nama provinsi dari API untuk ID: {$provinsiId}");
            }

            $kabupatenNama = $this->getGeoNameById($kabupatenId, 'kabupaten', $provinsiId);
            if (is_null($kabupatenNama)) {
                throw new \Exception("Gagal mendapatkan nama kabupaten dari API untuk ID: {$kabupatenId}");
            }

            $kabupatenIdForApiUrl = $kabupatenId;
            Log::debug("Kabupaten ID for API URL (for districts): " . $kabupatenIdForApiUrl);

            $kecamatanNama = $this->getGeoNameById($kecamatanId, 'kecamatan', $kabupatenIdForApiUrl);
            if (is_null($kecamatanNama)) {
                throw new \Exception("Gagal mendapatkan nama kecamatan dari API untuk ID: {$kecamatanId}");
            }

            $kecamatanIdForApiUrl = $kecamatanId;
            Log::debug("Kecamatan ID for API URL (for villages): " . $kecamatanIdForApiUrl);

            $kelurahanNama = $this->getGeoNameById($kelurahanId, 'kelurahan', $kecamatanIdForApiUrl);
            if (is_null($kelurahanNama)) {
                throw new \Exception("Gagal mendapatkan nama kelurahan dari API untuk ID: {$kelurahanId}");
            }

            $parentWilayah = Wilayah::where('tipe', 'kelurahan')
                                 ->where('external_provinsi_id', $provinsiId)
                                 ->where('external_kabupaten_id', $kabupatenId)
                                 ->where('external_kecamatan_id', $kecamatanId)
                                 ->where('external_kelurahan_id', $kelurahanId)
                                 ->first();

            Wilayah::create([
                'nama' => $request->nama_bagian,
                'tipe' => 'bagian',
                'deskripsi' => $request->deskripsi,
                'parent_id' => $parentWilayah ? $parentWilayah->id : null,
                'provinsi_nama' => $provinsiNama,
                'kabupaten_nama' => $kabupatenNama,
                'kecamatan_nama' => $kecamatanNama,
                'kelurahan_nama' => $kelurahanNama,
                'external_provinsi_id' => $provinsiId,
                'external_kabupaten_id' => $kabupatenId,
                'external_kecamatan_id' => $kecamatanId,
                'external_kelurahan_id' => $kelurahanId,
            ]);

            return redirect()->back()->with('success', 'Data Bagian berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Failed to add part: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Gagal menyimpan data Bagian: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $bagian = Wilayah::bagian()->findOrFail($id);
            $bagian->delete();

            return redirect()->back()->with('success', 'Data Bagian berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Gagal menghapus data Bagian: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal menghapus data Bagian: ' . $e->getMessage());
        }
    }

    public function getChildren(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|string',
            'child_type' => 'required|in:kabupaten,kecamatan,kelurahan',
        ]);

        $apiUrl = '';
        if ($request->child_type === 'kabupaten') {
            $apiUrl = $this->apiBaseUrl . 'regencies/' . $request->parent_id . '.json';
        } elseif ($request->child_type === 'kecamatan') {
            $apiUrl = $this->apiBaseUrl . 'districts/' . $request->parent_id . '.json';
        } elseif ($request->child_type === 'kelurahan') {
            $apiUrl = $this->apiBaseUrl . 'villages/' . $request->parent_id . '.json';
        }

        if (empty($apiUrl)) {
            Log::warning("URL API tidak dihasilkan untuk getChildren. Tipe anak: " . $request->child_type . ", Parent ID: " . $request->parent_id);
            return response()->json(['error' => 'Tipe anak atau ID parent tidak valid.'], 400);
        }

        Log::info("Mengambil anak dari API eksternal: " . $apiUrl);

        try {
            $response = Http::get($apiUrl);
            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data)) {
                    $filteredData = array_filter($data, fn($item) => isset($item['id']) && isset($item['name']));
                    return response()->json(array_values($filteredData));
                } else {
                    Log::error("Respons API untuk getChildren bukan array. URL: {$apiUrl}. Body: " . $response->body());
                    return response()->json(['error' => 'Kesalahan format respons API.'], 500);
                }
            } else {
                Log::error('Gagal mengambil data dari API eksternal untuk anak: ' . $response->status() . ' Body: ' . $response->body(), ['url' => $apiUrl]);
                return response()->json(['error' => 'Gagal mengambil data dari API eksternal: ' . $response->status(), 'details' => $response->body()], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Kesalahan koneksi API eksternal untuk anak: ' . $e->getMessage(), ['url' => $apiUrl, 'exception' => $e]);
            return response()->json(['error' => 'Kesalahan koneksi API eksternal: ' . $e->getMessage()], 500);
        }
    }

    private function getGeoNameById(string $id, string $type, ?string $parentApiIdForUrl = null): ?string
    {
        $apiUrl = '';

        if ($type === 'provinsi') {
            $apiUrl = $this->apiBaseUrl . 'provinces.json';
        } elseif ($type === 'kabupaten') {
            if (empty($parentApiIdForUrl)) {
                Log::warning("Parent API ID (provinsi) tidak diberikan untuk mencari kabupaten. ID: {$id}");
                return null;
            }
            $apiUrl = $this->apiBaseUrl . 'regencies/' . $parentApiIdForUrl . '.json';
        } elseif ($type === 'kecamatan') {
            if (empty($parentApiIdForUrl)) {
                Log::warning("Parent API ID (kabupaten) tidak diberikan untuk mencari kecamatan. ID: {$id}");
                return null;
            }
            $apiUrl = $this->apiBaseUrl . 'districts/' . $parentApiIdForUrl . '.json';
        } elseif ($type === 'kelurahan') {
            if (empty($parentApiIdForUrl)) {
                Log::warning("Parent API ID (kecamatan) tidak diberikan untuk mencari kelurahan. ID: {$id}");
                return null;
            }
            $apiUrl = $this->apiBaseUrl . 'villages/' . $parentApiIdForUrl . '.json';
        } else {
            Log::warning("Tipe tidak didukung untuk getGeoNameById: {$type}, ID: {$id}");
            return null;
        }

        if (empty($apiUrl)) {
            Log::warning("URL API kosong untuk getGeoNameById. Tipe: {$type}, ID: {$id}");
            return null;
        }

        Log::debug("Mencoba mengambil nama geo untuk ID: {$id}, Tipe: {$type}, URL: {$apiUrl}");

        try {
            $response = Http::get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data) && !empty($data)) {
                    foreach ($data as $item) {
                        $apiItemIdRaw = str_replace('.', '', $item['id']);

                        if (isset($item['id']) && $apiItemIdRaw == $id && isset($item['name'])) {
                            Log::debug("Ditemukan nama untuk ID {$id} ({$type}): {$item['name']}");
                            return $item['name'];
                        }
                    }
                    Log::warning("ID {$id} untuk tipe {$type} tidak ditemukan dalam respons API dari URL {$apiUrl}. Data respons: " . json_encode($data));
                } else {
                    Log::error("Respons API untuk {$type} ID {$id} bukan array atau kosong. URL: {$apiUrl}. Body: " . $response->body());
                }
            } else {
                Log::error("Gagal mendapatkan nama {$type} untuk ID {$id} dari API (HTTP Status " . $response->status() . '). URL: ' . $apiUrl . '. Body: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Kesalahan koneksi API untuk {$type} ID {$id}: " . $e->getMessage(), ['url' => $apiUrl, 'exception' => $e]);
        }
        return null;
    }
}