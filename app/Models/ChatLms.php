<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatLms extends Model
{
    use HasFactory;


    protected $table = 'chat_lms';

    protected $fillable = [
        'id_lm',
        'id_usuario',
        'mensagem',
    ];

    protected $hidden = [
        'updated_at'
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
