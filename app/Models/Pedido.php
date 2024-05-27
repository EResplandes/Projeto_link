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
        'protheus',
        'urgente',
        'anexo',
        'id_link',
        'id_status',
        'id_empresa',
        'id_criador',
        'id_local',
        'tipo_pedido',
        'dt_vencimento',
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

    public function local()
    {
        return $this->belongsTo(Local::class, 'id_local');
    }

    public function historicos()
    {
        return $this->hasMany(HistoricoPedidos::class, 'id_pedido');
    }

    public function fluxo()
    {
        return $this->hasMany(Fluxo::class, 'id_pedido');
    }
}
