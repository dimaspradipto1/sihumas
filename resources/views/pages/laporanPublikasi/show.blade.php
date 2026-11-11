<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kop Surat Universitas Ibnu Sina</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .kop-surat img {
            float: left;
            width: 100px;
            margin-right: 10px;
        }

        .kop-surat h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            color: green;
        }

        .kop-surat h2 {
            font-size: 36px;
            margin: 0;
            color: green;
        }

        .kop-surat h3 {
            font-size: 24px;
            margin: 0;
            color: red;
        }

        .kop-surat p {
            font-size: 16px;
            margin: 5px 0;
            color: black;
            margin-left: 110px;
        }

        .kop-surat .contact-info {
            margin-top: 5px;
            font-size: 14px;
        }

        .kop-surat hr {
            border: none;
            border-top: 3px solid green;
            margin: 2px 0;
        }

        .kop-surat hr.thin {
            border-top: 1px solid green;
        }

        .letter-header {
            width: 100%;
            margin: 20px auto;
            text-align: center;
            text-transform: uppercase;
        }

        .letter-header h3,
        .letter-header h4 {
            margin: 0;
        }

        .letter-header h3 {
            font-size: 16px;
            font-weight: bold;
        }

        .letter-header h4 {
            font-size: 14px;
        }

        .subject {
            margin-top: 20px;
            font-weight: bold;
        }

        .content {
            margin-top: 20px;
            text-align: justify;
        }

        .table-container {
            width: 100%;
            margin-top: 20px;
            text-align: center;
        }

        .laporan-table {
            width: 100%;
            border: 2px solid #000;
            border-collapse: collapse;
            margin: 0 auto;
        }

        /* Menyesuaikan lebar kolom Fakultas dan ukuran font */
        .laporan-table td.fakultas {
            width: 80px;
            /* Lebar kolom fakultas lebih kecil */
            font-size: 8px;
            /* Ukuran font kecil untuk fakultas */
        }

        .laporan-table th,
        .laporan-table td {
            border: 1px solid #000;
            padding: 6px;
            /* Padding kecil untuk memperkecil jarak kolom */
            font-size: 10px;
            /* Ukuran font lebih kecil untuk seluruh tabel */
            text-align: left;
        }

        /* Perbesar lebar kolom Link Pelaporan */
        .laporan-table td.link-pelaporan {
            width: 100px;
            /* Lebar kolom lebih besar untuk link pelaporan */
            font-size: 10px;
            /* Ukuran font lebih besar agar mudah dibaca */
        }

        .laporan-table td a {
            color: #007bff;
            text-decoration: none;
        }

        .laporan-table td a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 40px;
        }

        .signature-section {
            margin-top: 40px;
            position: relative;
            width: 100%;
            height: 150px;
            text-align: left;
        }

        .signature-section img.stamp {
            width: 230px;
            opacity: 1;
            position: relative;
            margin-left: -130px;
            margin-top: -20px;
        }

        .signature-section .text {
            position: absolute;
            top: 20px;
            right: 0;
            text-align: left;
        }

        .footer {
            font-size: 14px;
        }

        .signature-section .tembusan {
            float: left;
            margin-left: 0;
            text-align: left;
        }

        /* Media print: Menyembunyikan tombol Download dan Kembali serta mengubah font menjadi kecil */
        @media print {
            .print-buttons {
                display: none;
            }
            .hr {
                display: none;
            }

            /* Ukuran font kecil saat dicetak */
            .laporan-table th,
            .laporan-table td {
                font-size: 8px !important;
                /* Ukuran font lebih kecil saat dicetak */
                padding: 5px;
                /* Menambah jarak antar kolom untuk kenyamanan saat dicetak */
            }

            /* Memastikan tabel lebar penuh saat dicetak */
            .laporan-table {
                width: 100% !important;
            }

            /* Menyesuaikan header tabel agar rata tengah */
            .laporan-table th {
                text-align: center;
            }
        }
    </style>

</head>

    

<body>
    <!-- Tombol Download dan Kembali -->
    <div class="print-buttons" style="text-align: right; margin-bottom: 20px; margin-right: 20px; margin-top: 20px;">
        <!-- Tombol Download -->
        <a href="javascript:window.print();" class="btn btn-primary"
            style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
            <i class="fa-solid fa-download"></i> Download
        </a>

        <!-- Tombol Kembali -->
        <a href="{{ route('laporan-publikasi.index') }}" class="btn btn-secondary"
            style="padding: 10px 20px; background-color: #FF0101FF; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
    <hr class="hr">

    <!-- Kop Surat Section -->
    <div class="kop-surat">
        <img src="{{ asset('volt/assets/img/surat/logouis.png') }}" alt="Logo Universitas Ibnu Sina">
        <div class="text-center">
            <h1>YAYASAN PENDIDIKAN IBNU SINA BATAM (YAPISTA)</h1>
            <h2>UNIVERSITAS IBNU SINA (UIS)</h2>
            <p>Jalan Teuku Umar, Lubuk Baja Kota Batam Indonesia Telp. 0778 425391</p>
            <p>Email: info@uis.ac.id/uisibnusina@gmail.com Website: uis.ac.id</p>
        </div>
        <hr>
        <hr class="thin">
    </div>

    <!-- Letter Content Section -->
    <div class="letter-header">
        <h3 class="text-uppercase">laporan kegiatan humas dan publikasi</h3>
        <h4>universitas ibnu sina</h4>
        @if (!empty($filterLabel))
            <h4 style="color: #006694; font-weight: bold; margin-top: 6px; text-transform: none;">{{ $filterLabel }}</h4>
        @endif
    </div>

    <div class="table-container">
        <table id="laporanPublikasi-table" class="laporan-table">
            <thead>
                <tr>
                    <th style="text-align: center; width: 30px;">No</th>
                    <th style="width: 100px;">Tahun Akademik</th>
                    <th>Fakultas</th>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Awal</th>
                    <th>Tanggal Selesai</th>
                    <th>Deskripsi Kegiatan</th>
                    <th>Link Pelaporan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanPublikasi as $key => $laporan)
                    <tr>
                        <td style="text-align: center">{{ $key + 1 }}</td>
                        <td>{{ $laporan->publikasi?->tahunAkademik?->tahun_akademik ?? '-' }}</td>
                        <td class="fakultas">{{ $laporan->user?->fakultas ?? ($laporan->pengajuan?->user?->fakultas ?? 'N/A') }}</td>
                        <td>{{ $laporan->pengajuan?->nama_kegiatan ?? '-' }}</td>
                        <td>{{ $laporan->pengajuan?->tgl_awal ? \Carbon\Carbon::parse($laporan->pengajuan->tgl_awal)->locale('id')->translatedFormat('l, d F Y') : '-' }}</td>
                        <td>{{ $laporan->pengajuan?->tgl_selesai ? \Carbon\Carbon::parse($laporan->pengajuan->tgl_selesai)->locale('id')->translatedFormat('l, d F Y') : '-' }}</td>
                        <td>{{ trim(strip_tags($laporan->pengajuan?->deskripsi_kegiatan ?? '')) ?: '-' }}</td>
                        <td class="link-pelaporan">
                            @if($laporan->publikasi?->upload_laporan)
                                <a href="{{ $laporan->publikasi->upload_laporan }}" target="_blank">Link Pelaporan</a><br>
                            @endif
                            @if($laporan->publikasi?->link_dokumentasi)
                                <a href="{{ $laporan->publikasi->link_dokumentasi }}" target="_blank">Link Dokumentasi</a><br>
                            @endif
                            @if($laporan->publikasi?->link_publikasi)
                                <a href="{{ $laporan->publikasi->link_publikasi }}" target="_blank">Link Publikasi</a>
                            @endif
                            @if(!$laporan->publikasi?->upload_laporan && !$laporan->publikasi?->link_dokumentasi && !$laporan->publikasi?->link_publikasi)
                                <span style="color: #888;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 25px; color: #666; font-style: italic;">
                            Tidak ada data laporan kegiatan untuk filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Signature Section with Stamp -->
    <div class="signature-section">
        <div class="text" style="text-align: left; margin-top: 20px;">
            <p style="margin: 0;">Batam, {{ \Carbon\Carbon::now()->locale('id_ID')->isoFormat('D MMMM Y') }}</p>
            <p style="margin: 0;">Kabit Humas dan Publikasi</p>
            <div
                style="display: flex; flex-direction: column; justify-content: left; align-items: center; margin-top: 15px;">
                <div style="display: flex; justify-content: center; align-items: center; margin-bottom: -10px;">
                    <img src="{{ asset('volt/assets/img/surat/babang.png') }}" alt="Tanda Tangan" class="stamp"
                        style="width: 250px; position: absolute; z-index: 1; margin-top: 60px; margin-right: -100px;">
                </div>
                <div style="display: flex; align-items: center; text-align: left; margin-top: 80px;">
                    <div>
                        <p style="margin: 0; font-weight: bold; text-decoration: underline;">Andi Akbar, SE., M.M</p>
                        <p style="margin: 0;">NUP. 7770707688</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Tembusan:</p>
        <p style="margin-left: 25px; margin-bottom: 30px;">- Arsip</p>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
                "Oktober", "November", "Desember"
            ];
            var tanggalSekarang = new Date();
            var tanggal = tanggalSekarang.getDate();
            var namaBulan = bulan[tanggalSekarang.getMonth()];
            var tahun = tanggalSekarang.getFullYear();
            var tanggalIndonesia = `Batam, ${tanggal} ${namaBulan} ${tahun}`;
            document.getElementById("tanggal").textContent = tanggalIndonesia;
        });
    </script>

</body>

</html>
