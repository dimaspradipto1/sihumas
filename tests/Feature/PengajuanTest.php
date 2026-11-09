<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pengajuan;
use Tests\TestCase;

class PengajuanTest extends TestCase
{
    public function test_user_can_see_desain_dan_dokumentasi_option_on_create_page(): void
    {
        $user = User::factory()->create([
            'is_feb' => true,
        ]);

        $response = $this->actingAs($user)->get(route('pengajuan.create'));

        $response->assertStatus(200);
        $response->assertSee('Desain dan Dokumentasi');
    }

    public function test_user_can_submit_pengajuan_with_desain_dan_dokumentasi(): void
    {
        $user = User::factory()->create([
            'is_feb' => true,
        ]);

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Desain dan Dokumentasi',
            'tgl_awal' => now()->toDateString(),
            'tgl_selesai' => now()->addDay()->toDateString(),
            'jam_kegiatan' => '09:00',
            'waktu_selesai' => '12:00',
            'tempat_kegiatan' => 'Aula Universitas Ibnu Sina',
            'unit_kegiatan' => 'FEB',
            'deskripsi_kegiatan' => 'Kegiatan seminar nasional',
        ]);

        $this->assertDatabaseHas('pengajuans', [
            'nama_kegiatan' => 'Desain dan Dokumentasi',
            'user_id' => $user->id,
        ]);
    }
}

