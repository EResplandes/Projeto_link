<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    use HasFactory;

    protected $table = 'boletos';

    protected $fillable = [
        'boleto',
        'id_pedido',
        'status'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'id_pedido'
    ];

    public function pedidos()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
