<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'admin_whatsapp' => '082283736481',
            'wa_message_template' => "Halo Admin, ada pengajuan kegiatan baru:\n\n*Nama Kegiatan:* {nama_kegiatan}\n*Tanggal:* {tgl_awal} s.d {tgl_selesai}\n*Tempat:* {tempat_kegiatan}\n*Pengaju:* {nama_user} (WA: {no_wa_user})\n\nSilakan cek di sistem SIHUMAS.",
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
