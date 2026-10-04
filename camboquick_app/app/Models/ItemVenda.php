<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    use HasFactory;

    protected $table = 'item_vendas';
    protected $fillable = ['produto_id', 'venda_id', 'quantidade', 'preco', 'total','imposto'];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }
}
