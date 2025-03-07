<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['rol_id' => 1, 'rol_desc' => 'Administrador'],
            ['rol_id' => 2, 'rol_desc' => 'Usuario'],
        ]);
    }
}
