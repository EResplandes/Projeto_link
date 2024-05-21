<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;

    protected $table = 'local';

    protected $fillable = [
        'local',
        'created_at'
    ];

    protected $hiddem = [
        'updated_at'
    ];
}
