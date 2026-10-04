<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos'; 
    protected $fillable = ['nome', 'subCategoria_id',  'quantidade', 'preco'
    ,'custo_compra','custo_m_p','limite_minimo'
    ,'imposto','armazem_id','codigo','slug','quantidade_retalho',
    'quant_uni_grosso','custo_compra_ante','status','custo_u_retalho_compra','custo_m_p_retalho'];

 
    public function subCategoria()
    {
        return $this->belongsTo(SubCategoria::class, 'subCategoria_id');
    }

    public function armazem()
    {
        return $this->belongsTo(Armazem::class, 'armazem_id');
    }

  

    public function itensVenda()
    {
        return $this->hasMany(ItemVenda::class, 'produto_id');
    }
    public function compras(){
        return $this->hasMany(Compra::class,'produto_id');
    }
    public function movimentacoes(){
        return $this->hasMany(MovimentacaoEstoque::class,'produto_id');
    }
    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_produto')
                    ->withPivot('quantidade', 'preco', 'subtotal')
                    ->withTimestamps();
    }
    public  function perdas(){
        return $this->belongsToMany(Perda::class,'perda_produto')
        ->withPivot('quantidade', 'preco', 'subtotal')  ->withTimestamps();
    }

        public function relatorioLucros()
    {
        return $this->hasMany(RelatorioLucro::class, 'produto_id');

    }
}
