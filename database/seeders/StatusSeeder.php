<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'status' => 'Enviado para Emival'
        ]);

        Status::create([
            'status' => 'Enviado para Monica'
        ]);

        Status::create([
            'status' => 'Reprovado'
        ]);

        Status::create([
            'status' => 'Aprovado'
        ]);

        Status::create([
            'status' => 'Aprovado com Ressalva'
        ]);

        Status::create([
            'status' => 'Analisando'
        ]);

        Status::create([
            'status' => 'Em Fluxo'
        ]);

        Status::create([
            'status' => 'Excluído'
        ]);

        Status::create([
            'status' => 'Delegado'
        ]);
    }
}
