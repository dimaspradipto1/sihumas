<?php

namespace Tests\Feature;

use App\Models\LaporanPublikasi;
use App\Models\Pengajuan;
use App\Models\Publikasi;
use App\Models\Triwulan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TriwulanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_access_triwulan_menu_and_manage_records(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('triwulan.index'));
        $response->assertStatus(200);
        $response->assertSee('Triwulan (TW)');

        $createResponse = $this->actingAs($admin)->get(route('triwulan.create'));
        $createResponse->assertStatus(200);

        $storeResponse = $this->actingAs($admin)->post(route('triwulan.store'), [
            'kode' => 'TW5',
            'nama' => 'TW5 (Khusus)',
            'bulan_mulai' => 9,
            'bulan_selesai' => 11,
            'is_active' => false,
        ]);
        $storeResponse->assertRedirect(route('triwulan.index'));
        $this->assertDatabaseHas('triwulans', ['kode' => 'TW5', 'nama' => 'TW5 (Khusus)']);
    }

    public function test_triwulan_auto_activates_according_to_month(): void
    {
        $this->assertEquals('TW1', Triwulan::resolveForMonth(9)?->kode);
        $this->assertEquals('TW1', Triwulan::resolveForMonth(11)?->kode);
        $this->assertEquals('TW2', Triwulan::resolveForMonth(12)?->kode);
        $this->assertEquals('TW2', Triwulan::resolveForMonth(1)?->kode);
        $this->assertEquals('TW2', Triwulan::resolveForMonth(2)?->kode);
        $this->assertEquals('TW3', Triwulan::resolveForMonth(3)?->kode);
        $this->assertEquals('TW3', Triwulan::resolveForMonth(5)?->kode);
        $this->assertEquals('TW4', Triwulan::resolveForMonth(6)?->kode);
        $this->assertEquals('TW4', Triwulan::resolveForMonth(8)?->kode);

        $synced = Triwulan::syncActiveByCurrentMonth(3);
        $this->assertEquals('TW3', $synced?->kode);
        $this->assertTrue((bool) $synced?->fresh()->is_active);
    }

    public function test_laporan_publikasi_filter_by_tw_and_year_including_cross_year_tw2(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $inTw2Pengajuan = Pengajuan::create([
            'user_id' => $admin->id,
            'nama_kegiatan' => 'Kegiatan TW2 Januari',
            'tgl_awal' => '2027-01-15',
            'tgl_selesai' => '2027-01-15',
            'jam_kegiatan' => '09:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'unit_kegiatan' => 'FST',
            'status' => 'diterima',
        ]);
        $pubInTw2 = Publikasi::create(['user_id' => $admin->id, 'pengajuan_id' => $inTw2Pengajuan->id]);
        LaporanPublikasi::create([
            'user_id' => $admin->id,
            'publikasi_id' => $pubInTw2->id,
            'pengajuan_id' => $inTw2Pengajuan->id,
            'tgl_awal' => '2027-01-15',
            'tgl_selesai' => '2027-01-15',
        ]);

        $outsidePengajuan = Pengajuan::create([
            'user_id' => $admin->id,
            'nama_kegiatan' => 'Kegiatan TW3 April',
            'tgl_awal' => '2027-04-10',
            'tgl_selesai' => '2027-04-10',
            'jam_kegiatan' => '09:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'unit_kegiatan' => 'FST',
            'status' => 'diterima',
        ]);
        $pubOutside = Publikasi::create(['user_id' => $admin->id, 'pengajuan_id' => $outsidePengajuan->id]);
        LaporanPublikasi::create([
            'user_id' => $admin->id,
            'publikasi_id' => $pubOutside->id,
            'pengajuan_id' => $outsidePengajuan->id,
            'tgl_awal' => '2027-04-10',
            'tgl_selesai' => '2027-04-10',
        ]);

        $indexResp = $this->actingAs($admin)->get(route('laporan-publikasi.index'));
        $indexResp->assertStatus(200);
        $indexResp->assertSee('Pilih Triwulan (TW)');

        $showResp = $this->actingAs($admin)->get(route('laporan-publikasi.show', [
            'filter_type' => 'tw',
            'tw' => 'TW2',
            'tahun' => 2026,
        ]));
        $showResp->assertStatus(200);
        $showResp->assertSee('Kegiatan TW2 Januari');
        $showResp->assertDontSee('Kegiatan TW3 April');
    }

    public function test_triwulan_active_status_badge_can_be_toggled_directly(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $tw = Triwulan::where('kode', 'TW1')->firstOrFail();

        // Ensure toggling active -> inactive works in 1 click
        $tw->refresh();
        if (!$tw->is_active) {
            $tw->update(['is_active' => true]);
        }
        $res1 = $this->actingAs($admin)->post(route('triwulan.set-active', $tw->id));
        $res1->assertRedirect();
        $this->assertFalse((bool) $tw->fresh()->is_active);

        // Ensure toggling inactive -> active works in 1 click
        $res2 = $this->actingAs($admin)->post(route('triwulan.set-active', $tw->id));
        $res2->assertRedirect();
        $this->assertTrue((bool) $tw->fresh()->is_active);
    }
}
