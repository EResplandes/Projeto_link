<?php

namespace Database\Seeders;

use App\Models\Grupo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grupo::create([
            'grupo' => 'Administrador',
        ]);

        Grupo::create([
            'grupo' => 'Gerentes',
        ]);

        Grupo::create([
            'grupo' => 'Diretores',
        ]);

        Grupo::create([
            'grupo' => 'Gestor de Fluxo',
        ]);


        Grupo::create([
            'grupo' => 'Presidente',
        ]);
    }
}
