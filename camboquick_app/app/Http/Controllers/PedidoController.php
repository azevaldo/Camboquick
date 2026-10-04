<?php

namespace App\Http\Controllers;

use App\Models\ItemVenda;
use App\Models\Mesa;
use App\Models\MovimentacaoEstoque;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use App\Notifications\ProdutoEstoqueBaixo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    //
    
   
    public function semmesa()
    {
        //
        try{
        $pedidos=Pedido::where('mesa_id',null)->where('user_id',Auth::id())    ->select('*')
    ->orderByRaw("DATE(created_at) DESC") // ordena por data (sem considerar a hora)
    ->orderByRaw("FIELD(status, 'aberto', 'fechado', 'cancelado')") // ordena por status
    ->get()
    ;
 
        return view("app.paginas.pedidos.semMesa", compact("pedidos"));
     } catch (\Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
       public function semmesa2()
    {
        //
        try{
        $pedidos=Pedido::where('mesa_id',null)    ->select('*')
    ->orderByRaw("DATE(created_at) DESC") // ordena por data (sem considerar a hora)
    ->orderByRaw("FIELD(status, 'aberto', 'fechado', 'cancelado')") // ordena por status
    ->get()
    ;
 
        return view("app.paginas.pedidos.semMesa2", compact("pedidos"));
         } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }



     
    public function cancelar($id){
        try{
        $pedido=Pedido::findOrFail($id);
if($pedido!=null && $pedido->status=='aberto'){
 DB::beginTransaction();
    foreach ($pedido->produtos as $item ){

                $produto = Produto::find($item->id);
                $temp=$produto->quantidade_retalho+$item->pivot->quantidade;
                $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
                $produto->quantidade_retalho =$temp;
                $produto->save();

  }
        $pedido->status='cancelado';
        $pedido->save();
        DB::commit();
       return redirect()->back()->with('success', 'Pedido Cancelado com sucesso' );
}else{
        return redirect()->back()->with("error", "Esse pedido não existe ou já foi cancelado, ou já foi terminado " );
}

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }   
    public function listarPorMesa($mesa_id)
    {
        //
        try{
                $mesa=Mesa::findOrFail($mesa_id);
                $pedidos=$mesa->pedidos()->where('user_id',Auth::id())    ->select('*')
    ->orderByRaw("DATE(created_at) DESC") // ordena por data (sem considerar a hora)
    ->orderByRaw("FIELD(status, 'aberto', 'fechado', 'cancelado')") // ordena por status
    ->get()
    ;
                 } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }

        return view("app.paginas.pedidos.index", compact("pedidos","mesa"));
    }

       public function listarPorMesa2($mesa_id)
    {
        //
        try{
                $mesa=Mesa::findOrFail($mesa_id);
           $pedidos = $mesa->pedidos()
    ->select('*')
    ->orderByRaw("DATE(created_at) DESC") // ordena por data (sem considerar a hora)
    ->orderByRaw("FIELD(status, 'aberto', 'fechado', 'cancelado')") // ordena por status
    ->get()
    ;


        return view("app.paginas.pedidos.index2", compact("pedidos","mesa"));
     } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    public function create()
    {
        //
        try{
        $mesas=Mesa::where('status','Valido')->get();
        $pedidosSemMesa=Pedido::where('mesa_id',null)->where('status','aberto')->where('user_id',Auth::id())->count();
        $todosPedidos=Pedido::where('mesa_id',null)->where('status','aberto')->count();
        return view("app.paginas.pedidos.create", compact("mesas","pedidosSemMesa","todosPedidos"));
         } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function show($id)
    {
        //
        try{
        $pedido=Pedido::findOrFail($id);
              $produtos=Produto::where('status','Valido')->get();
        return view("app.paginas.pedidos.detalhes", compact("pedido","produtos"));
     } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function addPedido($mesa_id)
    {
        //
        try{
               $produtos=Produto::where('status','Valido')->get();
        $mesa="1";
        return view("app.paginas.pedidos.addPedido", compact("produtos","mesa_id","mesa"));

         } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function addPedido2()
    {
        //
        try{
              $produtos=Produto::where('status','Valido')->get();
        $mesa="2";
        return view("app.paginas.pedidos.addPedido", compact("produtos","mesa"));
         } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function finalizar(Request $request){
   
        try{
            $data = $request->validate([
                'pedido' => 'required',

            ]);
            $pedido=Pedido::findOrfail($request->pedido);
            
           DB::beginTransaction();
            // Criando a venda
            $venda = Venda::create([
                'nome_cliente' => $pedido->nome_cliente,
                'nif_cliente'=> "nulo",
                'contato_cliente'=> "nulo",
                'local_cliente'=> "nulo",
                'total' => $pedido->total,
                'user_id'=>Auth::user()->id,
            ]);
    
            $quant=0;
    
            foreach ($pedido->produtos as $item ){
                // Criando item da venda
                ItemVenda::create([
                    'produto_id' =>$item->id,
                    'venda_id' => $venda->id,
                    'quantidade' =>$item->pivot->quantidade,
                    'preco' =>$item->pivot->preco,
                    'imposto'=> 0,
                    'total' =>$item->pivot->quantidade *$item->pivot->preco*(1+0/100),
                ]);
    
                $quant+=$item->pivot->quantidade;
                // Atualizando o estoque de produtos
                $produto = Produto::find($item->id);
            
               /* $temp=$produto->quantidade_retalho- $item->pivot->quantidade;
                $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
                $produto->quantidade_retalho =$temp;
                $produto->save();*/
 if ($produto->quantidade <= $produto->limite_minimo) {
    $usuarios = User::role(['admin', 'gerente'])->get();

    foreach ($usuarios as $usuario) {
        $usuario->notify(new ProdutoEstoqueBaixo($produto));
    }
}
                
                MovimentacaoEstoque::create([
                    'produto_id'=>$produto->id,
                    'quantidade'=>$item->pivot->quantidade,
                    'preco_compra'=>$item->pivot->preco*$item->pivot->quantidade,
                    'tipo'=>'Saida',
                    'user_id'=>Auth::user()->id,
                ]);
    
            }
            $venda->quantidade=$quant;
            //strtoupper(Str::random(10)),
            $venda->n_fatura="FR A1VR".now()->year."/".$venda->id;
            $pedido->status="fechado";
          $venda->save();
          $pedido->save();
          DB::commit();
            if(Auth::user()->hasAnyRole('caixa')){
return redirect()->route("vendas.index")->with('success', 'Pedido Finalizado com sucesso' );

            }else if(Auth::user()->hasAnyRole(['admin','gerente'])){
return redirect()->route("vendas.lista")->with('success', 'Pedido Finalizado com sucesso' );

            }

            
                  
        } catch (Exception $e) {
            // Em caso de erro, reverte a transação
          DB::rollBack();
            Log::error("Erro : " . $e->getMessage());
           
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());

             
        }
    }
    public function update(Request $request,$id)
    {
        $pedido=Pedido::findOrfail($id);
        try {
        // Validação básica
        $request->validate([
            'produtos' => 'required|array|min:1',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco' => 'required|numeric|min:0',
        ]);
        DB::beginTransaction();
            $total = 0;

            // Calcular total do pedido
            foreach ($request->produtos as $item) {
                $total += $item['quantidade'] * $item['preco'];
            }

$pedido->total=$pedido->total+$total;
            // Criar pedido
          
 
            // Inserir os produtos do pedido
            foreach ($request->produtos as $item) {
                $produto_p=$pedido->produtos->firstWhere('id',$item['produto_id']);
                if($produto_p){
                
                    $pedido->produtos()->updateExistingPivot($item['produto_id'], 
                    ['quantidade' => $produto_p->pivot->quantidade+$item['quantidade'],
                    'subtotal' =>($produto_p->pivot->subtotal)+ $item['quantidade']*$produto_p->pivot->preco] );

                    $total += ($produto_p->pivot->subtotal)+ $item['quantidade']*$produto_p->pivot->preco;
                }else{
                     $pedido->produtos()->attach($item['produto_id'], [
                    'quantidade' => $item['quantidade'],
                    'preco' => $item['preco'],
                    'subtotal' => $item['quantidade'] * $item['preco'],
           
                ]);
                    $total+=$item['quantidade'] * $item['preco'];
                }
               

                $produto = Produto::find($item['produto_id']);
        
            $temp=$produto->quantidade_retalho- $item['quantidade'];
            $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
            $produto->quantidade_retalho =$temp;
            $produto->save();
            }
       // $pedido->total=$total;    
        $pedido->save();
            DB::commit();

            return redirect()->route("pedidos.show",$pedido->id)->with('success', 'Pedido atualizado com sucesso!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route("pedidos.show",$pedido->id)->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    public function atualizar(Request $request, $id)
{
     try {
    $pedido=Pedido::findOrFail($id);
    if ($pedido->status !== 'aberto') {
        return redirect()->back()->with('error', 'Apenas pedidos abertos podem ser atualizados.');
    }

    $total=0;

    foreach ($request->quantidades as $produtoId => $quantidade) {
    $produto = Produto::findOrFail($produtoId);
    $quantidadeMax = $produto->quantidade_retalho + $pedido->produtos()->find($produtoId)->pivot->quantidade;

    if ($quantidade < 0 || $quantidade > $quantidadeMax) {
        return back()->with('error', 'Quantidade inválida para o produto ' . $produto->nome);
    }
}

    foreach ($request->quantidades as $produtoId => $quantidade) {
        $produto=Produto::findOrFail($produtoId);
      
      
   
            $pivoProd=$pedido->produtos->firstWhere('id',$produtoId);
            $quantPivot=$pivoProd->pivot->quantidade;
            $temp=($produto->quantidade_retalho+$quantPivot)- $quantidade;
            $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
            $produto->quantidade_retalho =$temp;
            $produto->save();

            if($quantidade<= 0){
                $pedido->produtos()->detach($produtoId);
                continue;
            }
            $pedido->produtos()->updateExistingPivot($produtoId, ['quantidade' => $quantidade,'subtotal' => $quantidade*$produto->preco] );
            $total += $quantidade*$produto->preco;
    }
    $pedido->total=$total;
 $pedido->save();
    return redirect()->route('pedidos.show', $pedido->id)->with('success', 'Pedido atualizado com sucesso.');

     } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
}

    public function store(Request $request)
    {
        try {
        // Validação básica
        $request->validate([
            'nome_cliente' => 'nullable|string|max:255',
            'produtos' => 'required|array|min:1',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco' => 'required|numeric|min:0',
            'mesa_id'=> 'nullable',
        ]);

        DB::beginTransaction();
    
            $total = 0;

            // Calcular total do pedido
            foreach ($request->produtos as $item) {
                $total += $item['quantidade'] * $item['preco'];
            }

            // Criar pedido
            $pedido = Pedido::create([
                'nome_cliente' => $request->nome_cliente?$request->nome_cliente:'Cliente Final',
                'total' => $total,
                'status' => 'aberto',
                'mesa_id' => ($request->mesa_id)?$request->mesa_id:null,
                'user_id'=>Auth::id(),
            ]);

            // Inserir os produtos do pedido
            foreach ($request->produtos as $item) {
                $pedido->produtos()->attach($item['produto_id'], [
                    'quantidade' => $item['quantidade'],
                    'preco' => $item['preco'],
                    'subtotal' => $item['quantidade'] * $item['preco'],
           
                ]);
                 $produto = Produto::find($item['produto_id']);
        
            $temp=$produto->quantidade_retalho- $item['quantidade'];
            $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
            $produto->quantidade_retalho =$temp;
            $produto->save();
           
        }
        
            DB::commit();

            return redirect()->route('pedidos.create')->with('success', 'Pedido criado com sucesso!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
}
