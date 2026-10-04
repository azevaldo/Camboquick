<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioPerda extends Model
{
    use HasFactory;
     protected $table="relatorios_perda";
    protected $fillable=["quantidade_perdida","preco_unitario_perda","valor_total_perda","perda_id","produto_id"];
    
    public function produto(){
        return $this->belongsTo(Produto::class,'produto_id');
    }
    public function Perda(){
        return $this->belongsTo(Perda::class,'perda_id');
    }
}
