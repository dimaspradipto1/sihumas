<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BackupTest extends TestCase
{
    use DatabaseTransactions;

    public function test_non_admin_cannot_access_backup_page(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get(route('backup.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_backup_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('backup.index'));
        $response->assertStatus(200);
        $response->assertSee('Backup Database &amp; Storage', false);
        $response->assertSee('Ekspor &amp; Impor Database', false);
        $response->assertSee('Backup &amp; Impor Storage Public', false);
    }

    public function test_admin_can_download_database(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('backup.database.download'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/sql');
    }

    public function test_admin_can_download_storage(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('backup.storage.download'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/zip');
    }
}
