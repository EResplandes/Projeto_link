<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLm extends Model
{
    use HasFactory;

    protected $table = 'status_lm';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
