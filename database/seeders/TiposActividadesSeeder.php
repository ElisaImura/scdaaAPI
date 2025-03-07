<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposActividadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_actividades')->insert([
            ['tpAct_nombre' => 'Desecación', 'tpAct_desc' => 'Eliminación de la humedad del suelo o de las plantas para facilitar la siembra o cosecha.'],
            ['tpAct_nombre' => 'Tratamiento de Semilla', 'tpAct_desc' => 'Aplicación de productos para mejorar la germinación y proteger contra enfermedades.'],
            ['tpAct_nombre' => 'Siembra', 'tpAct_desc' => 'Colocación de semillas en el suelo para su cultivo.'],
            ['tpAct_nombre' => 'Control de Germinación', 'tpAct_desc' => 'Monitoreo del proceso de germinación para asegurar un crecimiento óptimo.'],
            ['tpAct_nombre' => 'Fumigación', 'tpAct_desc' => 'Aplicación de productos químicos o biológicos para el control de plagas y enfermedades.'],
            ['tpAct_nombre' => 'Cosecha', 'tpAct_desc' => 'Recolección de cultivos maduros para su procesamiento o venta.'],
        ]);
    }
}
