<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $table = 'vendas';

    protected $primaryKey = 'id_venda';

    protected $fillable = [
        'datetime_venda',
        'valor_total_venda',
        'valor_imposto_venda',
        'status_venda',
    ];

    public $timestamps = false;

}
