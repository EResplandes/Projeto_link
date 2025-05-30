<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListaMateriais;

class AnexosLm extends Model
{
    use HasFactory;

    protected $table = 'anexos_lm';

    protected $fillable = [
        'id_lm',
        'id_usuario',
        'anexo',
        'observacao',
        'extensao'
    ];


    public function lm()
    {
        return $this->belongsTo(ListaMateriais::class, 'id_lm');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
