<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UnitKegiatan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UnitKegiatanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_access_unit_kegiatan_index(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('unit-kegiatan.index'));
        $response->assertStatus(200);
        $response->assertSee('unit kerja');
    }

    public function test_admin_can_create_unit_kegiatan_with_kategori(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('unit-kegiatan.store'), [
            'kategori' => 'BAAK',
            'unit_kegiatan' => 'BAAK - Kabid Akademik Test',
        ]);

        $response->assertRedirect(route('unit-kegiatan.index'));
        $this->assertDatabaseHas('unit_kegiatans', [
            'kategori' => 'BAAK',
            'unit_kegiatan' => 'BAAK - Kabid Akademik Test',
        ]);
    }

    public function test_user_create_page_renders_optgroups_for_units(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('users.create'));
        $response->assertStatus(200);
        $response->assertSee('<optgroup', false);
        $response->assertSee('BAAK');
        $response->assertSee('BKAK');
        $response->assertSee('BAUK');
    }
}
