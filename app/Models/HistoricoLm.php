<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoLm extends Model
{
    use HasFactory;

    protected $table = 'historico_lm';

    protected $fillable = ['id_lm', 'observacao'];
}
