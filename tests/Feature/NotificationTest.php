<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengajuan_notification_badge_disappears_when_visited(): void
    {
        $febUser = User::factory()->create([
            'is_feb' => true,
            'is_admin' => false,
            'is_humas' => false,
            'pengajuan_read_at' => null,
        ]);

        // Buat pengajuan berstatus diterima
        $pengajuan = Pengajuan::create([
            'user_id' => $febUser->id,
            'unit_kegiatan' => 'FEB',
            'nama_kegiatan' => 'Seminar FEB',
            'tgl_awal' => '2026-11-20',
            'tgl_selesai' => '2026-11-20',
            'jam_kegiatan' => '09:00',
            'waktu_selesai' => '12:00',
            'tempat_kegiatan' => 'Aula FEB',
            'status' => 'diterima',
        ]);

        // Sebelum dibuka, di dashboard notifikasi diterima harus bernilai 1
        $dashResponse = $this->actingAs($febUser)->get(route('dashboard'));
        $dashResponse->assertStatus(200);

        $notifBefore = session('pengajuan_notif', []);
        $this->assertEquals(1, data_get($notifBefore, 'feb.diterima', 0));

        // Saat user mengklik / membuka halaman Pengajuan Kegiatan
        $pengajuanResponse = $this->actingAs($febUser)->get(route('pengajuan.index'));
        $pengajuanResponse->assertStatus(200);

        // Session notif untuk feb sekarang harus 0 (sudah dibaca)
        $notifAfter = session('pengajuan_notif', []);
        $this->assertEquals(0, data_get($notifAfter, 'feb.diterima', 0));

        // Dan saat kembali ke dashboard, notifikasi tetap 0 (tidak muncul kembali)
        $dashAgainResponse = $this->actingAs($febUser)->get(route('dashboard'));
        $dashAgainResponse->assertStatus(200);
        $notifAgain = session('pengajuan_notif', []);
        $this->assertEquals(0, data_get($notifAgain, 'feb.diterima', 0));
    }

    public function test_ajax_mark_as_read_endpoint_clears_notification(): void
    {
        $user = User::factory()->create([
            'is_feb' => true,
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->postJson(route('notifications.mark-as-read'), [
            'type' => 'pengajuan'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $user->refresh();
        $this->assertNotNull($user->pengajuan_read_at);
    }
}

