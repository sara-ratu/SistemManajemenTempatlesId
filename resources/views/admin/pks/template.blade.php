<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 12pt;
    color: #000;
    line-height: 1.6;
  }
  .page { padding: 2cm 2.5cm; }
  .header { text-align: center; margin-bottom: 24pt; }
  .header .logo-text {
    font-size: 20pt;
    font-weight: bold;
    color: #1d4ed8;
    letter-spacing: 1px;
  }
  .header .tagline { font-size: 9pt; color: #555; }
  .divider { border-top: 3px solid #1d4ed8; margin: 8pt 0 4pt; }
  .divider-thin { border-top: 1px solid #ccc; margin: 4pt 0; }

  h1.judul {
    text-align: center;
    font-size: 13pt;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 20pt 0 4pt;
  }
  .nomor { text-align: center; font-size: 11pt; margin-bottom: 16pt; }

  p { margin-bottom: 8pt; text-align: justify; }

  .pihak-box {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10pt 14pt;
    margin: 10pt 0 16pt;
    background: #f9f9f9;
  }
  .pihak-box table { width: 100%; }
  .pihak-box td.label { width: 40%; font-weight: bold; vertical-align: top; }
  .pihak-box td.sep   { width: 5%; text-align: center; }
  .pihak-box td.value { width: 55%; }

  h2.section {
    font-size: 11pt;
    text-transform: uppercase;
    margin: 14pt 0 6pt;
    border-bottom: 1px solid #ddd;
    padding-bottom: 3pt;
  }
  ol { margin-left: 18pt; margin-bottom: 8pt; }
  ol li { margin-bottom: 4pt; }

  .ttd-area {
    margin-top: 40pt;
    display: table;
    width: 100%;
  }
  .ttd-col {
    display: table-cell;
    width: 50%;
    text-align: center;
  }
  .ttd-label { font-weight: bold; margin-bottom: 60pt; }
  .ttd-nama  { font-weight: bold; border-top: 1px solid #000; display: inline-block; min-width: 180px; padding-top: 4pt; }

  .footer {
    margin-top: 24pt;
    text-align: center;
    font-size: 9pt;
    color: #888;
    border-top: 1px solid #eee;
    padding-top: 8pt;
  }
</style>
</head>
<body>
<div class="page">

  {{-- Header --}}
  <div class="header">
    <div class="logo-text">Tempatles.id</div>
    <div class="tagline">Platform Les Privat Terpercaya · Kediri</div>
    <div class="divider"></div>
    <div class="divider-thin"></div>
  </div>

  <h1 class="judul">Perjanjian Kerja Sama (PKS)</h1>
  <p class="nomor">Nomor: <strong>{{ $nomor_pks }}</strong></p>

  <p>
    Pada hari ini, tanggal <strong>{{ $tanggal_cetak }}</strong>, telah disepakati
    Perjanjian Kerja Sama antara pihak-pihak berikut:
  </p>

  {{-- Pihak Pertama --}}
  <div class="pihak-box">
    <p style="font-weight:bold; margin-bottom:6pt;">PIHAK PERTAMA</p>
    <table>
      <tr>
        <td class="label">Nama Lembaga</td>
        <td class="sep">:</td>
        <td class="value">Tempatles.id</td>
      </tr>
      <tr>
        <td class="label">Alamat</td>
        <td class="sep">:</td>
        <td class="value">Kota Kediri, Jawa Timur</td>
      </tr>
      <tr>
        <td class="label">Selanjutnya disebut</td>
        <td class="sep">:</td>
        <td class="value"><strong>PIHAK PERTAMA</strong></td>
      </tr>
    </table>
  </div>

  {{-- Pihak Kedua --}}
  <div class="pihak-box">
    <p style="font-weight:bold; margin-bottom:6pt;">PIHAK KEDUA (TUTOR)</p>
    <table>
      <tr>
        <td class="label">Nama Lengkap</td>
        <td class="sep">:</td>
        <td class="value">{{ $tutor->name }}</td>
      </tr>
      @if($profile && $profile->tempat_lahir && $profile->tgl_lahir)
      <tr>
        <td class="label">Tempat, Tgl Lahir</td>
        <td class="sep">:</td>
        <td class="value">{{ $profile->tempat_lahir }}, {{ \Carbon\Carbon::parse($profile->tgl_lahir)->translatedFormat('d F Y') }}</td>
      </tr>
      @endif
      @if($profile && $profile->pendidikan)
      <tr>
        <td class="label">Pendidikan Terakhir</td>
        <td class="sep">:</td>
        <td class="value">{{ $profile->pendidikan }}</td>
      </tr>
      @endif
      <tr>
        <td class="label">No. WhatsApp</td>
        <td class="sep">:</td>
        <td class="value">{{ $profile->no_wa ?? $tutor->phone ?? '-' }}</td>
      </tr>
      <tr>
        <td class="label">Mata Pelajaran</td>
        <td class="sep">:</td>
        <td class="value">{{ $profile ? $profile->subjects->pluck('nama')->join(', ') : '-' }}</td>
      </tr>
      <tr>
        <td class="label">Selanjutnya disebut</td>
        <td class="sep">:</td>
        <td class="value"><strong>PIHAK KEDUA</strong></td>
      </tr>
    </table>
  </div>

  <h2 class="section">Pasal 1 — Tujuan Kerja Sama</h2>
  <p>
    Perjanjian ini mengatur hubungan kerja sama antara PIHAK PERTAMA sebagai pengelola platform
    Tempatles.id dengan PIHAK KEDUA sebagai tutor yang terdaftar dan telah diverifikasi, dalam
    rangka memberikan layanan les privat kepada murid yang terdaftar di platform.
  </p>

  <h2 class="section">Pasal 2 — Masa Berlaku</h2>
  <p>
    Perjanjian ini berlaku mulai tanggal
    <strong>{{ \Carbon\Carbon::parse($tanggal_mulai)->translatedFormat('d F Y') }}</strong>
    sampai dengan
    <strong>{{ \Carbon\Carbon::parse($tanggal_selesai)->translatedFormat('d F Y') }}</strong>
    dan dapat diperpanjang atas kesepakatan kedua belah pihak.
  </p>

  <h2 class="section">Pasal 3 — Kewajiban Pihak Kedua</h2>
  <ol>
    <li>Memberikan layanan les privat sesuai jadwal yang telah disepakati dengan murid.</li>
    <li>Menjaga profesionalisme, kesopanan, dan etika dalam setiap interaksi dengan murid.</li>
    <li>Melaporkan setiap sesi yang telah dilaksanakan melalui platform Tempatles.id.</li>
    <li>Tidak melakukan transaksi di luar platform selama masa perjanjian berlaku.</li>
    <li>Menjaga kerahasiaan data murid dan tidak menyebarluaskan informasi pribadi murid.</li>
    <li>Memberitahukan perubahan jadwal atau ketidakhadiran minimal 24 jam sebelumnya.</li>
  </ol>

  <h2 class="section">Pasal 4 — Kewajiban Pihak Pertama</h2>
  <ol>
    <li>Menyediakan platform yang dapat diakses oleh PIHAK KEDUA untuk mengelola jadwal dan murid.</li>
    <li>Mencarikan dan menghubungkan murid yang sesuai dengan profil PIHAK KEDUA.</li>
    <li>Memberikan laporan pendapatan secara transparan kepada PIHAK KEDUA.</li>
    <li>Mentransfer honor PIHAK KEDUA sesuai kesepakatan pembayaran yang berlaku.</li>
  </ol>

  <h2 class="section">Pasal 5 — Pembagian Pendapatan</h2>
  <p>
    Dari setiap sesi les yang terlaksana, pembagian pendapatan adalah sebagai berikut:
    <strong>{{ env('PLATFORM_KOMISI_PERSEN', 10) }}%</strong> untuk PIHAK PERTAMA sebagai biaya
    platform, dan <strong>{{ 100 - env('PLATFORM_KOMISI_PERSEN', 10) }}%</strong> untuk PIHAK KEDUA
    sebagai honorarium tutor.
  </p>

  <h2 class="section">Pasal 6 — Pengakhiran Perjanjian</h2>
  <p>
    Perjanjian ini dapat diakhiri sebelum masa berlaku berakhir apabila salah satu pihak
    melanggar ketentuan yang telah disepakati, atau atas kesepakatan bersama dengan
    pemberitahuan tertulis minimal 7 hari sebelumnya.
  </p>

  @if($catatan)
  <h2 class="section">Catatan Tambahan</h2>
  <p>{{ $catatan }}</p>
  @endif

  <h2 class="section">Pasal 7 — Pengesahan</h2>
  <p>
    Perjanjian ini dibuat dan ditandatangani dalam keadaan sadar dan tanpa paksaan dari pihak manapun.
  </p>

  {{-- Tanda Tangan --}}
  <div class="ttd-area">
    <div class="ttd-col">
      <div class="ttd-label">PIHAK PERTAMA</div>
      <div>Tempatles.id</div>
      <br><br>
      <div class="ttd-nama">(__________________________)</div>
      <div style="font-size:10pt; color:#555;">Pengelola Platform</div>
    </div>
    <div class="ttd-col">
      <div class="ttd-label">PIHAK KEDUA</div>
      <div>Tutor</div>
      <br><br>
      <div class="ttd-nama">{{ $tutor->name }}</div>
      <div style="font-size:10pt; color:#555;">Tutor Tempatles.id</div>
    </div>
  </div>

  <div class="footer">
    Dokumen ini digenerate secara otomatis oleh sistem Tempatles.id · {{ $tanggal_cetak }}
  </div>

</div>
</body>
</html>
