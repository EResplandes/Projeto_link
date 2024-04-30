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
        // Exemplo de criação de um usuário
        User::create([
            'name' => 'Danilo',
            'email' => 'danilo@gmail.com',
            'password' => bcrypt('123456'),
            'id_funcao' => 1, // ID da função do usuário
            'id_grupo' => 1, // ID do grupo do usuário
        ]);

        // Adicione mais chamadas para o método create() para inserir mais usuários se necessário
    }
}
