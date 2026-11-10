<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Administrator');
        $response->assertSee('Jadwal Minggu Ini');
        $response->assertSee('Rekapitulasi Kegiatan per Unit');
    }

    public function test_faculty_can_access_dashboard(): void
    {
        $faculty = User::factory()->create([
            'is_admin' => false,
            'is_feb' => true,
        ]);

        $response = $this->actingAs($faculty)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Fakultas Ekonomi dan Bisnis (FEB)');
        $response->assertSee('Pengajuan Terkini Unit Anda');
        $response->assertSee('Alur Layanan Humas');
    }

    public function test_rektorat_can_access_dashboard(): void
    {
        $rektorat = User::factory()->create([
            'is_admin' => false,
            'is_rektorat' => true,
        ]);

        $response = $this->actingAs($rektorat)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Unit Rektorat');
    }

    public function test_humas_role_sees_appropriate_sidebar_and_dashboard(): void
    {
        $humas = User::factory()->create([
            'is_admin' => false,
            'is_humas' => true,
        ]);

        $response = $this->actingAs($humas)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Petugas Humas');
        $response->assertSee('Rekapitulasi Kegiatan per Unit');

        // Sidebar yang HARUS muncul untuk role Humas
        $response->assertSee(route('kotak-masuk-pengajuan.index'));
        $response->assertSee(route('publikasi.index'));
        $response->assertSee(route('laporan-publikasi.index'));
        $response->assertSee(route('pengajuan.index'));

        // Sidebar yang HARUS TERSEMBUNYI untuk role Humas (hanya untuk Super Admin)
        $response->assertDontSee(route('tahun-akademik.index'));
        $response->assertDontSee(route('unit-kegiatan.index'));
        $response->assertDontSee(route('users.index'));
        $response->assertDontSee(route('settings.index'));
        $response->assertDontSee(route('backup.index'));
    }

    public function test_humas_can_access_kotak_masuk_and_rekap_kegiatan(): void
    {
        $humas = User::factory()->create([
            'is_admin' => false,
            'is_humas' => true,
        ]);

        $responseKotakMasuk = $this->actingAs($humas)->get(route('kotak-masuk-pengajuan.index'));
        $responseKotakMasuk->assertStatus(200);

        $responseRekap = $this->actingAs($humas)->get(route('laporan-publikasi.index'));
        $responseRekap->assertStatus(200);
    }
}

