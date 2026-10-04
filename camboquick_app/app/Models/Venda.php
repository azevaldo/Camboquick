<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'vendas';
    protected $fillable = ['total', 'quantidade','forma_pag','n_fatura'
    ,'user_id','cliente_id','nome_cliente','nif_cliente','contato_cliente','local_cliente',"status"];

    public function itensVenda()
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');

    }
    public function usuario(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

    public function relatorioLucros()
    {
        return $this->hasMany(RelatorioLucro::class, 'venda_id');

    }

}
