<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $fillable=['produto_id','quantidade_total','custo_total','user_id','n_fatura','fornecedor_id',"status"];
   
    public function produto()
{
    return $this->belongsTo(Produto::class,'produto_id');
}
public function usuario(){
    return $this->belongsTo(User::class,'user_id');
}
public function itensCompra()
    {
        return $this->hasMany(ItemCompra::class, 'compra_id');

    }
public function fornecedor(){
    return $this->belongsTo(Fornecedor::class,'fornecedor_id');
}
}
