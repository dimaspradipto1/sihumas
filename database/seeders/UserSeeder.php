<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitHumas = \App\Models\UnitKegiatan::where('unit_kegiatan', 'like', '%Humas%')->first();
        $unitRektorat = \App\Models\UnitKegiatan::where('unit_kegiatan', 'Rektorat')->first();
        $unitFeb = \App\Models\UnitKegiatan::where('unit_kegiatan', 'like', '%Ekonomi%')->first();
        $unitFst = \App\Models\UnitKegiatan::where('unit_kegiatan', 'like', '%Sains%')->first();
        $unitFikes = \App\Models\UnitKegiatan::where('unit_kegiatan', 'like', '%Kesehatan%')->first();

        $users = [
            [
                'name' => 'admin humas dan publikasi',
                'email' => 'admin@uis.ac.id',
                'no_wa' => '082283736481',
                'password' => Hash::make('password'),
                'unit_kegiatan_id' => $unitHumas?->id,
                'is_admin' => true,
                'is_humas' => true,
                'is_rektorat' => false,
                'is_feb' => false,
                'is_fst' => false,
                'is_fikes' => false,
                'is_users' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nanda',
                'email' => 'nanda@uis.ac.id',
                'no_wa' => '085668494033',
                'password' => Hash::make('password'),
                'unit_kegiatan_id' => $unitHumas?->id,
                'is_admin' => false,
                'is_humas' => true,
                'is_rektorat' => false,
                'is_feb' => false,
                'is_fst' => false,
                'is_fikes' => false,
                'is_users' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ujang',
                'email' => 'ujang@uis.ac.id',
                'no_wa' => '087701514453',
                'password' => Hash::make('password'),
                'unit_kegiatan_id' => $unitRektorat?->id,
                'is_admin' => false,
                'is_humas' => false,
                'is_rektorat' => true,
                'is_feb' => false,
                'is_fst' => false,
                'is_fikes' => false,
                'is_users' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'operator Feb',
                'email' => 'feb@uis.ac.id',
                'no_wa' => '',
                'password' => Hash::make('password'),
                'unit_kegiatan_id' => $unitFeb?->id,
                'is_admin' => false,
                'is_humas' => false,
                'is_rektorat' => false,
                'is_feb' => true,
                'is_fst' => false,
                'is_fikes' => false,
                'is_users' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'operator FST',
                'email' => 'fst@uis.ac.id',
                'no_wa' => '085363572448',
                'password' => Hash::make('password'),
                'unit_kegiatan_id' => $unitFst?->id,
                'is_admin' => false,
                'is_humas' => false,
                'is_rektorat' => false,
                'is_feb' => false,
                'is_fst' => true,
                'is_fikes' => false,
                'is_users' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'operator Fikes',
                'email' => 'fikes@uis.ac.id',
                'no_wa' => '',
                'password' => Hash::make('password'),
                'unit_kegiatan_id' => $unitFikes?->id,
                'is_admin' => false,
                'is_humas' => false,
                'is_rektorat' => false,
                'is_feb' => false,
                'is_fst' => false,
                'is_fikes' => true,
                'is_users' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'pengguna',
                'email' => 'dimas@uis.ac.id',
                'no_wa' => '',
                'password' => Hash::make('password'),
                'unit_kegiatan_id' => $unitFst?->id,
                'is_admin' => false,
                'is_humas' => false,
                'is_rektorat' => false,
                'is_feb' => false,
                'is_fst' => false,
                'is_fikes' => false,
                'is_users' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(['email' => $user['email']], $user);
        }
    }
}
