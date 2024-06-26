<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'nome_empresa',
        'cnpj',
        'filial',
        'created_at'
    ];

    protected $hidden = ['updated_at'];
}
