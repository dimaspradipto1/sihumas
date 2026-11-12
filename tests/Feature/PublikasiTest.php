<?php

namespace Tests\Feature;

use App\Models\LaporanPublikasi;
use App\Models\Pengajuan;
use App\Models\Publikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_publikasi_update_strips_html_tags_and_handles_multiple_links(): void
    {
        $admin = User::factory()->create(['is_admin' => 1]);

        $pengajuan = Pengajuan::create([
            'user_id' => $admin->id,
            'nama_kegiatan' => 'Seminar Nasional Publikasi',
            'unit_kegiatan' => 'Fakultas Sains dan Teknologi',
            'tempat_kegiatan' => 'Auditorium UIS',
            'tgl_awal' => '2026-11-15',
            'tgl_selesai' => '2026-11-15',
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '12:00',
            'status' => 'diterima',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $admin->id,
            'pengajuan_id' => $pengajuan->id,
        ]);

        LaporanPublikasi::create([
            'user_id' => $admin->id,
            'publikasi_id' => $publikasi->id,
            'pengajuan_id' => $pengajuan->id,
            'tgl_awal' => $pengajuan->tgl_awal,
            'tgl_selesai' => $pengajuan->tgl_selesai,
        ]);

        $response = $this->actingAs($admin)->put(route('publikasi.update', $publikasi), [
            'upload_laporan' => '<p>Kegiatan seminar <strong>berjalan sukses</strong> dan dihadiri 100 peserta.<br>Terima kasih.</p>',
            'link_dokumentasi' => 'https://drive.google.com/drive/folders/test12345',
            'link_publikasi' => [
                'https://uis.ac.id/berita/seminar-nasional',
                'https://instagram.com/p/seminar-uis',
            ],
        ]);

        $response->assertRedirect(route('publikasi.index'));

        $publikasi->refresh();

        // Verifikasi tag HTML dihapus bersih
        $this->assertStringNotContainsString('<p>', $publikasi->upload_laporan);
        $this->assertStringNotContainsString('<strong>', $publikasi->upload_laporan);
        $this->assertStringContainsString('Kegiatan seminar berjalan sukses dan dihadiri 100 peserta.', $publikasi->upload_laporan);

        // Verifikasi link publikasi tersimpan dalam array / JSON
        $this->assertCount(2, $publikasi->link_publikasi_array);
        $this->assertEquals('https://uis.ac.id/berita/seminar-nasional', $publikasi->link_publikasi_array[0]);
        $this->assertEquals('https://instagram.com/p/seminar-uis', $publikasi->link_publikasi_array[1]);

        // Verifikasi tampilan show menampilkan link-link tersebut
        $responseShow = $this->actingAs($admin)->get(route('publikasi.show', $publikasi));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('https://uis.ac.id/berita/seminar-nasional');
        $responseShow->assertSee('https://instagram.com/p/seminar-uis');
    }

    public function test_humas_can_access_pelaporan_for_assigned_pengajuan(): void
    {
        $humas = User::factory()->create([
            'is_admin' => 0,
            'is_humas' => 1,
            'name' => 'Ferry Humas',
            'no_wa' => '081234567890',
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $humas->id,
            'nama_kegiatan' => 'Peliputan Acara Dies Natalis',
            'unit_kegiatan' => 'Humas Universitas',
            'tempat_kegiatan' => 'Gedung Rektorat UIS',
            'tgl_awal' => '2026-11-20',
            'tgl_selesai' => '2026-11-20',
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '14:00',
            'petugas_ditugaskan' => 'Ferry Humas',
            'no_wa_tujuan' => '081234567890',
            'pic_petugas' => json_encode([['name' => 'Ferry Humas', 'no_wa' => '081234567890']]),
            'status' => 'diterima',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $humas->id,
            'pengajuan_id' => $pengajuan->id,
        ]);

        $response = $this->actingAs($humas)->get(route('publikasi.index'));
        $response->assertStatus(200);

        // Test DataTables AJAX request with sorting on column 1 (pengajuan.nama_kegiatan)
        $ajaxResponse = $this->actingAs($humas)->getJson(route('publikasi.index') . '?' . http_build_query([
            'draw' => 1,
            'columns' => [
                0 => ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => 'true', 'orderable' => 'false'],
                1 => ['data' => 'nama_kegiatan', 'name' => 'pengajuan.nama_kegiatan', 'searchable' => 'true', 'orderable' => 'true'],
            ],
            'order' => [
                0 => ['column' => 1, 'dir' => 'asc'],
            ],
            'start' => 0,
            'length' => 10,
            'search' => ['value' => '', 'regex' => 'false'],
        ]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonStructure(['data', 'draw', 'recordsTotal', 'recordsFiltered']);

        $responseEdit = $this->actingAs($humas)->get(route('publikasi.edit', $publikasi));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Peliputan Acara Dies Natalis');
    }

    public function test_pic_reports_activity_and_admin_validates_it(): void
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
            'name' => 'Super Administrator',
        ]);

        $humas = User::factory()->create([
            'is_admin' => 0,
            'is_humas' => 1,
            'name' => 'Rian Humas',
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $admin->id,
            'nama_kegiatan' => 'Wisuda Periode Genap UIS',
            'unit_kegiatan' => 'Rektorat UIS',
            'tempat_kegiatan' => 'Hotel Aston Batam',
            'tgl_awal' => '2026-11-25',
            'tgl_selesai' => '2026-11-25',
            'jam_kegiatan' => '07:30',
            'waktu_selesai' => '13:00',
            'petugas_ditugaskan' => 'Rian Humas',
            'status' => 'diterima',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $humas->id,
            'pengajuan_id' => $pengajuan->id,
            'status_validasi' => 'belum_dilaporkan',
        ]);

        // 1. PIC Humas mengisi dan mengirim laporan kegiatan
        $responseHumas = $this->actingAs($humas)->put(route('publikasi.update', $publikasi), [
            'upload_laporan' => '<p>Acara wisuda berjalan lancar dan khidmat.</p>',
            'link_dokumentasi' => 'https://drive.google.com/drive/folders/wisuda-genap',
            'link_publikasi' => ['https://uis.ac.id/wisuda-sukses'],
        ]);
        $responseHumas->assertRedirect(route('publikasi.index'));

        $publikasi->refresh();
        // Status otomatis menjadi menunggu_validasi
        $this->assertEquals('menunggu_validasi', $publikasi->status_validasi);
        $this->assertEquals('Acara wisuda berjalan lancar dan khidmat.', $publikasi->upload_laporan);

        // 2. PIC Humas membuka show: melihat banner menunggu validasi
        $showHumas = $this->actingAs($humas)->get(route('publikasi.show', $publikasi));
        $showHumas->assertStatus(200);
        $showHumas->assertSee('Menunggu Validasi');
        $showHumas->assertDontSee('Form Validasi Pelaporan Kegiatan (Super Admin)');

        // 3. User non-admin tidak boleh memvalidasi
        $unauthorized = $this->actingAs($humas)->post(route('publikasi.validasi', $publikasi), [
            'status_validasi' => 'disetujui',
        ]);
        $unauthorized->assertStatus(403);

        // 4. Admin membuka show: melihat form validasi Super Admin
        $showAdmin = $this->actingAs($admin)->get(route('publikasi.show', $publikasi));
        $showAdmin->assertStatus(200);
        $showAdmin->assertSee('Form Validasi Pelaporan Kegiatan (Super Admin)');

        // Admin melihat tombol aksi validasi langsung (Setujui dan Tolak Validasi) di tabel
        $indexAdmin = $this->actingAs($admin)->get(route('publikasi.index'));
        $indexAdmin->assertStatus(200);

        $adminAjax = $this->actingAs($admin)->getJson(route('publikasi.index'), ['X-Requested-With' => 'XMLHttpRequest']);
        $adminAjax->assertStatus(200);
        $actionHtml = $adminAjax->json()['data'][0]['action'];
        $this->assertStringContainsString('name="status_validasi" value="disetujui"', $actionHtml);
        $this->assertStringContainsString('name="status_validasi" value="ditolak"', $actionHtml);
        $this->assertStringContainsString('title="Tolak Validasi"', $actionHtml);

        // PIC Humas tidak memiliki tombol validasi langsung
        $indexHumas = $this->actingAs($humas)->get(route('publikasi.index'));
        $indexHumas->assertStatus(200);
        $humasAjax = $this->actingAs($humas)->getJson(route('publikasi.index'), ['X-Requested-With' => 'XMLHttpRequest']);
        $humasAjax->assertStatus(200);
        $humasActionHtml = $humasAjax->json()['data'][0]['action'];
        $this->assertStringNotContainsString('title="Tolak Validasi"', $humasActionHtml);

        // 5. Admin menolak validasi
        $tolakResponse = $this->actingAs($admin)->post(route('publikasi.validasi', $publikasi), [
            'status_validasi' => 'ditolak',
            'catatan_validasi' => 'Dokumentasi belum lengkap, tolong perbaiki.',
        ]);
        $tolakResponse->assertSessionHasNoErrors();

        $publikasi->refresh();
        $this->assertEquals('ditolak', $publikasi->status_validasi);
        $this->assertEquals('Dokumentasi belum lengkap, tolong perbaiki.', $publikasi->catatan_validasi);
        $this->assertEquals($admin->id, $publikasi->validated_by);
        $revisiResponse = $this->actingAs($admin)->post(route('publikasi.validasi', $publikasi), [
            'status_validasi' => 'perlu_revisi',
            'catatan_validasi' => 'Mohon sertakan link foto beresolusi tinggi di folder Drive.',
        ]);
        $revisiResponse->assertSessionHasNoErrors();

        $publikasi->refresh();
        $this->assertEquals('perlu_revisi', $publikasi->status_validasi);
        $this->assertEquals('Mohon sertakan link foto beresolusi tinggi di folder Drive.', $publikasi->catatan_validasi);
        $this->assertEquals($admin->id, $publikasi->validated_by);

        // 6. Admin akhirnya menyetujui / validasi sukses
        $approveResponse = $this->actingAs($admin)->post(route('publikasi.validasi', $publikasi), [
            'status_validasi' => 'disetujui',
            'catatan_validasi' => 'Laporan sudah lengkap dan dokumentasi memadai.',
        ]);
        $approveResponse->assertSessionHasNoErrors();

        $publikasi->refresh();
        $this->assertEquals('disetujui', $publikasi->status_validasi);
        $this->assertEquals('Laporan sudah lengkap dan dokumentasi memadai.', $publikasi->catatan_validasi);
    }

    public function test_active_tahun_akademik_is_automatically_assigned_to_publikasi(): void
    {
        $admin = User::factory()->create(['is_admin' => 1]);

        $activeYear = \App\Models\TahunAkademik::create([
            'tahun_akademik' => '2026-2027 Gasal TEST',
            'is_active' => true,
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $admin->id,
            'nama_kegiatan' => 'Kuliah Umum Artificial Intelligence',
            'unit_kegiatan' => 'FST',
            'tempat_kegiatan' => 'Lab Komputer',
            'tgl_awal' => '2026-11-30',
            'tgl_selesai' => '2026-11-30',
            'jam_kegiatan' => '10:00',
            'waktu_selesai' => '12:00',
            'status' => 'diterima',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $admin->id,
            'pengajuan_id' => $pengajuan->id,
        ]);

        // Verifikasi tahun_akademik_id terisi otomatis dari activeYear
        $this->assertEquals($activeYear->id, $publikasi->tahun_akademik_id);

        $showResponse = $this->actingAs($admin)->get(route('publikasi.show', $publikasi));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('2026-2027 Gasal TEST');

        // Test toggle / set active
        $newYear = \App\Models\TahunAkademik::create([
            'tahun_akademik' => '2026-2027 Genap TEST',
            'is_active' => false,
        ]);

        $this->actingAs($admin)->post(route('tahun-akademik.set-active', $newYear));
        $this->assertTrue($newYear->fresh()->is_active);
        $this->assertFalse($activeYear->fresh()->is_active);
    }

    public function test_publikasi_supports_optional_multi_file_dokumentasi_upload(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => $admin->id,
            'nama_kegiatan' => 'Workshop UI/UX Design',
            'unit_kegiatan' => 'FST',
            'tempat_kegiatan' => 'Lab Multimedia',
            'tgl_awal' => '2026-12-01',
            'tgl_selesai' => '2026-12-01',
            'jam_kegiatan' => '09:00',
            'waktu_selesai' => '13:00',
            'status' => 'diterima',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $admin->id,
            'pengajuan_id' => $pengajuan->id,
        ]);

        $file1 = UploadedFile::fake()->image('foto_kegiatan1.jpg', 600, 400);
        $file2 = UploadedFile::fake()->create('dokumen_rundown.pdf', 500, 'application/pdf');

        $response = $this->actingAs($admin)->put(route('publikasi.update', $publikasi), [
            'upload_laporan' => 'Laporan workshop UI/UX telah selesai dilaksanakan.',
            'link_dokumentasi' => 'https://drive.google.com/test',
            'dokumentasi_files' => [$file1, $file2],
        ]);

        $response->assertRedirect(route('publikasi.index'));

        $publikasi->refresh();
        $files = $publikasi->dokumentasi_files_array;
        $this->assertCount(2, $files);
        $this->assertEquals('foto_kegiatan1.jpg', $files[0]['original_name']);
        $this->assertEquals('dokumen_rundown.pdf', $files[1]['original_name']);

        Storage::disk('public')->assertExists($files[0]['path']);
        Storage::disk('public')->assertExists($files[1]['path']);

        $showResponse = $this->actingAs($admin)->get(route('publikasi.show', $publikasi));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('foto_kegiatan1.jpg');
        $showResponse->assertSee('dokumen_rundown.pdf');
    }
}
