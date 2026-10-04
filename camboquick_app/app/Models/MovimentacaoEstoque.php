<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    use HasFactory;
    protected $table='movimentacao__estoques'; 
    protected $fillable=['produto_id','quantidade','preco_compra','tipo','imposto','user_id'];
     
    public function produto(){
        return $this->belongsTo(Produto::class,'produto_id');
    }
    public function usuario(){
        return $this->belongsTo(User::class,'user_id');
    }
}
