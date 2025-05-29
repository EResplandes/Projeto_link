<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriasLm extends Model
{
    use HasFactory;

    protected $table = 'materiais_lm';

    protected $fillable = [
        'quantidade',
        'unidade',
        'descricao',
        'descritiva',
        'indicador',
        'id_lm',
        'id_materiais',
        'id_pedido',
        'liberado_almoxarife'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function lancamentosMateriais()
    {
        return $this->hasMany(LancamentosMateriais::class, 'id_material');
    }

    public function chat()
    {
        return $this->hasMany(ChatMateriais::class, 'id_material');
    }
}
