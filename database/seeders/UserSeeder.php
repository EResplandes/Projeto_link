<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {

        User::create([
            'name' => 'Emival Caiado',
            'email' => 'emival@gruporialma.com.br',
            'password' => bcrypt('Super@2018'),
            'id_funcao' => 5, // ID da função do usuário
            'id_grupo' => 5, // ID do grupo do usuário
        ]);

        User::create([
            'name' => 'Eduardo C. Resplandes',
            'email' => 'eduardo.resplandes@gruporialma.com.br',
            'password' => bcrypt('Super@2018'),
            'id_funcao' => 1, // ID da função do usuário
            'id_grupo' => 1, // ID do grupo do usuário
        ]);
    }
}
