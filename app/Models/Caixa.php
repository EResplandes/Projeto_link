<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    use HasFactory;

    protected $table = 'caixas';

    protected $fillable = [
        'funcionario',
        'funcao_funcionario',
        'anexo',
        'banco',
        'agencia',
        'conta',
        'cpf'
    ];
}
