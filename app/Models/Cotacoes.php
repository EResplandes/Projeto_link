<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotacoes extends Model
{
    use HasFactory;

    protected $table = 'cotacoes';

    protected $fillable = [
        'finalidade',
        'rm',
        'id_comprador',
        'id_empresa',
        'id_local'
    ];

    public function comprador()
    {
        return $this->hasOne(User::class, 'id', 'id_comprador');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id', 'id_empresa');
    }

    public function local()
    {
        return $this->hasOne(Local::class, 'id', 'id_local');
    }

    public function pedido()
    {
        return $this->hasOne(Pedido::class, 'id', 'id_pedido');
    }
}
