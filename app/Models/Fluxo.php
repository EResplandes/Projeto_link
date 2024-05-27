<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fluxo extends Model
{
    use HasFactory;

    protected $table = 'fluxos';

    protected $fillable = [
        'id_pedido',
        'id_usuario',
        'assinado',
        'created_at'
    ];

    protected $hiddem = [
        'updated_at'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido')->where('id_status', 7);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
