<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    protected $table = 'produtos';

    protected $primaryKey = 'id_produto';

    protected $fillable = [
        'nome_produto',
        'tipo_produto',
        'preco_venda_produto',
        'preco_custo_produto',
        'status_produto',
    ];

    public $timestamps = false;

    public function itemVenda()
    {
        return $this->belongsTo(ItensVenda::class, 'cod_produto_venda', 'id_produto');
    }

    public function tipoProduto()
    {
        return $this->belongsTo(Tipos::class, 'tipo_produto', 'id_tipo_produto');
    }
    

    

}
