<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Kegiatan Baru</title>
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
                            <h2 style="margin: 0 0 12px; font-size: 18px; color: #0f172a; font-weight: 700;">Pengajuan Kegiatan Baru</h2>
                            <p style="margin: 0 0 20px; font-size: 14px; color: #475569;">
                                Ada pengajuan kegiatan baru yang diajukan ke sistem SIHUMAS dan memerlukan peninjauan lebih lanjut:
                            </p>

                            <!-- Detail Table -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin-bottom: 24px; font-size: 13px;">
                                <tbody>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; width: 35%; font-weight: 600; color: #475569; background-color: #f8fafc;">Nama Kegiatan</td>
                                        <td style="padding: 10px 8px; color: #0f172a; font-weight: 700;">{{ $pengajuan->nama_kegiatan }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Diajukan Oleh</td>
                                        <td style="padding: 10px 8px; color: #0f172a; font-weight: 600;">
                                            {{ $pengajuan->user?->name ?? '-' }}
                                            @if ($pengajuan->user?->email)
                                                <br><span style="color: #64748b; font-weight: normal; font-size: 12px;">{{ $pengajuan->user->email }}</span>
                                            @endif
                                            @if ($pengajuan->user?->no_wa)
                                                <br><span style="color: #059669; font-weight: normal; font-size: 12px;">WA: {{ $pengajuan->user->no_wa }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Unit Kegiatan</td>
                                        <td style="padding: 10px 8px; color: #0f172a;">{{ $pengajuan->unit_kegiatan }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Jadwal Pelaksanaan</td>
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
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Deskripsi</td>
                                        <td style="padding: 10px 8px; color: #0f172a;">{{ trim(strip_tags($pengajuan->deskripsi_kegiatan ?? '')) ?: '-' }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 10px 8px; font-weight: 600; color: #475569; background-color: #f8fafc;">Perlengkapan</td>
                                        <td style="padding: 10px 8px; color: #0f172a;">{{ trim(strip_tags($pengajuan->perlengkapan ?? '')) ?: '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0 10px;">
                                <a href="{{ route('login') }}" target="_blank" style="display: inline-block; background-color: #1e293b; color: #ffffff; padding: 12px 28px; font-size: 14px; font-weight: 600; text-decoration: none; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    Buka Kotak Masuk SIHUMAS
                                </a>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
                            <p style="margin: 0 0 6px;">Email ini dikirimkan secara otomatis oleh <strong>Sistem Informasi Humas (SIHUMAS)</strong> Universitas Ibnu Sina.</p>
                            <p style="margin: 0;">Silakan hubungi Tim Humas di <a href="mailto:humasuis@gmail.com" style="color: #64748b;">humasuis@gmail.com</a>.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
