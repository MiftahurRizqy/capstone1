<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->nomor_invoice ?? '-' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/logo/logo.png') }}" type="image/png">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
        body {
            font-family: 'Inter', sans-serif;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
    </style>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-xl p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <img src="{{ asset('images/logo/logo.png') }}" alt="Logo Perusahaan" class="h-16">
            </div>
            <div class="text-right">
                <h1 class="text-4xl font-extrabold text-blue-600">INVOICE</h1>
                <p class="text-sm text-gray-600 mt-2">Nomor: {{ $invoice->nomor_invoice ?? '-' }}</p>
                <p class="text-sm text-gray-600">Tanggal: {{ $invoice->created_at->format('d F Y') ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-10 text-gray-700">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2 border-b-2 border-blue-500 pb-1">Ditagihkan Kepada:</h3>
                <p class="font-medium">{{ $invoice->pelanggan->nama_lengkap ?? $invoice->pelanggan->nama_perusahaan }}</p>
                <p class="text-sm">{{ $invoice->pelanggan->alamat }}</p>
                <p class="text-sm">{{ $invoice->pelanggan->kota }}, {{ $invoice->pelanggan->kode_pos }}</p>
                <p class="text-sm">Telp: {{ $invoice->pelanggan->no_hp }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-lg font-semibold text-gray-800 mb-2 border-b-2 border-blue-500 pb-1">Detail Pembayaran:</h3>
                <p class="text-sm">Jatuh Tempo: <span class="font-semibold">{{ $invoice->jatuh_tempo->format('d F Y') ?? '-' }}</span></p>
                <p class="text-sm">Total: <span class="font-semibold text-blue-600">{{ number_format($invoice->total_biaya ?? 0, 2, ',', '.') }} {{ $invoice->pelanggan->penagihan->mata_uang ?? 'IDR' }}</span></p>
                <p class="text-sm">Status: <span class="font-semibold">{{ ucfirst($invoice->status ?? '-') }}</span></p>
                <p class="text-sm">Metode Pembayaran: {{ ucwords(str_replace('_', ' ', $invoice->metode_pembayaran)) ?? '-' }}</p>
            </div>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-6 mb-10">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Deskripsi Layanan</h3>
            <div class="grid grid-cols-3 gap-4 font-bold text-gray-800 mb-2">
                <div>Deskripsi</div>
                <div class="text-right">Harga Satuan</div>
                <div class="text-right">Jumlah</div>
            </div>
            <div class="grid grid-cols-3 gap-4 border-b border-gray-200 py-2 text-gray-700">
                <div>{{ $invoice->layanan->layananEntry->nama_paket ?? 'N/A' }}</div>
                <div class="text-right">{{ number_format($invoice->total_biaya ?? 0, 2, ',', '.') }} {{ $invoice->pelanggan->penagihan->mata_uang ?? 'IDR' }}</div>
                <div class="text-right">1</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 text-gray-700">
            <div>
                <p class="font-semibold text-gray-800">Catatan:</p>
                <p class="text-sm">{{ $invoice->keterangan ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-md font-semibold text-gray-800 border-t-2 border-gray-400 pt-4">Subtotal: {{ number_format($invoice->layanan->layananEntry->biaya ?? 0, 2, ',', '.') }} {{ $invoice->pelanggan->penagihan->mata_uang ?? 'IDR' }}</p>
                @if ($invoice->pelanggan->penagihan && $invoice->pelanggan->penagihan->kenakan_ppn)
                    <p class="text-md font-semibold text-gray-800">PPN (11%): {{ number_format(($invoice->layanan->layananEntry->biaya ?? 0) * 0.11, 2, ',', '.') }} {{ $invoice->pelanggan->penagihan->mata_uang ?? 'IDR' }}</p>
                @endif
                <p class="text-2xl font-bold text-blue-600 border-t-2 border-gray-400 pt-2 mt-2">Total: {{ number_format($invoice->total_biaya ?? 0, 2, ',', '.') }} {{ $invoice->pelanggan->penagihan->mata_uang ?? 'IDR' }}</p>
            </div>
        </div>
    </div>
    
    <div class="no-print mt-8 flex justify-center items-center">
        <button onclick="window.print()" class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition-all duration-200">
            Cetak Invoice
        </button>
    </div>

</body>
</html>
