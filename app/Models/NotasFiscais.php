<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotasFiscais extends Model
{
    use HasFactory;

    protected $table = 'notas_fiscais';

    protected $fillable = [
        'nota',
        'id_pedido',
        'dt_emissao',
        'numero_nota',
        'valor',
        'dt_escrituracao',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['updated_at'];

    public function pedidos()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
