<?php

namespace Database\Seeders;

use App\Models\Funcao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuncaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Funcao::create([
            'funcao' => 'Administrador',
        ]);

        Funcao::create([
            'funcao' => 'Gerente',
        ]);

        Funcao::create([
            'funcao' => 'Diretor',
        ]);

        Funcao::create([
            'funcao' => 'Gestor de Fluxo',
        ]);

        Funcao::create([
            'funcao' => 'Presidente',
        ]);
    }
}
