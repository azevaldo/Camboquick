<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioEstoque extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'inventario_estoque';

    protected $fillable = [
        'inventario_id',
        'produto_id',
        'qnt1_grosso',
        'qnt1_restante',
        'qnt1_retalho'
    ];

    // Relacionamento com o inventário
    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    // Relacionamento com o produto
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
