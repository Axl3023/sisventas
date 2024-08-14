<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('parametros')->insert([
            [
                'id_tipo' => 1,
                'serie' => '001',
                'numeracion' => '0'
            ],
            [
                'id_tipo' => 2,
                'serie' => '002',
                'numeracion' => '0'
            ]
        ]);
    }
}
