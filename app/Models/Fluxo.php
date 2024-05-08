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
        'id_status',
        'observacao',
        'created_at'
    ];

    protected $hiddem = [
        'updated_at'
    ];


}
