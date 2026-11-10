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

    public function test_admin_can_assign_multiple_pics_and_their_whatsapp_numbers(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'is_fst' => true,
            'no_wa' => '081234567890',
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'nama_kegiatan' => 'Dokumentasi',
            'tgl_awal' => now()->toDateString(),
            'tgl_selesai' => now()->addDay()->toDateString(),
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'unit_kegiatan' => 'FST',
            'deskripsi_kegiatan' => 'Dokumentasi wisuda',
            'status' => 'pending',
        ]);

        $editResponse = $this->actingAs($admin)->get(route('pengajuan.edit', $pengajuan->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Petugas / PIC yang Ditugaskan');
        $editResponse->assertSee('Tambah PIC');

        $updateResponse = $this->actingAs($admin)->put(route('pengajuan.update', $pengajuan->id), [
            'status' => 'diterima',
            'pic_nama' => ['Habib', 'Ujang'],
            'pic_no_wa' => ['081298765432', '085298765432'],
            'alasan_ditolak' => 'Pengajuan diterima dan petugas telah ditugaskan.',
            'tgl_awal' => $pengajuan->tgl_awal,
            'tgl_selesai' => $pengajuan->tgl_selesai,
        ]);

        $updateResponse->assertRedirect(route('kotak-masuk-pengajuan.index'));
        $updateResponse->assertSessionHas('wa_pic_list');

        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'status' => 'diterima',
            'petugas_ditugaskan' => 'Habib, Ujang',
            'no_wa_tujuan' => '081298765432, 085298765432',
        ]);

        $pengajuanFresh = $pengajuan->fresh();
        $this->assertCount(2, $pengajuanFresh->pic_petugas);
        $this->assertEquals('Habib', $pengajuanFresh->pic_petugas[0]['nama']);
        $this->assertEquals('Ujang', $pengajuanFresh->pic_petugas[1]['nama']);

        $showResponse = $this->actingAs($admin)->get(route('pengajuan.show', $pengajuan->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Habib');
        $showResponse->assertSee('Ujang');
        $showResponse->assertSee('081298765432');
        $showResponse->assertSee('085298765432');

        $indexResponse = $this->actingAs($admin)->get(route('kotak-masuk-pengajuan.index'));
        $indexResponse->assertStatus(200);
    }

    public function test_edit_pengajuan_only_displays_users_with_role_humas(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'is_humas' => false,
            'name' => 'ADMIN_NON_HUMAS_' . uniqid(),
        ]);

        $humas = User::factory()->create([
            'is_admin' => false,
            'is_humas' => true,
            'name' => 'PETUGAS_HUMAS_' . uniqid(),
            'no_wa' => '081234567890',
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $admin->id,
            'nama_kegiatan' => 'Rapat Humas',
            'tgl_awal' => now()->toDateString(),
            'tgl_selesai' => now()->addDay()->toDateString(),
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'unit_kegiatan' => 'FST',
            'deskripsi_kegiatan' => 'Deskripsi rapat',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('pengajuan.edit', $pengajuan->id));
        $response->assertStatus(200);

        $petugasHumas = $response->viewData('petugasHumas');
        $this->assertTrue($petugasHumas->contains('id', $humas->id));
        $this->assertFalse($petugasHumas->contains('id', $admin->id));
    }
}

