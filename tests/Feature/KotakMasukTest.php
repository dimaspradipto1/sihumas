<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pengajuan;
use App\Models\KotakMasukPengajuan;
use App\Models\TahunAkademik;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KotakMasukTest extends TestCase
{
    use DatabaseTransactions;
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

    public function test_kotak_masuk_deadline_badges_and_filters(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_humas' => false]);
        $user = User::factory()->create(['is_admin' => false, 'is_humas' => false, 'is_users' => true]);

        // 1. Overdue & belum selesai
        $overdue = Pengajuan::create([
            'user_id' => $user->id,
            'unit_kegiatan' => 'BAAK',
            'nama_kegiatan' => 'Dokumentasi',
            'judul_kegiatan' => 'Kegiatan Overdue Test',
            'tgl_awal' => now()->subDays(2)->toDateString(),
            'tgl_selesai' => now()->subDay()->toDateString(),
            'status' => 'diterima',
        ]);
        KotakMasukPengajuan::create(['pengajuan_id' => $overdue->id]);

        // 2. Deadline hari ini & belum selesai
        $todayTask = Pengajuan::create([
            'user_id' => $user->id,
            'unit_kegiatan' => 'BAAK',
            'nama_kegiatan' => 'Desain',
            'judul_kegiatan' => 'Kegiatan Hari Ini Test',
            'tgl_awal' => now()->toDateString(),
            'tgl_selesai' => now()->toDateString(),
            'status' => 'pending',
        ]);
        KotakMasukPengajuan::create(['pengajuan_id' => $todayTask->id]);

        // 3. Sudah selesai (disetujui) walaupun tanggal lewat
        $completed = Pengajuan::create([
            'user_id' => $user->id,
            'unit_kegiatan' => 'BAAK',
            'nama_kegiatan' => 'Publikasi',
            'judul_kegiatan' => 'Kegiatan Selesai Valid Test',
            'tgl_awal' => now()->subDays(5)->toDateString(),
            'tgl_selesai' => now()->subDays(4)->toDateString(),
            'status' => 'diterima',
        ]);
        $completed->publikasi()->create([
            'user_id' => $user->id,
            'status_validasi' => 'disetujui',
        ]);
        KotakMasukPengajuan::create(['pengajuan_id' => $completed->id]);

        // Filter: belum_selesai
        $resBelum = $this->actingAs($admin)->getJson(route('kotak-masuk-pengajuan.index', ['deadline_filter' => 'belum_selesai']), ['X-Requested-With' => 'XMLHttpRequest']);
        $resBelum->assertStatus(200);
        $jsonBelum = json_encode($resBelum->json());
        $this->assertStringContainsString('Kegiatan Overdue Test', $jsonBelum);
        $this->assertStringContainsString('Kegiatan Hari Ini Test', $jsonBelum);
        $this->assertStringContainsString('Lewat Deadline', $jsonBelum);
        $this->assertStringContainsString('Deadline Hari Ini', $jsonBelum);
        $this->assertStringNotContainsString('Kegiatan Selesai Valid Test', $jsonBelum);

        // Filter: lewat_deadline
        $resOverdue = $this->actingAs($admin)->getJson(route('kotak-masuk-pengajuan.index', ['deadline_filter' => 'lewat_deadline']), ['X-Requested-With' => 'XMLHttpRequest']);
        $resOverdue->assertStatus(200);
        $jsonOverdue = json_encode($resOverdue->json());
        $this->assertStringContainsString('Kegiatan Overdue Test', $jsonOverdue);
        $this->assertStringNotContainsString('Kegiatan Hari Ini Test', $jsonOverdue);
        $this->assertStringNotContainsString('Kegiatan Selesai Valid Test', $jsonOverdue);

        // Filter: selesai
        $resSelesai = $this->actingAs($admin)->getJson(route('kotak-masuk-pengajuan.index', ['deadline_filter' => 'selesai']), ['X-Requested-With' => 'XMLHttpRequest']);
        $resSelesai->assertStatus(200);
        $jsonSelesai = json_encode($resSelesai->json());
        $this->assertStringContainsString('Kegiatan Selesai Valid Test', $jsonSelesai);
        $this->assertStringNotContainsString('Kegiatan Overdue Test', $jsonSelesai);
    }
}
