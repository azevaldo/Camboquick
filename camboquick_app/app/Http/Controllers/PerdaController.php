<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\MovimentacaoEstoque;
use App\Models\Perda;
use App\Models\Produto;
use App\Models\RelatorioPerda;
use App\Models\User;
use App\Notifications\ProdutoEstoqueBaixo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerdaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
        $perdas=Perda::orderBy('created_at','DESC')->get();
        return view('app.paginas.perdas.index',compact('perdas'));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        try{
               $produtos=Produto::where('status','Valido')->get();
        return view('app.paginas.perdas.create',compact('produtos'));
     } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
   
 
    public function store(Request $request)
    {
        try {
        // Validação básica
        $request->validate([
      
            'produtos' => 'required|array|min:1',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
             
      
        ]);

        DB::beginTransaction();
    
            $total = 0;

            // Calcular total do pedido
           /* foreach ($request->produtos as $item) {
                $total += $item['quantidade'] * $item['preco'];
            }*/
 
            // Criar pedido
            $perda = Perda::create([
                 'total' => 0,
                'user_id' => Auth::user()->id,
                'motivo'=>$request->motivo,
                  ]);
                  $quant=0;
            // Inserir os produtos do pedido
            foreach ($request->produtos as $item) {
               
                    $produto = Produto::find($item['produto_id']);
                $perda->produtos()->attach($item['produto_id'], [
                    'quantidade' => $item['quantidade'],
                    'preco' =>$produto ->custo_m_p_retalho,
                    'subtotal' =>bcmul(strval( $produto ->custo_m_p_retalho),strval($item['quantidade']),6)  ,
                ]);
                $quant+=$item['quantidade'];
               $total= bcadd(bcmul(strval($produto ->custo_m_p_retalho), strval($item['quantidade']),6),$total,6)   ;
                // Atualizando o estoque de produtos
           
            
                $temp=$produto->quantidade_retalho- $item['quantidade'];
                $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
                $produto->quantidade_retalho =$temp;
                $produto->save();
    

                    //perda
                $precisao=20;
                     $quantidade = $item['quantidade'];
                      $custo_medio = $produto->custo_m_p_retalho;
                     $valor_total = bcmul(strval( $quantidade),strval( $custo_medio),$precisao) ;


            RelatorioPerda::create([
        'produto_id' => $produto->id,
        'quantidade_perdida' => $quantidade,
        'perda_id' => $perda->id,
        'preco_unitario_perda' => $custo_medio,
        'valor_total_perda' => $valor_total
    ]);

      if ($produto->quantidade <= $produto->limite_minimo) {
    $usuarios = User::role(['admin', 'gerente'])->get();

    foreach ($usuarios as $usuario) {
        $usuario->notify(new ProdutoEstoqueBaixo($produto));
    }
}
                MovimentacaoEstoque::create([
                    'produto_id'=>$produto->id,
                    'quantidade'=> $item['quantidade'],
                    'preco_compra'=>$valor_total,
                    'tipo'=>'Perda',
                    'user_id'=>Auth::user()->id,
                ]);
            }
        $perda->total=$total;
        $perda->save();
            DB::commit();

            return redirect()->route('perdas.index')->with('success', 'Perda criada com sucesso!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('perdas.create')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }




    public function cancelar($id)
    {
        try {
        // Validação básica
     $perda=Perda::findOrFail($id);
  if($perda!=null && $perda->status=='Valida'){
 DB::beginTransaction();
    
           

            // Calcular total do pedido
           

            // Criar pedido

                  $perda=Perda::findOrFail($id);
                         // Inserir os produtos do pedido
            foreach ($perda->produtos as $item) {
                
                // Atualizando o estoque de produtos

               // Atualizando o estoque de produtos
               $produto = Produto::find($item->id);
               $temp=$produto->quantidade_retalho+$item->pivot->quantidade;
               $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
               $produto->quantidade_retalho =$temp;
              
               $produto->save();
   
               MovimentacaoEstoque::create([
                   'produto_id'=>$produto->id,
                   'quantidade'=> $item->pivot->quantidade,
                   'preco_compra'=>bcmul(strval($item->pivot->preco),strval($item->pivot->quantidade),6) ,
                   'tipo'=>'Perda Cancelada',
                   'user_id'=>Auth::user()->id,
               ]);
            }
        $perda->status='Cancelada';
        $perda->save();
            DB::commit();

            return redirect()->route('perdas.index')->with('success', 'Perda cancelada com sucesso!');
  }else{
     return redirect()->route('perdas.index')->with('error', 'Esta perda já foi cancelada ou mesmo não existe! ');
  }
       
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('perdas.index')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $perda=Perda::findOrFail($id);
        $empresa=Empresa::first();
        return view('app.paginas.perdas.documento',compact('perda','empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perda $perda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perda $perda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perda $perda)
    {
        //
    }
}
