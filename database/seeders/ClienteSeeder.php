<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clientes')->insert([
            [
                'nro_doc' => '12345678901',
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'email' => 'juan.perez@example.com',
                'direccion' => 'Av. Principal 123',
                'estado' => true              
            ],
            [
                'nro_doc' => '23456789012',
                'nombre' => 'Maria',
                'apellido' => 'Garcia',
                'email' => 'maria.garcia@example.com',
                'direccion' => 'Calle Secundaria 456',
                'estado' => true
            ],
            [
                'nro_doc' => '34567890123',
                'nombre' => 'Carlos',
                'apellido' => 'Lopez',
                'email' => 'carlos.lopez@example.com',
                'direccion' => 'Jr. Los Olivos 789',
                'estado' => true
            ],
            [
                'nro_doc' => '45678901234',
                'nombre' => 'Ana',
                'apellido' => 'Martinez',
                'email' => 'ana.martinez@example.com',
                'direccion' => 'Av. Las Flores 321',
                'estado' => true
            ],
            [
                'nro_doc' => '56789012345',
                'nombre' => 'Luis',
                'apellido' => 'Ramirez',
                'email' => 'luis.ramirez@example.com',
                'direccion' => 'Calle Los Pinos 654',
                'estado' => true
            ],
        ]);
    }
}
