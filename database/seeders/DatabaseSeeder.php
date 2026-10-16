<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@anka.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // Usuario Alumno de prueba
        User::create([
            'name' => 'Ana Martínez',
            'email' => 'ana@anka.com',
            'password' => Hash::make('12345678'),
            'role' => 'alumno',
        ]);
    }
}