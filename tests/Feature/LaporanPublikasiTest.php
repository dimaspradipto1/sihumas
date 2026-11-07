<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LaporanPublikasiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_authenticated_user_can_access_laporan_publikasi_index(): void
    {
        $user = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->get(route('laporan-publikasi.index'));
        $response->assertStatus(200);
        $this->assertEquals(url('laporan-publikasi/show'), route('laporan-publikasi.show'));
    }

    public function test_authenticated_user_can_access_laporan_publikasi_show(): void
    {
        $user = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->get(route('laporan-publikasi.show'));
        $response->assertStatus(200);
    }
}
