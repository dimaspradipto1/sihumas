<?php

namespace Tests\Feature;

use App\Models\LaporanPublikasi;
use App\Models\Pengajuan;
use App\Models\Publikasi;
use App\Models\TahunAkademik;
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
        $response->assertSee('target="_blank"', false);
        $response->assertSee('filter_type', false);
        $response->assertSee('tahun_akademik', false);
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
        $response->assertSee('laporan kegiatan humas dan publikasi', false);
    }

    private function createPengajuan(User $user, array $overrides = []): Pengajuan
    {
        return Pengajuan::create(array_merge([
            'user_id' => $user->id,
            'nama_kegiatan' => 'Kegiatan Test',
            'tgl_awal' => '2026-11-10',
            'tgl_selesai' => '2026-11-11',
            'jam_kegiatan' => '08:00',
            'waktu_selesai' => '12:00',
            'tempat_kegiatan' => 'Kampus UIS',
            'unit_kegiatan' => 'Humas',
            'deskripsi_kegiatan' => 'Deskripsi testing',
            'status' => 'Selesai',
        ], $overrides));
    }

    public function test_filter_print_by_tahun_akademik(): void
    {
        $user = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $ta = TahunAkademik::create([
            'tahun_akademik' => '2026/2027 Ganjil',
            'is_active' => true,
        ]);

        $pengajuan = $this->createPengajuan($user, [
            'nama_kegiatan' => 'Kegiatan Filter TA Testing',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $user->id,
            'pengajuan_id' => $pengajuan->id,
            'tahun_akademik_id' => $ta->id,
            'link_publikasi' => 'https://example.com/pub-ta',
        ]);

        LaporanPublikasi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'publikasi_id' => $publikasi->id,
            'tgl_awal' => '2026-11-10',
            'tgl_selesai' => '2026-11-11',
        ]);

        $response = $this->actingAs($user)->get(route('laporan-publikasi.show', [
            'filter_type' => 'tahun_akademik',
            'tahun_akademik' => $ta->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tahun Akademik: 2026/2027 Ganjil');
        $response->assertSee('Kegiatan Filter TA Testing');
    }

    public function test_filter_print_by_bulan_dan_tahun(): void
    {
        $user = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $pengajuan = $this->createPengajuan($user, [
            'nama_kegiatan' => 'Kegiatan Filter Bulan Nov 2026',
            'tgl_awal' => '2026-11-15',
            'tgl_selesai' => '2026-11-16',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $user->id,
            'pengajuan_id' => $pengajuan->id,
            'link_publikasi' => 'https://example.com/pub-nov',
        ]);

        LaporanPublikasi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'publikasi_id' => $publikasi->id,
            'tgl_awal' => '2026-11-15',
            'tgl_selesai' => '2026-11-16',
        ]);

        $response = $this->actingAs($user)->get(route('laporan-publikasi.show', [
            'filter_type' => 'bulan',
            'bulan' => '11',
            'tahun' => '2026',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bulan November 2026');
        $response->assertSee('Kegiatan Filter Bulan Nov 2026');
    }

    public function test_filter_print_by_tahun_kalender(): void
    {
        $user = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $pengajuan = $this->createPengajuan($user, [
            'nama_kegiatan' => 'Kegiatan Khusus Tahun 2027',
            'tgl_awal' => '2027-03-01',
            'tgl_selesai' => '2027-03-02',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $user->id,
            'pengajuan_id' => $pengajuan->id,
            'link_publikasi' => 'https://example.com/pub-2027',
        ]);

        LaporanPublikasi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'publikasi_id' => $publikasi->id,
            'tgl_awal' => '2027-03-01',
            'tgl_selesai' => '2027-03-02',
        ]);

        $response = $this->actingAs($user)->get(route('laporan-publikasi.show', [
            'filter_type' => 'tahun',
            'tahun' => '2027',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tahun 2027');
        $response->assertSee('Kegiatan Khusus Tahun 2027');
    }

    public function test_filter_print_by_rentang_tanggal(): void
    {
        $user = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $pengajuan = $this->createPengajuan($user, [
            'nama_kegiatan' => 'Kegiatan Rentang Tanggal Spesifik',
            'tgl_awal' => '2026-12-01',
            'tgl_selesai' => '2026-12-05',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $user->id,
            'pengajuan_id' => $pengajuan->id,
            'link_publikasi' => 'https://example.com/pub-range',
        ]);

        LaporanPublikasi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'publikasi_id' => $publikasi->id,
            'tgl_awal' => '2026-12-01',
            'tgl_selesai' => '2026-12-05',
        ]);

        $response = $this->actingAs($user)->get(route('laporan-publikasi.show', [
            'filter_type' => 'tanggal',
            'tgl_awal' => '2026-12-01',
            'tgl_selesai' => '2026-12-05',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Periode 01 Des 2026 s/d 05 Des 2026');
        $response->assertSee('Kegiatan Rentang Tanggal Spesifik');
    }

    public function test_laporan_publikasi_datatable_matches_pelaporan_structure(): void
    {
        $admin = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $ta = TahunAkademik::create([
            'tahun_akademik' => '2026/2027 Ganjil',
            'is_active' => true,
        ]);

        $pengajuan = $this->createPengajuan($admin, [
            'nama_kegiatan' => 'Workshop Desain dan Dokumentasi',
            'tgl_awal' => '2026-11-10',
            'tgl_selesai' => '2026-11-10',
        ]);

        $publikasi = Publikasi::create([
            'user_id' => $admin->id,
            'pengajuan_id' => $pengajuan->id,
            'tahun_akademik_id' => $ta->id,
            'status_validasi' => 'disetujui',
            'link_dokumentasi' => 'https://drive.google.com/test-folder',
            'link_publikasi' => 'https://example.com/test-link',
        ]);

        LaporanPublikasi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $admin->id,
            'publikasi_id' => $publikasi->id,
            'tgl_awal' => '2026-11-10',
            'tgl_selesai' => '2026-11-10',
        ]);

        $response = $this->actingAs($admin)->getJson(route('laporan-publikasi.index', [
            'columns' => [
                0 => ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false'],
                1 => ['data' => 'nama_kegiatan', 'name' => 'pengajuan.nama_kegiatan', 'orderable' => 'true'],
            ],
            'order' => [
                0 => ['column' => 1, 'dir' => 'asc'],
            ],
        ]), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $json = $response->json();
        $this->assertArrayHasKey('data', $json);
        $this->assertNotEmpty($json['data']);

        $row = $json['data'][0];
        $this->assertStringContainsString('Workshop Desain dan Dokumentasi', $row['nama_kegiatan']);
        $this->assertStringContainsString('Disetujui', $row['status_validasi']);
        $this->assertStringContainsString('Buka Drive', $row['link_dokumentasi']);
        $this->assertStringContainsString('Link', $row['link_publikasi']);
        $this->assertStringContainsString('btn-modal-validasi', $row['action']);
    }
}


