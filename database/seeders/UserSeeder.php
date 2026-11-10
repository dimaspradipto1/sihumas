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
        $users = [
            [
                'name' => 'admin humas dan publikasi',
                'email' => 'admin@uis.ac.id',
                'no_wa' => '082283736481',
                'password' => Hash::make('password'),
                'fakultas' => 'UNIVERSITAS IBNU SINA',
                'is_admin' => true,
                'is_rektorat' => false,
                'is_feb' => false,
                'is_fst' => false,
                'is_fikes' => false,
                'is_users' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'operator rektorat',
                'email' => 'rektorat@uis.ac.id',
                'no_wa' => '',
                'password' => Hash::make('password'),
                'fakultas'=>'REKTORAT',
                'is_admin' => false,
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
                'fakultas'=>'FAKULTAS EKONOMI DAN BISNIS',
                'is_admin' => false,
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
                'fakultas'=>'FAKULTAS SAINS DAN TEKNOLOGI',
                'is_admin' => false,
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
                'fakultas'=>'FAKULTAS ILMU KESEHATAN',
                'is_admin' => false,
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
                'fakultas'=>'FAKULTAS SAINS DAN TEKNOLOGI',
                'is_admin' => false,
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
            DB::table('users')->insert($user);
        }
    }
}
