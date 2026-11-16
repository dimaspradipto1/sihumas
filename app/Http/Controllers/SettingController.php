<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        // Pastikan hanya admin yang bisa mengakses
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $defaultTemplate = "Halo Admin, ada pengajuan kegiatan baru:\n\n*Nama Kegiatan:* {nama_kegiatan}\n*Tanggal:* {tgl_awal} s.d {tgl_selesai}\n*Tempat:* {tempat_kegiatan}\n*Pemohon:* {nama_user} (WA: {no_wa_user})\n\nSilakan cek di sistem SIHUMAS:\nhttps://sihumas.uisbatam.com/";
        $currentTemplate = Setting::getValue('wa_message_template', $defaultTemplate);
        if (!empty($currentTemplate) && !str_contains($currentTemplate, 'sihumas.uisbatam.com') && !str_contains($currentTemplate, '{link_sistem}')) {
            $currentTemplate = rtrim($currentTemplate) . "\nhttps://sihumas.uisbatam.com/";
            Setting::setValue('wa_message_template', $currentTemplate);
        }

        $settings = [
            'admin_whatsapp' => Setting::getValue('admin_whatsapp', '6282283736481'),
            'wa_message_template' => $currentTemplate ?: $defaultTemplate,
        ];

        return view('pages.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        // Pastikan hanya admin yang bisa mengakses
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'admin_whatsapp' => 'required|string',
            'wa_message_template' => 'required|string',
        ]);

        Setting::setValue('admin_whatsapp', $request->admin_whatsapp);
        Setting::setValue('wa_message_template', $request->wa_message_template);

        Alert::success('Success', 'Pengaturan WhatsApp berhasil diperbarui.')->toToast()->autoclose(3000)->timerProgressBar();
        return redirect()->route('settings.index');
    }
}
