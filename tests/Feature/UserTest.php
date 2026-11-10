<?php

namespace Tests\Feature;

use App\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_users_datatable_has_no_wa_and_unit_columns(): void
    {
        $dataTable = app(UsersDataTable::class);
        $columns = collect($dataTable->getColumns())->keyBy('data');

        $this->assertTrue($columns->has('no_wa'), 'Column no_wa should exist in UsersDataTable');
        $this->assertEquals('No. WhatsApp', $columns->get('no_wa')->title);

        $this->assertTrue($columns->has('fakultas'), 'Column fakultas should exist in UsersDataTable');
        $this->assertEquals('Unit', $columns->get('fakultas')->title);

        $this->assertTrue($columns->has('role'), 'Column role should exist in UsersDataTable');
        $this->assertEquals('Role Akses', $columns->get('role')->title);
    }

    public function test_admin_can_access_users_index(): void
    {
        $admin = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee('data pengguna');
    }

    public function test_admin_can_view_create_user_page(): void
    {
        $admin = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('users.create'));
        $response->assertStatus(200);
        $response->assertSee('Nomor WhatsApp');
        $response->assertSee('Unit');
    }

    public function test_admin_can_create_user_with_no_wa_and_unit(): void
    {
        $admin = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $userData = [
            'name' => 'Test User Unit',
            'email' => 'test_unit_' . uniqid() . '@uis.ac.id',
            'no_wa' => '081299887766',
            'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI',
            'password' => 'password123',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'no_wa' => '081299887766',
            'fakultas' => 'FAKULTAS SAINS DAN TEKNOLOGI',
            'is_fst' => 1,
        ]);
    }

    public function test_admin_can_view_edit_user_page(): void
    {
        $admin = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Existing User',
            'email' => 'existing_' . uniqid() . '@uis.ac.id',
            'no_wa' => '081234567890',
            'fakultas' => 'REKTORAT',
        ]);

        $response = $this->actingAs($admin)->get(route('users.edit', $user->id));
        $response->assertStatus(200);
        $response->assertSee('Nomor WhatsApp');
        $response->assertSee('Unit');
        $response->assertSee('081234567890');
    }

    public function test_admin_can_update_user_no_wa_and_unit(): void
    {
        $admin = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'User To Update',
            'email' => 'update_' . uniqid() . '@uis.ac.id',
            'no_wa' => '081111111111',
            'fakultas' => 'REKTORAT',
        ]);

        $updateData = [
            'name' => 'User Updated Name',
            'email' => $user->email,
            'no_wa' => '082299998888',
            'fakultas' => 'FAKULTAS EKONOMI DAN BISNIS',
            'is_feb' => 'true',
        ];

        $response = $this->actingAs($admin)->put(route('users.update', $user->id), $updateData);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'User Updated Name',
            'no_wa' => '082299998888',
            'fakultas' => 'FAKULTAS EKONOMI DAN BISNIS',
            'is_feb' => 1,
        ]);
    }

    public function test_no_wa_is_normalized_to_start_with_08(): void
    {
        $admin = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $userData = [
            'name' => 'Normalization Test',
            'email' => 'normalize_' . uniqid() . '@uis.ac.id',
            'no_wa' => '6281234567890',
            'fakultas' => 'REKTORAT',
            'password' => 'password123',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'no_wa' => '081234567890',
        ]);
    }

    public function test_admin_can_create_user_with_unit_kegiatan_relation_and_role_humas(): void
    {
        $admin = User::factory()->create([
            'fakultas' => 'UNIVERSITAS IBNU SINA',
            'is_admin' => true,
        ]);

        $unit = \App\Models\UnitKegiatan::create([
            'unit_kegiatan' => 'Biro Humas & Promosi',
        ]);

        $userData = [
            'name' => 'Petugas Humas Baru',
            'email' => 'humas_' . uniqid() . '@uis.ac.id',
            'no_wa' => '085600001111',
            'unit_kegiatan_id' => $unit->id,
            'is_humas' => 'true',
            'password' => 'password123',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'unit_kegiatan_id' => $unit->id,
            'fakultas' => 'Biro Humas & Promosi',
            'is_humas' => 1,
        ]);

        $createdUser = User::where('email', $userData['email'])->first();
        $this->assertEquals($unit->id, $createdUser->unitKegiatan->id);
    }
}

