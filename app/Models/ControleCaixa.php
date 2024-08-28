<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControleCaixa extends Model
{
    use HasFactory;

    protected $table = 'controle_caixa';

    protected $fillable = [
        'id_caixa',
        'dt_lancamento',
        'discriminacao',
        'debito',
        'credito',
        'saldo',
        'observacao',
        'tipo_caixa'
    ];
}
