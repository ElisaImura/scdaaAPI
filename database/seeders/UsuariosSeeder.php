<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'rol_id' => 1, // Rol de administrador
                'uss_nombre' => 'Admin User',
                'uss_email' => 'admin@example.com',
                'uss_clave' => Hash::make('admin123'), // Contraseña encriptada
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
