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

        $adminWaList = Setting::getAdminWhatsappList();
        if (empty($adminWaList)) {
            $adminWaList = [['nama' => 'Admin Humas', 'no_wa' => '6282283736481', 'is_active' => true]];
        }

        $settings = [
            'admin_whatsapp' => Setting::getValue('admin_whatsapp', '6282283736481'),
            'admin_whatsapp_list' => $adminWaList,
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
            'wa_message_template' => 'required|string',
            'admin_whatsapp' => 'nullable|string',
            'admin_wa_nomor' => 'nullable|array',
            'admin_wa_nomor.*' => 'nullable|string|max:30',
            'admin_wa_nama' => 'nullable|array',
            'admin_wa_nama.*' => 'nullable|string|max:100',
            'admin_wa_active' => 'nullable|array',
        ]);

        $waList = [];
        if ($request->has('admin_wa_nomor') && is_array($request->admin_wa_nomor)) {
            $names = $request->input('admin_wa_nama', []);
            $actives = $request->input('admin_wa_active', []);

            foreach ($request->admin_wa_nomor as $idx => $rawNo) {
                $normalized = Setting::normalizeWhatsappNumber($rawNo);
                if ($normalized === '') {
                    continue;
                }
                $waList[] = [
                    'nama' => trim((string) ($names[$idx] ?? '')) ?: ('Admin Humas ' . (count($waList) + 1)),
                    'no_wa' => $normalized,
                    'is_active' => !empty($actives[$idx]) && (string) $actives[$idx] !== '0',
                ];
            }
        } elseif ($request->filled('admin_whatsapp')) {
            $normalized = Setting::normalizeWhatsappNumber($request->admin_whatsapp);
            if ($normalized !== '') {
                $waList[] = [
                    'nama' => 'Admin Humas',
                    'no_wa' => $normalized,
                    'is_active' => true,
                ];
            }
        }

        if (empty($waList)) {
            return back()
                ->withErrors(['admin_wa_nomor' => 'Minimal harus ada 1 nomor WhatsApp Admin yang valid.'])
                ->withInput();
        }

        Setting::setValue('admin_whatsapp_list', json_encode($waList));
        $firstActive = collect($waList)->firstWhere('is_active', true);
        Setting::setValue('admin_whatsapp', $firstActive['no_wa'] ?? ($waList[0]['no_wa'] ?? null));
        Setting::setValue('wa_message_template', $request->wa_message_template);

        Alert::success('Success', 'Pengaturan WhatsApp berhasil diperbarui.')->toToast()->autoclose(3000)->timerProgressBar();
        return redirect()->route('settings.index');
    }
}
