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
            'judul_kegiatan' => 'Seminar Nasional FEB',
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
            'judul_kegiatan' => 'Seminar Nasional FEB',
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
        $showResponse->assertSee('Dokumen Pendukung');
        $showResponse->assertSee('Bahan Materi');
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
            'pic_peran' => ['desain', 'dokumentasi'],
            'pic_catatan' => ['Buat banner horizontal', 'Foto opening dan penutupan'],
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
        $this->assertEquals('desain', $pengajuanFresh->pic_petugas[0]['peran']);
        $this->assertEquals('Buat banner horizontal', $pengajuanFresh->pic_petugas[0]['catatan']);
        $this->assertEquals('Ujang', $pengajuanFresh->pic_petugas[1]['nama']);
        $this->assertEquals('dokumentasi', $pengajuanFresh->pic_petugas[1]['peran']);
        $this->assertEquals('Foto opening dan penutupan', $pengajuanFresh->pic_petugas[1]['catatan']);

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

    public function test_humas_role_does_not_see_verifikasi_dan_penugasan_card(): void
    {
        $humas = User::factory()->create([
            'is_admin' => false,
            'is_humas' => true,
            'name' => 'PETUGAS_HUMAS_' . uniqid(),
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $admin->id,
            'nama_kegiatan' => 'Dies Natalis UIS ke-20',
            'tgl_awal' => now()->toDateString(),
            'tgl_selesai' => now()->addDay()->toDateString(),
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '12:00',
            'tempat_kegiatan' => 'Auditorium UIS',
            'unit_kegiatan' => 'Rektorat',
            'deskripsi_kegiatan' => 'Rangkaian dies natalis',
            'status' => 'diterima',
            'petugas_ditugaskan' => $humas->name,
        ]);

        // Verifikasi untuk Admin: kartu "Verifikasi & Penugasan" HARUS muncul
        $adminResponse = $this->actingAs($admin)->get(route('pengajuan.edit', $pengajuan->id));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Verifikasi & Penugasan', false);

        // Verifikasi untuk Humas: kartu "Verifikasi & Penugasan" HARUS TERSEMBUNYI
        $humasResponse = $this->actingAs($humas)->get(route('pengajuan.edit', $pengajuan->id));
        $humasResponse->assertStatus(200);
        $humasResponse->assertDontSee('Verifikasi & Penugasan', false);
        $humasResponse->assertSee('Detail Pengajuan (Review)');
        $humasResponse->assertSee('Kembali ke Kotak Masuk');
    }

    public function test_user_can_submit_desain_only_without_schedule_and_location(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'is_humas' => false,
            'is_fst' => true,
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $deadline = now()->addDays(3)->toDateString();

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Desain',
            'tgl_awal' => $deadline,
            'deskripsi_kegiatan' => 'Poster Seminar Nasional AI, ukuran A3, warna hijau UIS.',
            'judul_kegiatan' => 'Poster Seminar Nasional AI',
            'unit_kegiatan' => 'FST',
        ]);

        $response->assertRedirect(route('pengajuan.index'));

        $this->assertDatabaseHas('pengajuans', [
            'user_id' => $user->id,
            'nama_kegiatan' => 'Desain',
            'tgl_awal' => $deadline,
            'tgl_selesai' => $deadline,
            'jam_kegiatan' => null,
            'waktu_selesai' => null,
            'tempat_kegiatan' => null,
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->where('nama_kegiatan', 'Desain')->firstOrFail();

        $showResponse = $this->actingAs($user)->get(route('pengajuan.show', $pengajuan->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Deadline Desain');
        $showResponse->assertSee('Brief Desain');
        $showResponse->assertDontSee('Waktu Kegiatan');
        $showResponse->assertDontSee('Tempat Kegiatan');

        $editResponse = $this->actingAs($admin)->get(route('pengajuan.edit', $pengajuan->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Deadline Desain');
        $editResponse->assertSee('1. PIC Desain');
        $editResponse->assertDontSee('2. PIC Dokumentasi');
    }

    public function test_user_can_submit_publikasi_only_without_schedule_and_location(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'is_humas' => false,
            'is_fst' => true,
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $humas = User::factory()->create([
            'is_admin' => false,
            'is_humas' => true,
            'name' => 'HUMAS_PUB_' . uniqid(),
        ]);

        $targetTayang = now()->addDays(2)->toDateString();

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Publikasi',
            'tgl_awal' => $targetTayang,
            'deskripsi_kegiatan' => 'Rilis berita prestasi mahasiswa FST juara lomba UI/UX Nasional.',
            'judul_kegiatan' => 'Rilis Berita Prestasi Mahasiswa FST',
            'unit_kegiatan' => 'FST',
        ]);

        $response->assertRedirect(route('pengajuan.index'));

        $this->assertDatabaseHas('pengajuans', [
            'user_id' => $user->id,
            'nama_kegiatan' => 'Publikasi',
            'tgl_awal' => $targetTayang,
            'tgl_selesai' => $targetTayang,
            'jam_kegiatan' => null,
            'waktu_selesai' => null,
            'tempat_kegiatan' => null,
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->where('nama_kegiatan', 'Publikasi')->firstOrFail();

        $showResponse = $this->actingAs($user)->get(route('pengajuan.show', $pengajuan->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Target Publikasi');
        $showResponse->assertSee('Brief Publikasi');
        $showResponse->assertDontSee('Waktu Kegiatan');
        $showResponse->assertDontSee('Tempat Kegiatan');

        $editResponse = $this->actingAs($admin)->get(route('pengajuan.edit', $pengajuan->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Target Publikasi');
        $editResponse->assertSee('1. PIC Publikasi');
        $editResponse->assertDontSee('1. PIC Desain');
        $editResponse->assertDontSee('2. PIC Dokumentasi');

        $pengajuan->update([
            'status' => 'diterima',
            'petugas_ditugaskan' => $humas->name,
            'pic_petugas' => [['nama' => $humas->name, 'no_wa' => '081234567890', 'peran' => 'desain', 'catatan' => 'Tayangkan di web']],
        ]);

        $publikasiEdit = $this->actingAs($humas)->get(route('publikasi.edit', $pengajuan->publikasi->id));
        $publikasiEdit->assertStatus(200);
        $publikasiEdit->assertSee('Narasi / Rilis Berita Publikasi');
        $publikasiEdit->assertSee('Link Publikasi');
        $publikasiEdit->assertDontSee('Link GDrive (Desain atau Flayer)');
    }

    public function test_regular_user_can_edit_and_delete_own_pengajuan_only(): void
    {
        $owner = User::factory()->create([
            'is_admin' => false,
            'is_humas' => false,
            'is_users' => true,
        ]);

        $otherUser = User::factory()->create([
            'is_admin' => false,
            'is_humas' => false,
            'is_users' => true,
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $owner->id,
            'nama_kegiatan' => 'Desain dan Dokumentasi',
            'tgl_awal' => now()->addDays(2)->toDateString(),
            'tgl_selesai' => now()->addDays(2)->toDateString(),
            'jam_kegiatan' => '09:00',
            'waktu_selesai' => '11:00',
            'tempat_kegiatan' => 'Auditorium',
            'unit_kegiatan' => 'BAAK',
            'deskripsi_kegiatan' => 'Brief awal',
            'status' => 'pending',
        ]);

        // Owner sees edit and delete actions in DataTables AJAX response
        $dtResponse = $this->actingAs($owner)->getJson(route('pengajuan.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $dtResponse->assertStatus(200);
        $actionHtml = $dtResponse->json('data.0.action') ?? '';
        $this->assertStringContainsString(route('pengajuan.edit', $pengajuan->id), $actionHtml);
        $this->assertStringContainsString(route('pengajuan.destroy', $pengajuan->id), $actionHtml);

        // Owner can view edit page and update own pengajuan
        $this->actingAs($owner)->get(route('pengajuan.edit', $pengajuan->id))->assertStatus(200);
        $updateResponse = $this->actingAs($owner)->put(route('pengajuan.update', $pengajuan->id), [
            'nama_kegiatan' => 'Dokumentasi',
            'tgl_awal' => $pengajuan->tgl_awal,
            'tgl_selesai' => $pengajuan->tgl_selesai,
            'jam_kegiatan' => '10:00',
            'waktu_selesai' => '12:00',
            'tempat_kegiatan' => 'Ruang Rapat',
            'deskripsi_kegiatan' => 'Brief diperbarui',
        ]);
        $updateResponse->assertRedirect(route('pengajuan.index'));
        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'nama_kegiatan' => 'Dokumentasi',
            'tempat_kegiatan' => 'Ruang Rapat',
        ]);

        // Other user is forbidden from editing, updating, or deleting owner's pengajuan
        $this->actingAs($otherUser)->get(route('pengajuan.edit', $pengajuan->id))->assertStatus(403);
        $this->actingAs($otherUser)->put(route('pengajuan.update', $pengajuan->id), [
            'nama_kegiatan' => 'Publikasi',
        ])->assertStatus(403);
        $this->actingAs($otherUser)->delete(route('pengajuan.destroy', $pengajuan->id))->assertStatus(403);

        // When pengajuan is completed/diterima, edit and delete buttons are hidden for owner
        $completedPengajuan = Pengajuan::create([
            'user_id' => $owner->id,
            'nama_kegiatan' => 'Desain',
            'tgl_awal' => now()->addDays(4)->toDateString(),
            'tgl_selesai' => now()->addDays(4)->toDateString(),
            'unit_kegiatan' => 'BAAK',
            'deskripsi_kegiatan' => 'Sudah selesai',
            'status' => 'diterima',
        ]);
        $dtCompleted = $this->actingAs($owner)->getJson(route('pengajuan.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $dtCompleted->assertStatus(200);
        $completedRow = collect($dtCompleted->json('data'))->firstWhere('id', $completedPengajuan->id);
        $this->assertNotNull($completedRow);
        $this->assertStringNotContainsString(route('pengajuan.edit', $completedPengajuan->id), $completedRow['action']);
        $this->assertStringNotContainsString('form-action-delete', $completedRow['action']);

        // Owner can delete own pending pengajuan
        $deleteResponse = $this->actingAs($owner)->delete(route('pengajuan.destroy', $pengajuan->id));
        $deleteResponse->assertRedirect(route('pengajuan.index'));
        $this->assertDatabaseMissing('pengajuans', ['id' => $pengajuan->id]);
    }

    public function test_user_can_submit_desain_dan_publikasi_with_combined_features(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'is_humas' => false,
            'is_users' => true,
        ]);

        $humas = User::factory()->create([
            'is_admin' => false,
            'is_humas' => true,
            'name' => 'HUMAS_BOTH_' . uniqid(),
        ]);

        $this->actingAs($user)->get(route('pengajuan.create'))
            ->assertStatus(200)
            ->assertSee('Desain dan Publikasi');

        $deadline = now()->addDays(3)->toDateString();
        $targetPublikasi = now()->addDays(5)->toDateString();

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Desain dan Publikasi',
            'tgl_awal' => $deadline,
            'tgl_selesai' => $targetPublikasi,
            'judul_kegiatan' => 'Wisuda Universitas Ibnu Sina',
            'deskripsi_kegiatan' => 'Poster dan rilis berita wisuda UIS.',
            'unit_kegiatan' => 'BAAK',
        ]);
        $response->assertRedirect(route('pengajuan.index'));

        $pengajuan = Pengajuan::where('user_id', $user->id)
            ->where('nama_kegiatan', 'Desain dan Publikasi')
            ->firstOrFail();

        $this->assertEquals($deadline, $pengajuan->tgl_awal);
        $this->assertEquals($targetPublikasi, $pengajuan->tgl_selesai);

        $showResponse = $this->actingAs($user)->get(route('pengajuan.show', $pengajuan->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Deadline Desain:');
        $showResponse->assertSee('Target Publikasi:');
        $showResponse->assertSee('Brief Desain &amp; Publikasi', false);
        $showResponse->assertSee('Link Desain');

        $pengajuan->update([
            'status' => 'diterima',
            'petugas_ditugaskan' => $humas->name,
            'pic_petugas' => [['nama' => $humas->name, 'no_wa' => '081234567890', 'peran' => 'desain', 'catatan' => 'Desain + rilis']],
        ]);

        $pubEdit = $this->actingAs($humas)->get(route('publikasi.edit', $pengajuan->publikasi->id));
        $pubEdit->assertStatus(200);
        $pubEdit->assertSee('Narasi / Rilis Berita Publikasi');
        $pubEdit->assertSee('Link GDrive (Desain atau Flayer)');
        $pubEdit->assertSee('Link Publikasi');
    }

    public function test_multiple_active_admin_whatsapp_numbers_appear_after_pengajuan_submit(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false, 'is_users' => true]);

        $settingResp = $this->actingAs($admin)->post(route('settings.update'), [
            'admin_wa_nama' => ['Admin 1', 'Admin 2 (Nonaktif)', 'Admin 3'],
            'admin_wa_nomor' => ['08118989777', '081200001111', '6282283736481'],
            'admin_wa_active' => ['1', '0', '1'],
            'wa_message_template' => 'Halo Admin, ada pengajuan {nama_kegiatan} oleh {nama_user}.',
        ]);
        $settingResp->assertRedirect(route('settings.index'));

        $activeList = \App\Models\Setting::getActiveAdminWhatsapps();
        $this->assertCount(2, $activeList);
        $this->assertEquals('628118989777', $activeList[0]['no_wa']);
        $this->assertEquals('6282283736481', $activeList[1]['no_wa']);

        $storeResp = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Desain',
            'judul_kegiatan' => 'Poster Seminar Nasional',
            'tgl_awal' => now()->addDays(2)->toDateString(),
            'deskripsi_kegiatan' => 'Poster seminar.',
        ]);
        $storeResp->assertRedirect(route('pengajuan.index'));

        $waAdminList = session('wa_admin_list');
        $this->assertCount(2, $waAdminList);
        $this->assertEquals('Admin 1', $waAdminList[0]['nama']);
        $this->assertEquals('628118989777', $waAdminList[0]['no_wa']);
        $this->assertEquals('Admin 3', $waAdminList[1]['nama']);
        $this->assertEquals('6282283736481', $waAdminList[1]['no_wa']);
    }

    public function test_pic_sections_match_selected_jenis_pengajuan(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_humas' => false, 'is_users' => false]);
        $user = User::factory()->create(['is_admin' => false, 'is_humas' => false, 'is_users' => true]);

        $cases = [
            'Desain' => ['see' => ['1. PIC Desain', 'id="kontainerPicDesain"'], 'dontSee' => ['id="kontainerPicDokumentasi"', 'id="kontainerPicPublikasi"', 'id="kontainerPicGeneral"']],
            'Desain dan Dokumentasi' => ['see' => ['1. PIC Desain', '2. PIC Dokumentasi', 'id="kontainerPicDesain"', 'id="kontainerPicDokumentasi"'], 'dontSee' => ['id="kontainerPicPublikasi"', 'id="kontainerPicGeneral"']],
            'Publikasi' => ['see' => ['1. PIC Publikasi', 'id="kontainerPicPublikasi"'], 'dontSee' => ['id="kontainerPicDesain"', 'id="kontainerPicDokumentasi"', 'id="kontainerPicGeneral"']],
            'Desain dan Publikasi' => ['see' => ['1. PIC Desain', '2. PIC Publikasi', 'id="kontainerPicDesain"', 'id="kontainerPicPublikasi"'], 'dontSee' => ['id="kontainerPicDokumentasi"', 'id="kontainerPicGeneral"']],
            'DOKUMENTASI DAN PUBLIKASI' => ['see' => ['1. PIC Dokumentasi', '2. PIC Publikasi', 'id="kontainerPicDokumentasi"', 'id="kontainerPicPublikasi"'], 'dontSee' => ['id="kontainerPicDesain"', 'id="kontainerPicGeneral"']],
            'Desain, Dokumentasi dan Publikasi' => ['see' => ['1. PIC Desain', '2. PIC Dokumentasi', '3. PIC Publikasi', 'id="kontainerPicDesain"', 'id="kontainerPicDokumentasi"', 'id="kontainerPicPublikasi"'], 'dontSee' => ['id="kontainerPicGeneral"']],
            'Liputan Khusus Custom' => ['see' => ['1. PIC General', 'id="kontainerPicGeneral"'], 'dontSee' => ['id="kontainerPicDesain"', 'id="kontainerPicDokumentasi"', 'id="kontainerPicPublikasi"']],
        ];

        foreach ($cases as $jenis => $expected) {
            $pengajuan = Pengajuan::create([
                'user_id' => $user->id,
                'unit_kegiatan' => 'BAAK',
                'nama_kegiatan' => $jenis,
                'tgl_awal' => now()->addDays(2)->toDateString(),
                'tgl_selesai' => now()->addDays(2)->toDateString(),
                'status' => 'pending',
            ]);

            $resp = $this->actingAs($admin)->get(route('pengajuan.edit', $pengajuan->id));
            $resp->assertStatus(200);
            foreach ($expected['see'] as $text) {
                $resp->assertSee($text, false);
            }
            foreach ($expected['dontSee'] as $text) {
                $resp->assertDontSee($text, false);
            }
        }
    }

    public function test_judul_kegiatan_is_required_and_kotak_masuk_falls_back_to_deskripsi(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_humas' => false, 'is_users' => false]);
        $user = User::factory()->create(['is_admin' => false, 'is_humas' => false, 'is_users' => true]);

        // Validasi required judul_kegiatan
        $invalidResp = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Desain',
            'tgl_awal' => now()->addDays(2)->toDateString(),
            'deskripsi_kegiatan' => 'Poster tanpa nama kegiatan',
        ]);
        $invalidResp->assertSessionHasErrors('judul_kegiatan');

        // Pengajuan baru dengan judul_kegiatan & pengajuan lama tanpa judul_kegiatan (fallback ke deskripsi)
        $withJudul = Pengajuan::create([
            'user_id' => $user->id,
            'unit_kegiatan' => 'BAAK',
            'nama_kegiatan' => 'Desain',
            'judul_kegiatan' => 'Judul Kegiatan Baru',
            'deskripsi_kegiatan' => 'Deskripsi singkat',
            'tgl_awal' => now()->addDays(2)->toDateString(),
            'tgl_selesai' => now()->addDays(2)->toDateString(),
            'status' => 'pending',
        ]);
        \App\Models\KotakMasukPengajuan::create(['pengajuan_id' => $withJudul->id]);

        $legacyWithoutJudul = Pengajuan::create([
            'user_id' => $user->id,
            'unit_kegiatan' => 'BAAK',
            'nama_kegiatan' => 'Dokumentasi',
            'judul_kegiatan' => null,
            'deskripsi_kegiatan' => '<p>Kegiatan Lama Dari Deskripsi</p>',
            'tgl_awal' => now()->addDays(3)->toDateString(),
            'tgl_selesai' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ]);
        \App\Models\KotakMasukPengajuan::create(['pengajuan_id' => $legacyWithoutJudul->id]);

        $dtResponse = $this->actingAs($admin)->getJson(route('kotak-masuk-pengajuan.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $dtResponse->assertStatus(200);
        $jsonString = json_encode($dtResponse->json());
        $this->assertStringContainsString('Judul Kegiatan Baru', $jsonString);
        $this->assertStringContainsString('Kegiatan Lama Dari Deskripsi', $jsonString);
    }
}

