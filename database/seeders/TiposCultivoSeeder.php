<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposCultivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_cultivo')->insert([
            ['tpCul_nombre' => 'Maíz'],
            ['tpCul_nombre' => 'Soja'],
            ['tpCul_nombre' => 'Trigo'],
            ['tpCul_nombre' => 'Arroz'],
            ['tpCul_nombre' => 'Girasol'],
            ['tpCul_nombre' => 'Caña de Azúcar'],
            ['tpCul_nombre' => 'Canola'],
            ['tpCul_nombre' => 'Sorgo'],
        ]);
    }
}
