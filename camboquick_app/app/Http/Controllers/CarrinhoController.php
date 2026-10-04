<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;
use App\Models\ItemVenda;
use App\Models\Venda;
use Illuminate\Support\Facades\Log;
use Darryldecode\Cart\Facades\CartFacade as Cart;
class CarrinhoController extends Controller
{
    //
    public function finalizar(Request $request){
        try{
            //    protected $fillable = ['nome_cliente', 'total', 'quantidade'];
            $request->validate([
                "nome" => "required",
            ]);

            $venda=Venda::create([
                "nome_cliente"=>$request->nome,
                "total"=>"0",
                "quantidade"=>"0",
            ]);
            $qntProd=0;
            //    protected $fillable = ['produto_id', 'venda_id', 'quantidade', 'preco', 'total'];
            foreach(Cart::getContent() as $item){
                $venda->itensVenda()->create([
                    "produto_id" => $item->id,
                    "quantidade"=>$item->quantity,
                    "preco"=>$item->price,
                    "total"=>$item->price * $item->quantity,
                ]);
                $qntProd+=$item->quantity;
            }
            $venda->quantidade=$qntProd;
            $venda->total=Cart::getSubTotal();
            $venda->save();
            Cart::clear();
            return redirect()->back()->with("success","Venda Efetuada Com Sucesso");
        }catch(\Exception $e){
            Log::error("Ocorreu um erro ".$e->getMessage());
            return redirect()->back()->with("error","Ocorreu Erro  : ".$e->getMessage());
        }
    }
    public function index(){
        try{
            $cartItems=Cart::getContent();
            $itensAgrupadosPorCategoria=$cartItems->groupBy("attributes.categoria_id");
            $total=Cart::getSubtotal();
            $totalProdutos=0;
            $categorias=[];
            foreach($itensAgrupadosPorCategoria as $categoria_id=>$items){
                $categorias[$categoria_id]=Categoria::findOrFail($categoria_id);
                foreach($items as $item){
                    $totalProdutos+=$item->quantity;
                }
            }
            $totalCategorias=count($categorias);
            return view("app.carrinho.index",compact([
                "itensAgrupadosPorCategoria",
                "total",
                "totalProdutos",
                "totalCategorias",
                "categorias"
            ]));
           }catch(\Exception $e){
            Log::error("Ocorreu um erro ".$e->getMessage());
            return redirect()->back()->with("error","Ocorreu Erro  : ".$e->getMessage());
        }
    }
    public function remove($id){
        try{
        Cart::remove($id);
        return redirect()->back()->with("success","Item removido do carrinho");
    }catch(\Exception $e){
        Log::error("Ocorreu um erro ".$e->getMessage());
        return redirect()->back()->with("error","Ocorreu Erro  : ".$e->getMessage());
    }
    }
    public function clear(){
        try{
        Cart::clear();
        return redirect()->back()->with("success","Carrinho esvaziado Com Sucesso");
    }catch(\Exception $e){
        Log::error("Ocorreu um erro ".$e->getMessage());
        return redirect()->back()->with("error","Ocorreu Erro  : ".$e->getMessage());
    }
    }
    public function add(Request $request,$id){
        $request->validate([
            "quantidade"=>"required|integer",
        ]);
        $produto=Produto::findOrFail($id);
       
        $carrinho=Cart::Add([
            "id"=>$produto->id,
            "name"=>$produto->nome,
            "price"=>$produto->preco,
            "quantity"=>$request->quantidade,
            "attributes"=>array(
                "categoria_id"=>$produto->categoria_id
            )
        ]);
        return redirect()->back()->with("success","Produto Adicionado Com Sucesso no carrinho");

    }
}
