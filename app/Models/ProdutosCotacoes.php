<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosCotacoes extends Model
{
    use HasFactory;

    protected $table = 'prodtos_cotacoes';

    protected $fillable = [
        'id_cotacao',
        'produto',
        'valor',
        'fornecedor',
        'link_produto',
        'link_imagem',
        'entrega'
    ];
}
