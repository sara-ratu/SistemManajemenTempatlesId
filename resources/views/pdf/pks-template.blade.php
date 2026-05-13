<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PKS - {{ $pks->nomor_surat ?? 'Draft' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #1a1a1a;
            line-height: 1.6;
        }

        .page {
            padding: 2cm 2.5cm;
        }

        /* HEADER */
        .header {
            text-align: center;
            border-bottom: 3px double #1a1a5e;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .header-logo {
            font-size: 22pt;
            font-weight: bold;
            color: #1a1a5e;
            letter-spacing: 2px;
        }

        .header-tagline {
            font-size: 9pt;
            color: #555;
            margin-top: 2px;
        }

        .header-address {
            font-size: 8.5pt;
            color: #666;
            margin-top: 4px;
        }

        /* JUDUL DOKUMEN */
        .doc-title {
            text-align: center;
            margin: 20px 0 6px;
        }

        .doc-title h2 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .doc-nomor {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 20px;
            color: #333;
        }

        /* PEMBUKA */
        .pembuka {
            text-align: justify;
            margin-bottom: 16px;
            font-size: 11.5pt;
        }

        /* PARA PIHAK */
        .pihak-container {
            margin: 16px 0;
        }

        .pihak {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            background: #f9f9ff;
            padding: 10px 14px;
        }

        .pihak-label {
            font-weight: bold;
            color: #1a1a5e;
            font-size: 11pt;
            margin-bottom: 6px;
        }

        .pihak-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
            font-size: 10.5pt;
        }

        .pihak-key {
            display: table-cell;
            width: 160px;
            color: #555;
        }

        .pihak-sep {
            display: table-cell;
            width: 16px;
        }

        .pihak-val {
            display: table-cell;
            font-weight: 500;
        }

        /* PASAL */
        .pasal {
            margin: 18px 0 10px;
        }

        .pasal-title {
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            font-size: 11.5pt;
            margin-bottom: 6px;
        }

        .pasal-subtitle {
            text-align: center;
            font-size: 11pt;
            font-style: italic;
            margin-bottom: 10px;
            color: #333;
        }

        .pasal-text {
            text-align: justify;
            font-size: 11pt;
            margin-bottom: 6px;
        }

        ol.pasal-list {
            padding-left: 20px;
            font-size: 11pt;
        }

        ol.pasal-list li {
            margin-bottom: 4px;
            text-align: justify;
        }

        /* TABEL JADWAL */
        .tbl {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10.5pt;
        }

        .tbl th {
            background: #1a1a5e;
            color: white;
            padding: 7px 10px;
            text-align: left;
            font-weight: 600;
        }

        .tbl td {
            padding: 6px 10px;
            border-bottom: 1px solid #e5e5e5;
        }

        .tbl tr:nth-child(even) td {
            background: #f5f5ff;
        }

        /* TANDA TANGAN */
        .ttd-section {
            margin-top: 40px;
        }

        .ttd-title {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 20px;
            color: #555;
        }

        .ttd-row {
            display: table;
            width: 100%;
        }

        .ttd-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }

        .ttd-pihak {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .ttd-nama {
            font-size: 10pt;
            color: #555;
            margin-bottom: 60px;
        }

        .ttd-line {
            border-bottom: 1px solid #333;
            width: 70%;
            margin: 0 auto 4px;
        }

        .ttd-jabatan {
            font-size: 9.5pt;
            color: #555;
        }

        /* METERAI */
        .meterai-box {
            border: 1px dashed #999;
            width: 80px;
            height: 80px;
            margin: 0 auto 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #aaa;
            text-align: center;
        }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            font-size: 8.5pt;
            color: #888;
            text-align: center;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }

        .badge-draft {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }

        .badge-signed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- KOPSURAT --}}
    <div class="header">
        <div class="header-logo">TEMPATLES.ID</div>
        <div class="header-tagline">Platform Bimbingan Belajar Terpercaya</div>
        <div class="header-address">
            Jl. Pendidikan No. 1, Kediri, Jawa Timur 64100 | Email: admin@tempatles.id | Telp: 0812-XXXX-XXXX
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="doc-title">
        <h2>Perjanjian Kerja Sama (PKS)</h2>
    </div>
    <div class="doc-nomor">
        Nomor: <strong>{{ $pks->nomor_surat ?? '___/PKS/TL/'.date('Y') }}</strong>
        &nbsp;&nbsp;
        <span class="badge-status {{ $pks->status === 'signed' ? 'badge-signed' : 'badge-draft' }}">
            {{ strtoupper($pks->status ?? 'DRAFT') }}
        </span>
    </div>

    {{-- PEMBUKA --}}
    <p class="pembuka">
        Pada hari ini, <strong>{{ $pks->tanggal_pks ? \Carbon\Carbon::parse($pks->tanggal_pks)->translatedFormat('l, d F Y') : '_______, ___ _________ _____' }}</strong>,
        telah dibuat dan ditandatangani Perjanjian Kerja Sama antara pihak-pihak yang tersebut di bawah ini:
    </p>

    {{-- PARA PIHAK --}}
    <div class="pihak-container">
        <div class="pihak">
            <div class="pihak-label">PIHAK PERTAMA (Platform)</div>
            <div class="pihak-row">
                <div class="pihak-key">Nama Platform</div>
                <div class="pihak-sep">:</div>
                <div class="pihak-val">Tempatles.id</div>
            </div>
            <div class="pihak-row">
                <div class="pihak-key">Diwakili Oleh</div>
                <div class="pihak-sep">:</div>
                <div class="pihak-val">Admin Tempatles.id</div>
            </div>
            <div class="pihak-row">
                <div class="pihak-key">Jabatan</div>
                <div class="pihak-sep">:</div>
                <div class="pihak-val">Pengelola Platform</div>
            </div>
        </div>

        <div class="pihak">
            <div class="pihak-label">PIHAK KEDUA (Tutor)</div>
            <div class="pihak-row">
                <div class="pihak-key">Nama Lengkap</div>
                <div class="pihak-sep">:</div>
                <div class="pihak-val"><strong>{{ $pks->tutorProfile->user->name ?? '-' }}</strong></div>
            </div>
            <div class="pihak-row">
                <div class="pihak-key">Nomor KTP</div>
                <div class="pihak-sep">:</div>
                <div class="pihak-val">{{ $pks->tutorProfile->no_ktp ?? '-' }}</div>
            </div>
            <div class="pihak-row">
                <div class="pihak-key">Mata Pelajaran</div>
                <div class="pihak-sep">:</div>
                <div class="pihak-val">{{ $pks->tutorProfile->mata_pelajaran ?? '-' }}</div>
            </div>
            <div class="pihak-row">
                <div class="pihak-key">Kota Mengajar</div>
                <div class="pihak-sep">:</div>
                <div class="pihak-val">{{ $pks->tutorProfile->kota ?? '-' }}</div>
            </div>
            <div class="pihak-row">
                <div class="pihak-key">Nomor HP</div>
                <div class="pihak-sep">:</div>
                <div class="pihak-val">{{ $pks->tutorProfile->user->phone ?? '-' }}</div>
            </div>
        </div>
    </div>

    <p class="pembuka">
        Kedua belah pihak selanjutnya disebut sebagai <strong>"Para Pihak"</strong>, sepakat untuk mengadakan
        Perjanjian Kerja Sama dengan ketentuan-ketentuan sebagai berikut:
    </p>

    {{-- PASAL 1 --}}
    <div class="pasal">
        <div class="pasal-title">PASAL 1</div>
        <div class="pasal-subtitle">Ruang Lingkup Kerja Sama</div>
        <ol class="pasal-list">
            <li>Pihak Pertama memberikan akses kepada Pihak Kedua untuk bergabung sebagai tutor pada platform Tempatles.id.</li>
            <li>Pihak Kedua bersedia menyediakan jasa pengajaran kepada murid yang dijodohkan melalui platform Tempatles.id.</li>
            <li>Ruang lingkup pengajaran meliputi mata pelajaran yang telah disetujui sesuai kompetensi Pihak Kedua.</li>
        </ol>
    </div>

    {{-- PASAL 2 --}}
    <div class="pasal">
        <div class="pasal-title">PASAL 2</div>
        <div class="pasal-subtitle">Honor dan Pembagian Hasil</div>
        <ol class="pasal-list">
            <li>Tarif mengajar Pihak Kedua sebesar <strong>Rp {{ number_format($pks->tutorProfile->tarif_per_jam ?? 0, 0, ',', '.') }}</strong> per sesi.</li>
            <li>Pembagian hasil: <strong>90% (sembilan puluh persen)</strong> untuk Pihak Kedua dan <strong>10% (sepuluh persen)</strong> untuk Pihak Pertama sebagai biaya pengelolaan platform.</li>
            <li>Honor dibayarkan setiap hari Jumat setelah sesi dikonfirmasi selesai oleh murid.</li>
            <li>Pembayaran dilakukan melalui rekening bank yang telah didaftarkan Pihak Kedua.</li>
        </ol>
    </div>

    {{-- PASAL 3 --}}
    <div class="pasal">
        <div class="pasal-title">PASAL 3</div>
        <div class="pasal-subtitle">Kewajiban Para Pihak</div>
        <ol class="pasal-list">
            <li>Pihak Kedua wajib hadir tepat waktu sesuai jadwal yang telah disepakati dengan murid.</li>
            <li>Pihak Kedua wajib mengisi laporan sesi setelah setiap sesi mengajar.</li>
            <li>Pihak Kedua dilarang mengajak murid untuk bertransaksi di luar platform Tempatles.id.</li>
            <li>Pihak Pertama wajib menyediakan platform yang berfungsi baik dan memproses pembayaran tepat waktu.</li>
        </ol>
    </div>

    {{-- PASAL 4 --}}
    <div class="pasal">
        <div class="pasal-title">PASAL 4</div>
        <div class="pasal-subtitle">Jangka Waktu Perjanjian</div>
        <p class="pasal-text">
            Perjanjian ini berlaku selama <strong>1 (satu) tahun</strong> terhitung sejak tanggal ditandatangani,
            yaitu mulai <strong>{{ $pks->tanggal_mulai ? \Carbon\Carbon::parse($pks->tanggal_mulai)->format('d F Y') : '-' }}</strong>
            sampai dengan <strong>{{ $pks->tanggal_selesai ? \Carbon\Carbon::parse($pks->tanggal_selesai)->format('d F Y') : '-' }}</strong>,
            dan dapat diperpanjang atas kesepakatan Para Pihak.
        </p>
    </div>

    {{-- PASAL 5 --}}
    <div class="pasal">
        <div class="pasal-title">PASAL 5</div>
        <div class="pasal-subtitle">Pemutusan Kerja Sama</div>
        <ol class="pasal-list">
            <li>Kerja sama dapat diputus sewaktu-waktu oleh salah satu pihak dengan pemberitahuan minimal 7 hari kerja.</li>
            <li>Pihak Pertama berhak memutus kerja sama tanpa pemberitahuan jika Pihak Kedua terbukti melanggar ketentuan pada Pasal 3.</li>
        </ol>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="ttd-section">
        <div class="ttd-title">
            Demikian Perjanjian Kerja Sama ini dibuat dan ditandatangani dengan penuh kesadaran dan tanpa paksaan dari pihak manapun.
        </div>
        <div class="ttd-row">
            <div class="ttd-col">
                <div class="ttd-pihak">PIHAK PERTAMA</div>
                <div class="ttd-nama">Tempatles.id</div>
                <div class="meterai-box">Meterai<br>Rp 10.000</div>
                <div class="ttd-line"></div>
                <div class="ttd-jabatan">Admin / Pengelola Platform</div>
            </div>
            <div class="ttd-col">
                <div class="ttd-pihak">PIHAK KEDUA</div>
                <div class="ttd-nama">{{ $pks->tutorProfile->user->name ?? '(Nama Tutor)' }}</div>
                <div class="meterai-box">Meterai<br>Rp 10.000</div>
                <div class="ttd-line"></div>
                <div class="ttd-jabatan">Tutor Tempatles.id</div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Dokumen ini dibuat secara elektronik oleh sistem Tempatles.id &bull;
        Nomor: {{ $pks->nomor_surat ?? '-' }} &bull;
        Dicetak: {{ now()->format('d F Y H:i') }}
    </div>

</div>
</body>
</html>
