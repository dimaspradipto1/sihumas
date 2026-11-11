<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pengajuan;
use App\Models\KotakMasukPengajuan;
use App\Models\TahunAkademik;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KotakMasukTest extends TestCase
{
    public function test_humas_only_sees_assigned_pengajuan_in_kotak_masuk(): void
    {
        $ujangName = 'UJANG_' . uniqid();
        $ujangWa = '0877' . rand(10000000, 99999999);

        $budiName = 'BUDI_' . uniqid();
        $budiWa = '0888' . rand(10000000, 99999999);

        $facultyUser = User::factory()->create([
            'is_feb' => true,
            'name' => 'Operator FEB',
        ]);

        $humasUjang = User::factory()->create([
            'name' => $ujangName,
            'no_wa' => $ujangWa,
            'is_humas' => true,
            'is_admin' => false,
        ]);

        $humasBudi = User::factory()->create([
            'name' => $budiName,
            'no_wa' => $budiWa,
            'is_humas' => true,
            'is_admin' => false,
        ]);

        // 1. Pengajuan yang ditugaskan ke UJANG
        $pengajuanUjang = Pengajuan::create([
            'user_id' => $facultyUser->id,
            'unit_kegiatan' => 'FEB',
            'nama_kegiatan' => 'Workshop Desain Grafis Ujang',
            'tgl_awal' => '2026-11-15',
            'tgl_selesai' => '2026-11-15',
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'status' => 'diterima',
            'petugas_ditugaskan' => $ujangName,
            'no_wa_tujuan' => $ujangWa,
            'pic_petugas' => [
                ['nama' => $ujangName, 'no_wa' => $ujangWa]
            ],
        ]);
        KotakMasukPengajuan::create(['pengajuan_id' => $pengajuanUjang->id]);

        // 2. Pengajuan yang ditugaskan ke BUDI (bukan UJANG)
        $pengajuanBudi = Pengajuan::create([
            'user_id' => $facultyUser->id,
            'unit_kegiatan' => 'FEB',
            'nama_kegiatan' => 'Dokumentasi Seminar Budi',
            'tgl_awal' => '2026-11-16',
            'tgl_selesai' => '2026-11-16',
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'status' => 'diterima',
            'petugas_ditugaskan' => $budiName,
            'no_wa_tujuan' => $budiWa,
            'pic_petugas' => [
                ['nama' => $budiName, 'no_wa' => $budiWa]
            ],
        ]);
        KotakMasukPengajuan::create(['pengajuan_id' => $pengajuanBudi->id]);

        // 3. Pengajuan pending umum yang BELUM ditugaskan ke siapapun
        $pengajuanUnassigned = Pengajuan::create([
            'user_id' => $facultyUser->id,
            'unit_kegiatan' => 'FEB',
            'nama_kegiatan' => 'Rapat Senat Belum Ada PIC',
            'tgl_awal' => '2026-11-17',
            'tgl_selesai' => '2026-11-17',
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'status' => 'pending',
            'petugas_ditugaskan' => null,
            'no_wa_tujuan' => null,
            'pic_petugas' => null,
        ]);
        KotakMasukPengajuan::create(['pengajuan_id' => $pengajuanUnassigned->id]);

        // Request Kotak Masuk oleh Ujang via AJAX Datatables
        $response = $this->actingAs($humasUjang)
            ->getJson(route('kotak-masuk-pengajuan.index'), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $data = $response->json();

        // Ujang HANYA boleh melihat data yang ditugaskan kepada dirinya
        $this->assertEquals(1, $data['recordsTotal'] ?? null);
        $jsonString = json_encode($data);
        $this->assertStringContainsString('Workshop Desain Grafis Ujang', $jsonString);
        $this->assertStringNotContainsString('Dokumentasi Seminar Budi', $jsonString);
        $this->assertStringNotContainsString('Rapat Senat Belum Ada PIC', $jsonString);
    }

    public function test_humas_notification_only_counts_assigned_tasks(): void
    {
        $uniqueName = 'UJANG_NOTIF_' . uniqid();
        $uniqueWa = '0899' . rand(10000000, 99999999);

        $facultyUser = User::factory()->create([
            'is_feb' => true,
            'name' => 'Operator FEB',
        ]);

        $humasUjang = User::factory()->create([
            'name' => $uniqueName,
            'no_wa' => $uniqueWa,
            'is_humas' => true,
            'is_admin' => false,
        ]);

        // Buat pengajuan pending umum (bukan untuk Ujang)
        Pengajuan::create([
            'user_id' => $facultyUser->id,
            'unit_kegiatan' => 'FEB',
            'nama_kegiatan' => 'Kegiatan Fakultas Lain',
            'tgl_awal' => '2026-11-15',
            'tgl_selesai' => '2026-11-15',
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'status' => 'pending',
        ]);

        // Saat Ujang login dan belum punya tugas yang ditugaskan, notifikasi tugasnya harus 0
        $response = $this->actingAs($humasUjang)->get(route('dashboard'));
        $response->assertStatus(200);

        // Session notif untuk humas harus 0 karena belum ada pengajuan yang di-assign ke Ujang
        $notif = session('pengajuan_notif', []);
        $this->assertEquals(0, data_get($notif, 'humas.pending', 0));
        $this->assertEquals(0, data_get($notif, 'humas.diterima', 0));

        // Sekarang kita assign 1 tugas ke Ujang
        Pengajuan::create([
            'user_id' => $facultyUser->id,
            'unit_kegiatan' => 'FEB',
            'nama_kegiatan' => 'Tugas Khusus Ujang',
            'tgl_awal' => '2026-11-18',
            'tgl_selesai' => '2026-11-18',
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'status' => 'diterima',
            'petugas_ditugaskan' => $uniqueName,
            'no_wa_tujuan' => $uniqueWa,
            'pic_petugas' => [
                ['nama' => $uniqueName, 'no_wa' => $uniqueWa]
            ],
        ]);

        $responseAfterAssign = $this->actingAs($humasUjang)->get(route('dashboard'));
        $responseAfterAssign->assertStatus(200);

        $notifAfter = session('pengajuan_notif', []);
        $this->assertEquals(1, data_get($notifAfter, 'humas.diterima', 0));
    }

    public function test_petugas_pic_column_hidden_for_humas(): void
    {
        $humas = User::factory()->create([
            'is_humas' => true,
            'is_admin' => false,
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'is_humas' => false,
        ]);

        // Humas mengakses kotak masuk - kolom Petugas / PIC harus tersembunyi
        $responseHumas = $this->actingAs($humas)->get(route('kotak-masuk-pengajuan.index'));
        $responseHumas->assertStatus(200);
        $responseHumas->assertDontSee('Petugas / PIC');

        // Admin mengakses kotak masuk - kolom Petugas / PIC harus muncul
        $responseAdmin = $this->actingAs($admin)->get(route('kotak-masuk-pengajuan.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Petugas / PIC');
    }
}
