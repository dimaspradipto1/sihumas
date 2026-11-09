<?php

namespace Tests\Feature;

use App\Models\Pengajuan;
use App\Models\Publikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PublikasiTest extends TestCase
{
    use DatabaseTransactions;
    public function test_publikasi_auto_generates_unique_slug_and_route_binding_works_with_slug_and_id(): void
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'nama_kegiatan' => 'Workshop UI UX Modern',
            'tgl_awal' => '2026-11-10',
            'tgl_selesai' => '2026-11-12',
            'jam_kegiatan' => '09:00',
            'waktu_selesai' => '12:00',
            'unit_kegiatan' => 'Fakultas Sains dan Teknologi',
            'tempat_kegiatan' => 'Auditorium UIS',
            'status' => 'disetujui',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $user->id,
            'pengajuan_id' => $pengajuan->id,
            'tahun_akademik_id' => null, // explicitly test null tahun_akademik
        ]);

        $this->assertNotEmpty($publikasi->slug);
        $this->assertEquals('workshop-ui-ux-modern', $publikasi->slug);
        $this->assertEquals(url('/publikasi/workshop-ui-ux-modern'), route('publikasi.show', $publikasi));

        // Test show by slug
        $responseBySlug = $this->actingAs($user)->get(route('publikasi.show', $publikasi));
        $responseBySlug->assertStatus(200);
        $responseBySlug->assertSee('Workshop UI UX Modern');
        $responseBySlug->assertSee('-'); // Fallback for null tahun_akademik

        // Test backwards compatibility: access by numeric ID
        $responseById = $this->actingAs($user)->get('/publikasi/' . $publikasi->id);
        $responseById->assertStatus(200);
        $responseById->assertSee('Workshop UI UX Modern');

        // Test edit view by slug
        $responseEdit = $this->actingAs($user)->get(route('publikasi.edit', $publikasi));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Workshop UI UX Modern');

        // Test unique slug collision handling
        $duplicatePublikasi = Publikasi::create([
            'user_id' => $user->id,
            'pengajuan_id' => $pengajuan->id,
        ]);
        $this->assertEquals('workshop-ui-ux-modern-2', $duplicatePublikasi->slug);
    }

    public function test_publikasi_with_null_slug_falls_back_to_id_gracefully(): void
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $publikasi = new Publikasi();
        $publikasi->id = 999;
        $publikasi->slug = null;

        // Must not throw UrlGenerationException
        $url = route('publikasi.show', $publikasi);
        $this->assertEquals(url('/publikasi/999'), $url);

        $urlEdit = route('publikasi.edit', $publikasi);
        $this->assertEquals(url('/publikasi/999/edit'), $urlEdit);
    }
}
