<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocaisLm extends Model
{
    use HasFactory;

    protected $table = 'locais_lm';

    protected $fillable = [
        'local',
        'ativo'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
