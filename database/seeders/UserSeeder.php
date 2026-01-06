<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'zahlul.fuadi',
            'email' => 'zahlul.fuadi@bps.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'username' => 'hafidz.alahmad',
            'email' => 'hafidz.alahmad@bps.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'username' => 'adrian.devano',
            'email' => 'adrian.devano@bps.go.id',
            'password' => Hash::make('password'),
            'role' => 'pembimbing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Data Dummy
        DB::table('users')->insert([
            'username' => 'mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pelajar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
