<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'perfil_usuario',
        'nome_usuario',
        'email_usuario',
        'telefone_usuario',
        'login_usuario',
        'senha_usuario',
        'status_usuario',
    ];

    // Não atualizar created_at updated_at
    public $timestamps = false;
}
