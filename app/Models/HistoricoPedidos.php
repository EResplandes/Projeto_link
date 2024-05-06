<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoPedidos extends Model
{
    use HasFactory;

    protected $table = 'historico_pedidos';

    protected $fillable = [
        'id_pedido',
        'id_status',
        'observacao',
        'created_at'
    ];
}
