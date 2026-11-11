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
        $response->assertSee('LAPORAN KEGIATAN HUMAS DAN PUBLIKASI');
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

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'nama_kegiatan' => 'Kegiatan Filter TA Testing',
            'tgl_awal' => '2026-11-10',
            'tgl_selesai' => '2026-11-11',
            'status' => 'Selesai',
        ]);

        $publikasi = Publikasi::create([
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

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'nama_kegiatan' => 'Kegiatan Filter Bulan Nov 2026',
            'tgl_awal' => '2026-11-15',
            'tgl_selesai' => '2026-11-16',
            'status' => 'Selesai',
        ]);

        $publikasi = Publikasi::create([
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

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'nama_kegiatan' => 'Kegiatan Khusus Tahun 2027',
            'tgl_awal' => '2027-03-01',
            'tgl_selesai' => '2027-03-02',
            'status' => 'Selesai',
        ]);

        $publikasi = Publikasi::create([
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

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'nama_kegiatan' => 'Kegiatan Rentang Tanggal Spesifik',
            'tgl_awal' => '2026-12-01',
            'tgl_selesai' => '2026-12-05',
            'status' => 'Selesai',
        ]);

        $publikasi = Publikasi::create([
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
}


