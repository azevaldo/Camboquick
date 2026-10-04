<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCompra extends Model
{
    use HasFactory;
    /**
     *  $table->integer('quantidade');
            $table->decimal('custo_total');
            $table->decimal('custo_unitario');
            $table->integer('imposto')->default(0);
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade')->onUpdate('cascade');
     */
    protected $table = 'itens_de_compra';
    protected $fillable = ['produto_id', 'compra_id', 'quantidade', 'custo_total', 'custo_unitario','imposto'];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }
}
