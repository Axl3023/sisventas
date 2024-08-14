<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\TipoDocumento;
use App\Models\User;
use GuzzleHttp\Client;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);

        $this->call([
            CategoriaSeeder::class,
            UnidadSeeder::class,
            TipoDocumentoSeeder::class,
            ClienteSeeder::class,
            ProductoSeeder::class,
            ParametroSeeder::class,
        ]);
    }
}
