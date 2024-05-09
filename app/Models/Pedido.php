<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'descricao',
        'valor',
        'urgente',
        'anexo',
        'id_link',
        'id_status',
        'id_empresa'
    ];

    protected $hidden = ['updated_at'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'id_link');
    }

}
