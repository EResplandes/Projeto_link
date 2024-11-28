<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMateriais extends Model
{
    use HasFactory;

    protected $table = 'chat_materiais';

    protected $fillable = [
        'id_material',
        'id_usuario',
        'mensagem',
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function material()
    {
        return $this->belongsTo(MateriasLm::class, 'id_material');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

}
