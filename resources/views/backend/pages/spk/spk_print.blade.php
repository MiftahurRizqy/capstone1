<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perintah Kerja (SPK) - {{ $spk->nomor_spk }}</title>
    <link rel="icon" href="{{ asset('images/logo/kecil.png') }}" type="image/png">
    <style>
        /* --- STYLE DEFAULT (UNTUK WEB BROWSER) --- */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            font-size: 11pt;
        }
        .invoice-container {
            width: 21cm;
            min-height: 29.7cm;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header .logo {
            max-width: 150px;
            height: auto;
        }
        .header .company-details {
            text-align: right;
            line-height: 1.4;
        }
        .header .company-details h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .header .company-details p {
            margin: 2px 0;
            font-size: 14px;
            color: #555;
        }
        .title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .section {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .section h2 {
            font-size: 18px;
            color: #2c3e50;
            border-left: 4px solid #3498db;
            padding-left: 10px;
            margin-bottom: 15px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .details-grid .item {
            display: flex;
            flex-direction: column;
        }
        .details-grid .item strong {
            font-size: 12px;
            color: #7f8c8d;
        }
        .details-grid .item p {
            font-size: 14px;
            font-weight: bold;
            color: #34495e;
            margin: 0;
        }
        .details-grid .full-width {
            grid-column: 1 / -1;
        }
        .material-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 10px;
        }
        .material-table th, .material-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }
        .material-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .content-flex {
            display: flex;
            gap: 40px;
            margin-top: 15px;
        }
        .time-box {
             width: 35%;
        }
        .material-box {
            width: 65%;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        .signatures .person {
            width: 30%;
        }
        .signatures .name {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            font-weight: bold;
        }
        
        /* --- STYLE KHUSUS UNTUK CETAK (PRINT MEDIA) --- */
        @page {
            size: A4;
            /* Mengurangi margin horizontal ke 0.2cm */
            margin: 0.5cm 0.2cm; 
        }
        @media print {
            body {
                background-color: #fff;
                padding: 0;
                font-size: 10pt;
            }
            .invoice-container {
                box-shadow: none;
                border: none;
                width: 100%;
                min-height: auto;
                /* Mengurangi padding horizontal dari 10px menjadi 5px */
                padding: 10px 5px; 
            }
            /* Perkecil elemen untuk menghemat ruang vertikal */
            .header {
                margin-bottom: 10px;
                padding-bottom: 10px;
            }
            .header .logo {
                max-width: 120px;
            }
            .header .company-details h1 {
                font-size: 18px;
            }
            .header .company-details p {
                font-size: 9pt;
            }
            .title {
                font-size: 20px;
                margin-bottom: 10px;
            }
            .section {
                margin-bottom: 10px;
                padding-bottom: 5px;
            }
            .section h2 {
                font-size: 13pt;
                margin-bottom: 5px;
            }
            .details-grid {
                gap: 5px;
            }
            .details-grid .item p, 
            .details-grid .item strong {
                font-size: 10pt;
            }
            .material-table {
                font-size: 9pt;
            }
            .material-table th, .material-table td {
                padding: 3px;
            }
            .signatures {
                margin-top: 20px;
            }
            .signatures .name {
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        
        {{-- HEADER --}}
        <div class="header">
            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo Perusahaan" class="logo">
            <div class="company-details">
                <h1>Surat Perintah Kerja</h1>
                <p>MEGA CLEON</p>
                <p>PT. SARANA INSANMUDA SELARAS</p>
                <p>YOGYAKARTA - INDONESIA</p>
                <p>Telp. +62-822-2598-8821</p>
                <p>Website: http://www.megacleon.com</p>
            </div>
        </div>

        <h1 class="title">Surat Perintah Kerja (SPK)</h1>

        {{-- DETAIL SPK --}}
        <div class="section">
            <h2>Detail SPK</h2>
            <div class="details-grid">
                <div class="item">
                    <strong>Nomor SPK</strong>
                    <p>{{ $spk->nomor_spk }}</p>
                </div>
                <div class="item">
                    <strong>Tipe SPK</strong>
                    <p>{{ ucfirst($spk->tipe) }}</p>
                </div>
                <div class="item">
                    <strong>Status</strong>
                    <p>{{ ucfirst(str_replace('_', ' ', $spk->status)) }}</p>
                </div>
                <div class="item">
                    <strong>Rencana Pengerjaan</strong>
                    <p>{{ $spk->rencana_pengerjaan ? $spk->rencana_pengerjaan->format('d M Y') : '-' }}</p>
                </div>
                <div class="item">
                    <strong>Layanan Induk</strong>
                    <p>{{ $spk->layananInduk->nama_layanan_induk ?? '-' }}</p>
                </div>
                <div class="item">
                    <strong>POP</strong>
                    <p>{{ $spk->pop->nama_pop ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- INFORMASI PELANGGAN --}}
        <div class="section">
            <h2>Informasi Pelanggan</h2>
            <div class="details-grid">
                <div class="item">
                    <strong>Nama Pelanggan</strong>
                    <p>{{ ($spk->keluhan->pelanggan->tipe == 'personal' ? $spk->keluhan->pelanggan->nama_lengkap : $spk->keluhan->pelanggan->nama_perusahaan) ?? '-' }}</p>
                </div>
                <div class="item">
                    <strong>Nomor Pelanggan</strong>
                    <p>{{ $spk->keluhan->pelanggan->nomor_pelanggan ?? '-' }}</p>
                </div>
                <div class="item full-width">
                    <strong>Alamat</strong>
                    <p>{{ $spk->keluhan->pelanggan->alamat ?? '-' }}</p>
                </div>
                <div class="item full-width">
                    <strong>Deskripsi Keluhan</strong>
                    <p>{{ $spk->keterangan ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- PELAKSANA & WAKTU & MATERIAL --}}
        <div class="content-flex">
            
            {{-- BLOK KIRI: PELAKSANA & WAKTU --}}
            <div class="time-box">
                <div class="section" style="border-bottom: none; padding-bottom: 0; margin-bottom: 10px;">
                    <h2>Pelaksana & Kelengkapan</h2>
                    <div class="details-grid" style="gap: 5px;">
                        <div class="item full-width">
                            <strong>Pelaksana 1</strong>
                            <p>{{ $spk->pelaksana_1 ?? '-' }}</p>
                        </div>
                        <div class="item full-width">
                            <strong>Pelaksana 2</strong>
                            <p>{{ $spk->pelaksana_2 ?? '-' }}</p>
                        </div>
                        <div class="item full-width">
                            <strong>Koordinator</strong>
                            <p>{{ $spk->koordinator ?? '-' }}</p>
                        </div>
                        <div class="item full-width">
                            <strong>Kelengkapan Kerja</strong>
                            <p>{{ $spk->kelengkapan_kerja ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <h2>Jangka Waktu Pengerjaan</h2>
                    <p style="margin-bottom: 5px;"><strong>Tgl/jam mulai:</strong> ....................................................</p>
                    <p><strong>Tgl/jam selesai:</strong> ....................................................</p>
                </div>
            </div>
            
            {{-- BLOK KANAN: TABEL MATERIAL --}}
            <div class="material-box">
                <h2 style="margin-top: 0;">Material</h2>
                <table class="material-table">
                    <thead>
                        <tr>
                            <th style="width: 3%;">No</th>
                            <th style="width: 45%; text-align: left;">Material</th>
                            <th style="width: 10%;">Jml</th>
                            <th style="width: 12%;">Satuan</th>
                            <th style="width: 15%;">Ambil</th>
                            <th style="width: 15%;">Pakai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1</td><td style="text-align: left;">Kabel RG 11 Tap-Tiang</td><td></td><td>meter</td><td></td><td></td></tr>
                        <tr><td>2</td><td style="text-align: left;">Kabel RG 11 Tiang-Plg</td><td></td><td>meter</td><td></td><td></td></tr>
                        <tr><td>3</td><td style="text-align: left;">Kabel RG 6 Tap-Tiang</td><td></td><td>meter</td><td></td><td></td></tr>
                        <tr><td>4</td><td style="text-align: left;">Kabel RG 6 Tiang-Plg</td><td></td><td>meter</td><td></td><td></td></tr>
                        <tr><td>5</td><td style="text-align: left;">Kabel Untuk Titik Tambahan</td><td></td><td>meter</td><td></td><td></td></tr>
                        <tr><td>6</td><td style="text-align: left;">Connector RG 11</td><td></td><td>buah</td><td></td><td></td></tr>
                        <tr><td>7</td><td style="text-align: left;">Connector RG 6</td><td></td><td>buah</td><td></td><td></td></tr>
                        <tr><td>8</td><td style="text-align: left;">I Connector</td><td></td><td>buah</td><td></td><td></td></tr>
                        <tr><td>9</td><td style="text-align: left;">Splitter 2 way</td><td></td><td>buah</td><td></td><td></td></tr>
                        <tr><td>10</td><td style="text-align: left;">Splitter 4 way</td><td></td><td>buah</td><td></td><td></td></tr>
                        <tr><td>11</td><td style="text-align: left;">Pipa</td><td></td><td>meter</td><td></td><td></td></tr>
                        <tr><td>12</td><td style="text-align: left;">Paku Klem</td><td></td><td>buah</td><td></td><td></td></tr>
                        <tr><td>13</td><td style="text-align: left;">Outlet</td><td></td><td>buah</td><td></td><td></td></tr>
                        <tr><td>14</td><td style="text-align: left;">Jack TV</td><td></td><td>buah</td><td></td><td></td></tr>
                        <tr><td>15</td><td style="text-align: left;">Tiang</td><td></td><td>buah</td><td></td><td></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="border-bottom: 1px solid #eee; margin-top: 15px;"></div>

        {{-- TANDA TANGAN --}}
        <div class="signatures">
            <div class="person">
                <strong>Dibuat Oleh:</strong>
                <div class="name">({{ $spk->user->name ?? '-' }})</div>
                <p>({{ $spk->created_at->format('d/m/Y') }})</p>
            </div>
            <div class="person">
                <strong>Pelaksana:</strong>
                <div class="name">({{ $spk->pelaksana_1 ?? '-' }})</div>
                <p>(Tanggal)</p>
            </div>
            <div class="person">
                <strong>Disetujui Oleh:</strong>
                <div class="name">(....................)</div>
                <p>(Tanggal)</p>
            </div>
        </div>
    </div>
</body>
</html>