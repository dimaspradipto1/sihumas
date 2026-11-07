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
}

