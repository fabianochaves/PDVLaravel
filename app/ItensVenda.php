<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItensVenda extends Model
{
    protected $table = 'itens_venda';

    protected $primaryKey = 'id_item_venda';

    protected $fillable = [
        'cod_venda',
        'cod_produto_venda',
        'qtd_produto_venda',
        'valor_unitario_venda',
        'imposto_produto_venda',
        'total_produto_venda',
        'total_imposto_venda',
        'status_item_venda',
    ];

    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'cod_produto_venda', 'id_produto');
    }

}
