<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('permisos')->insert([
            ['perm_id' => 1, 'perm_nombre' => 'Crear usuarios', 'perm_desc' => 'Permite crear nuevos usuarios'],
            ['perm_id' => 2, 'perm_nombre' => 'Editar usuarios', 'perm_desc' => 'Permite modificar la información de los usuarios'],
            ['perm_id' => 3, 'perm_nombre' => 'Eliminar usuarios', 'perm_desc' => 'Permite eliminar usuarios del sistema'],
            ['perm_id' => 4, 'perm_nombre' => 'Ver reportes', 'perm_desc' => 'Permite acceder a los reportes del sistema'],
        ]);
    }
}
