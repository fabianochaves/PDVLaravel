<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipos extends Model
{
    protected $table = 'tipos_produtos';

    protected $primaryKey = 'id_tipo_produto';

    protected $fillable = [
        'nome_tipo_produto',
        'imposto_tipo_produto',
        'status_tipo_produto',
    ];

    public $timestamps = false;

}
