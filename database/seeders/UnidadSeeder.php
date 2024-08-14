<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unidad;

class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            ['descripcion' => 'Unidad', 'estado' => true],
            ['descripcion' => 'Par', 'estado' => true],
            ['descripcion' => 'Docena', 'estado' => true],
            ['descripcion' => 'Pack', 'estado' => true],
        ];

        foreach ($unidades as $unidad) {
            Unidad::create($unidad);
        }
    }
}
