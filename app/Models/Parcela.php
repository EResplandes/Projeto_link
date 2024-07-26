<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    protected $table = 'parcelas';

    protected $fillable = [
        'id_pedido',
        'dt_vencimento',
        'dt_pagamento',
        'validado',
        'dt_validacao',
        'status',
        'valor'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
