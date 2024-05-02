<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';

    protected $fillable = ['status'];

    public function pedidos()
    {
        return $this->hasMany(Status::class, 'id');
    }
}
