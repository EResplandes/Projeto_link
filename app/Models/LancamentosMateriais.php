<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentosMateriais extends Model
{
    use HasFactory;

    protected $table = 'lancamentos_materiais';

    protected $fillable = [
        'id_material',
        'quantidade_entregue',
        'dt_entrega',
        'numero_nota',
        'nota'
    ];
}
