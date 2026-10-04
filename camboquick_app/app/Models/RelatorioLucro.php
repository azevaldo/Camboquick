<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioLucro extends Model
{
    use HasFactory;
        protected $table="relatorios_lucro";
    protected $fillable=["quantidade","preco_venda_unitario","preco_custo_unitario","lucro_unitario","lucro_total","venda_id","produto_id"];

    public function produto(){
        return $this->belongsTo(Produto::class,'produto_id');
    }
    public function venda(){
        return $this->belongsTo(Venda::class,'venda_id');
    }
 
}
