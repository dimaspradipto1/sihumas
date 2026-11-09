<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Status Pengajuan Kegiatan</title>
</head>
<body style="margin: 0; padding: 20px 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; line-height: 1.6;">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9;">
        <tr>
            <td align="center" style="padding: 20px 10px;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 24px 30px; text-align: center;">
                            <div style="color: #94a3b8; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 4px;">UNIVERSITAS IBNU SINA</div>
                            <div style="color: #ffffff; font-size: 20px; font-weight: 700;">SIHUMAS - Sistem Informasi Humas</div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="margin: 0 0 12px; font-size: 18px; color: #0f172a; font-weight: 700;">Update Status Pengajuan Kegiatan</h2>
                            <p style="margin: 0 0 20px; font-size: 14px; color: #475569;">
                                Halo, pengajuan kegiatan berikut telah ditinjau dan diperbarui oleh Tim Humas Universitas Ibnu Sina:
                            </p>

                            <!-- Status Banner -->
                            <div style="margin-bottom: 24px; padding: 14px 18px; border-radius: 6px; text-align: center;
                                @if($pengajuan->status == 'diterima') background-color: #ecfdf5; border: 1px solid #a7f3d0;
                                @elseif($pengajuan->status == 'ditolak') background-color: #fef2f2; border: 1px solid #fecaca;
                                @else background-color: #fffbeb; border: 1px solid #fde68a; @endif">
                                <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #64748b; display: block; margin-bottom: 4px;">Status Saat Ini:</span>
                                <span style="font-size: 18px; font-weight: 800; text-transform: uppercase;
                                    @if($pengajuan->status == 'diterima') color: #059669;
                                    @elseif($pengajuan->status == 'ditolak') color: #dc2626;
                                    @else color: #d97706; @endif">
                                    {{ $pengajuan->status }}
                                </span>
                            </div>

                            <!-- Detail Table -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin-bottom: 24px; font-size: 13px;">
                                <tbody>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; width: 35%; font-weight: 600; color: #475569; background-color: #f8fafc;">Nama Kegiatan</td>
                                        <td style="padding: 10px 8px; color: #0f172a; font-weight: 700;">{{ $pengajuan->nama_kegiatan }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Unit Pengaju</td>
                                        <td style="padding: 10px 8px; color: #0f172a;">{{ $pengajuan->unit_kegiatan }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Jadwal Kegiatan</td>
                                        <td style="padding: 10px 8px; color: #0f172a;">
                                            {{ \Carbon\Carbon::parse($pengajuan->tgl_awal)->locale('id')->isoFormat('D MMMM Y') }}
                                            @if($pengajuan->tgl_awal != $pengajuan->tgl_selesai)
                                                s/d {{ \Carbon\Carbon::parse($pengajuan->tgl_selesai)->locale('id')->isoFormat('D MMMM Y') }}
                                            @endif
                                            <br>
                                            <span style="color: #64748b;">Pukul {{ $pengajuan->jam_kegiatan }} - {{ $pengajuan->waktu_selesai }} WIB</span>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Tempat Kegiatan</td>
                                        <td style="padding: 10px 8px; color: #0f172a;">{{ $pengajuan->tempat_kegiatan ?: '-' }}</td>
                                    </tr>
                                    @if ($pengajuan->link_zoom)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Link Zoom</td>
                                        <td style="padding: 10px 8px; color: #2563eb; word-break: break-all;">
                                            <a href="{{ $pengajuan->link_zoom }}" target="_blank" style="color: #2563eb;">{{ $pengajuan->link_zoom }}</a>
                                        </td>
                                    </tr>
                                    @endif
                                    @if ($pengajuan->petugas_ditugaskan)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Petugas / PIC</td>
                                        <td style="padding: 10px 8px; color: #0f172a; font-weight: 600;">
                                            {{ $pengajuan->petugas_ditugaskan }}
                                            @if ($pengajuan->no_wa_tujuan)
                                                <span style="color: #059669; font-weight: normal; margin-left: 4px;">({{ $pengajuan->no_wa_tujuan }})</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @if ($pengajuan->alasan_ditolak)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Catatan Verifikasi</td>
                                        <td style="padding: 10px 8px; color: #0f172a;">{{ $pengajuan->alasan_ditolak }}</td>
                                    </tr>
                                    @endif
                                    @if ($pengajuan->link_desain)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Link Desain</td>
                                        <td style="padding: 10px 8px; color: #2563eb; word-break: break-all;">
                                            <a href="{{ $pengajuan->link_desain }}" target="_blank" style="color: #2563eb;">{{ $pengajuan->link_desain }}</a>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0 10px;">
                                <a href="{{ route('login') }}" target="_blank" style="display: inline-block; background-color: #1e293b; color: #ffffff; padding: 12px 28px; font-size: 14px; font-weight: 600; text-decoration: none; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    Masuk ke SIHUMAS
                                </a>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
                            <p style="margin: 0 0 6px;">Email ini dikirimkan secara otomatis oleh <strong>Sistem Informasi Humas (SIHUMAS)</strong> Universitas Ibnu Sina.</p>
                            <p style="margin: 0;">Jika ada pertanyaan, silakan hubungi Humas UIS di <a href="mailto:humasuis@gmail.com" style="color: #64748b;">humasuis@gmail.com</a>.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
