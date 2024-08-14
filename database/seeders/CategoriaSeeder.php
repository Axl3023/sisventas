<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['descripcion' => 'Tecnología', 'estado' => true],
            ['descripcion' => 'Electrodomésticos', 'estado' => true],
            ['descripcion' => 'Ropa', 'estado' => true],
            ['descripcion' => 'Útiles escolares', 'estado' => true],
            ['descripcion' => 'Automóviles', 'estado' => true],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
