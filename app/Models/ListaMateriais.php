<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaMateriais extends Model
{
    use HasFactory;

    protected $table = 'listas_materiais';

    protected $fillable = [
        'urgente',
        'aplicacao',
        'lm',
        'prazo',
        'data_prevista',
        'id_solicitante',
        'id_status',
        'id_empresa',
        'id_comprador',
        'id_local'
    ];

    /**
     * Relacionamento com Materiais
     * Uma lista de materiais pode ter muitos materiais.
     */
    public function materiais()
    {
        return $this->hasMany(MateriasLm::class, 'id_lm', 'id');
    }

    /**
     * Relacionamento com Empresa
     * Uma lista de materiais pertence a uma única empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
    }

    /**
     * Relacionamento com Solicitante
     * Uma lista de materiais pertence a um único solicitante.
     */
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_solicitante', 'id');
    }

    /**
     * Relacionamento com Comprador
     * Uma lista de materiais pode ter um único comprador.
     */
    public function comprador()
    {
        return $this->belongsTo(User::class, 'id_comprador', 'id');
    }

    /**
     * Relacionamento com Status
     * Uma lista de materiais pode ter um único status.
     */
    public function status()
    {
        return $this->belongsTo(StatusLm::class, 'id_status', 'id');
    }

    public function local()
    {
        return $this->belongsTo(LocaisLm::class, 'id_local', 'id');
    }
}
