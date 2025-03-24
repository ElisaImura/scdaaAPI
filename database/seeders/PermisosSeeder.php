<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('permisos')->insert([
            // Permisos para Gestión de Ciclos
            ['perm_id' => 1, 'perm_nombre' => 'Crear ciclos', 'perm_desc' => 'Permite crear nuevos ciclos'],
            ['perm_id' => 2, 'perm_nombre' => 'Editar ciclos', 'perm_desc' => 'Permite modificar los datos de los ciclos'],
            ['perm_id' => 3, 'perm_nombre' => 'Eliminar ciclos', 'perm_desc' => 'Permite eliminar ciclos del sistema'],

            // Permisos para Gestión de Insumos
            ['perm_id' => 4, 'perm_nombre' => 'Crear insumos', 'perm_desc' => 'Permite crear nuevos insumos'],
            ['perm_id' => 5, 'perm_nombre' => 'Editar insumos', 'perm_desc' => 'Permite modificar los datos de los insumos'],
            ['perm_id' => 6, 'perm_nombre' => 'Eliminar insumos', 'perm_desc' => 'Permite eliminar insumos del sistema'],
        ]);
    }
}
