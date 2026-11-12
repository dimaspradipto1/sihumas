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

        $this->assertTrue($columns->has('unit_kegiatan'), 'Column unit_kegiatan should exist in UsersDataTable');
        $this->assertEquals('Unit', $columns->get('unit_kegiatan')->title);

        $this->assertTrue($columns->has('role'), 'Column role should exist in UsersDataTable');
        $this->assertEquals('Role Akses', $columns->get('role')->title);
    }

    public function test_admin_can_access_users_index(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee('data pengguna');
    }

    public function test_admin_can_view_create_user_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('users.create'));
        $response->assertStatus(200);
        $response->assertSee('Nomor WhatsApp');
        $response->assertSee('Unit');
        $response->assertSee('value="password"', false);
        $response->assertSee('togglePasswordVisibility');
    }

    public function test_admin_can_create_user_with_no_wa_and_unit(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $unit = \App\Models\UnitKegiatan::firstOrCreate(['unit_kegiatan' => 'FAKULTAS SAINS DAN TEKNOLOGI']);

        $userData = [
            'name' => 'Test User Unit',
            'email' => 'test_unit_' . uniqid() . '@uis.ac.id',
            'no_wa' => '081299887766',
            'unit_kegiatan_id' => $unit->id,
            'password' => 'password123',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'no_wa' => '081299887766',
            'unit_kegiatan_id' => $unit->id,
            'is_fst' => 1,
        ]);
    }

    public function test_admin_can_view_edit_user_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Existing User',
            'email' => 'existing_' . uniqid() . '@uis.ac.id',
            'no_wa' => '081234567890',
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
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'User To Update',
            'email' => 'update_' . uniqid() . '@uis.ac.id',
            'no_wa' => '081111111111',
        ]);

        $unit = \App\Models\UnitKegiatan::firstOrCreate(['unit_kegiatan' => 'FAKULTAS EKONOMI DAN BISNIS']);

        $updateData = [
            'name' => 'User Updated Name',
            'email' => $user->email,
            'no_wa' => '082299998888',
            'unit_kegiatan_id' => $unit->id,
            'is_feb' => 'true',
        ];

        $response = $this->actingAs($admin)->put(route('users.update', $user->id), $updateData);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'User Updated Name',
            'no_wa' => '082299998888',
            'unit_kegiatan_id' => $unit->id,
            'is_feb' => 1,
        ]);
    }

    public function test_no_wa_is_normalized_to_start_with_08(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $userData = [
            'name' => 'Normalization Test',
            'email' => 'normalize_' . uniqid() . '@uis.ac.id',
            'no_wa' => '6281234567890',
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
            'is_humas' => 1,
        ]);

        $createdUser = User::where('email', $userData['email'])->first();
        $this->assertEquals($unit->id, $createdUser->unitKegiatan->id);
        $this->assertEquals('Biro Humas & Promosi', $createdUser->fakultas);
    }

    public function test_create_user_defaults_to_password_if_omitted(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $email = 'default_pass_' . uniqid() . '@uis.ac.id';
        $userData = [
            'name' => 'Default Pass User',
            'email' => $email,
            // password field left empty
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);
        $response->assertRedirect(route('users.index'));

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password', $user->password));
    }

    public function test_admin_can_create_user_with_role_radio_selection(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $unit = \App\Models\UnitKegiatan::firstOrCreate([
            'unit_kegiatan' => 'BAAK - Kabid Akademik',
            'kategori' => 'BAAK',
        ]);

        $userData = [
            'name' => 'Operator BAAK Baru',
            'email' => 'baak_' . uniqid() . '@uis.ac.id',
            'no_wa' => '081234567899',
            'unit_kegiatan_id' => $unit->id,
            'role' => 'operator',
            'password' => 'password123',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);
        $response->assertRedirect(route('users.index'));

        $created = User::where('email', $userData['email'])->first();
        $this->assertNotNull($created);
        $this->assertFalse((bool)$created->is_admin);
        $this->assertFalse((bool)$created->is_humas);
        $this->assertTrue((bool)$created->is_users);
        $this->assertEquals('operator', $created->role_key);
        $this->assertEquals('Pengusul (Kabid Akademik)', $created->role_label);
    }
}

