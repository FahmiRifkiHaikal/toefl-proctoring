<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun untuk Simulasi Sisi Admin ITN Malang
        User::create([
            'name' => 'Admin Proctors',
            'email' => 'admin@itn.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Akun untuk Simulasi Sisi Peserta Ujian
        User::create([
            'name' => 'Fahmi Peserta',
            'email' => 'fahmi@peserta.com',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
        ]);

        User::create([
            'name' => 'Ramdhan S. Zulkifly',
            'email' => 'ramdhan@peserta.com',
            'password' => Hash::make('ramdhan12345'),
            'role' => 'peserta',
        ]);
    }
}
