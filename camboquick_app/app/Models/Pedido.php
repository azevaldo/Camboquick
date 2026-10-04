<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['mesa_id', 'nome_cliente', 'status', 'total','user_id'];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
 public function usuario(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
                    ->withPivot('quantidade', 'preco', 'subtotal')
                    ->withTimestamps();
    }
}
