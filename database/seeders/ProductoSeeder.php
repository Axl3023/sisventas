<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productosPorCategoria = [
            'Tecnología' => [
                ['descripcion' => 'Laptop', 'precio' => 1500.00],
                ['descripcion' => 'Smartphone', 'precio' => 800.00],
                ['descripcion' => 'Tablet', 'precio' => 600.00],
            ],
            'Electrodomésticos' => [
                ['descripcion' => 'Refrigerador', 'precio' => 1000.00],
                ['descripcion' => 'Microondas', 'precio' => 150.00],
                ['descripcion' => 'Lavadora', 'precio' => 750.00],
            ],
            'Ropa' => [
                ['descripcion' => 'Camiseta', 'precio' => 25.00],
                ['descripcion' => 'Jeans', 'precio' => 50.00],
                ['descripcion' => 'Chaqueta', 'precio' => 100.00],
            ],
            'Útiles escolares' => [
                ['descripcion' => 'Cuaderno', 'precio' => 3.00],
                ['descripcion' => 'Lápiz', 'precio' => 0.50],
                ['descripcion' => 'Mochila', 'precio' => 30.00],
            ],
            'Automóviles' => [
                ['descripcion' => 'Sedán', 'precio' => 20000.00],
                ['descripcion' => 'SUV', 'precio' => 30000.00],
                ['descripcion' => 'Pickup', 'precio' => 25000.00],
            ],
        ];

        foreach ($productosPorCategoria as $categoriaDescripcion => $productos) {
            $categoria = Categoria::where('descripcion', $categoriaDescripcion)->first();

            foreach ($productos as $producto) {
                Producto::create([
                    'descripcion' => $producto['descripcion'],
                    'estado' => true,
                    'stock' => 10,
                    'precio' => $producto['precio'],
                    'id_categoria' => $categoria->id,
                    'id_unidad' => 1,
                ]);
            }
        }
    }
}
