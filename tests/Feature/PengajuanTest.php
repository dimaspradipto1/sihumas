<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PengajuanTest extends TestCase
{
    use DatabaseTransactions;
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

    public function test_user_can_submit_pengajuan_with_uploaded_file_and_png(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'is_fst' => true,
        ]);

        $file = UploadedFile::fake()->create('proposal-kegiatan.pdf', 500, 'application/pdf');
        $png = UploadedFile::fake()->image('banner-kegiatan.png', 800, 600);

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Desain dan Dokumentasi',
            'tgl_awal' => now()->toDateString(),
            'tgl_selesai' => now()->addDay()->toDateString(),
            'jam_kegiatan' => '10:00',
            'waktu_selesai' => '15:00',
            'tempat_kegiatan' => 'Lab Komputer UIS',
            'unit_kegiatan' => 'FST',
            'deskripsi_kegiatan' => 'Pelatihan Desain Grafis',
            'upload_file' => $file,
            'upload_png' => $png,
        ]);

        $response->assertRedirect(route('pengajuan.index'));

        $pengajuan = Pengajuan::where('user_id', $user->id)->latest('id')->first();
        $this->assertNotNull($pengajuan->upload_file);
        $this->assertNotNull($pengajuan->upload_png);

        Storage::disk('public')->assertExists($pengajuan->upload_file);
        Storage::disk('public')->assertExists($pengajuan->upload_png);

        // Verify show page displays file and image links
        $showResponse = $this->actingAs($user)->get(route('pengajuan.show', $pengajuan->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('File Lampiran');
        $showResponse->assertSee('Gambar / Desain (PNG)');
        $showResponse->assertSee(basename($pengajuan->upload_file));
    }
}

