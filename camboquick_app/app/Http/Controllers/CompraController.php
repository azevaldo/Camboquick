<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Compra;
use App\Models\SubCategoria;
use App\Models\Empresa;
use App\Models\Fornecedor;
use App\Models\MovimentacaoEstoque;
use App\Models\Produto;
use App\Models\ItemCompra;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  /*  public function __construct(){
        $this->middleware('roles:admin,gerente')->only(['destroy','edit','update']);
    }
        */
    public function index()
    {
        //
        try {
        $compras=Compra::with(['produto','usuario','fornecedor'])->orderBy('created_at','DESC')->get();

        return view('app.paginas.compras.index',compact('compras'));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->route('produtos.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    
    }
    public function fatura($id){
        try{
        $compra=Compra::with(['produto','usuario','fornecedor','itensCompra'])->find($id);
        if(!$compra){
            return redirect()->route('compras.index')->with('error','Compra Não Existe');
        }
        $valor_tributavel=0;
        $tot_imposto=0;
        foreach($compra->itensCompra as $item){
            $valor_tributavel+=$item->quantidade*$item->custo_unitario;
            $tot_imposto+=($item->quantidade*$item->custo_unitario)*($item->imposto/100);
        }
        $empresa=Empresa::first();
        return view('app.paginas.compras.fatura',compact('compra','tot_imposto','valor_tributavel','empresa'));
     } catch (\Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->route('compras.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        try{
         $subcategorias=SubCategoria::where('status','Valido')->get();
         $categorias=Categoria::where('status','Valido')->get();
               $produtos=Produto::where('status','Valido')->get();
        $fornecedores=Fornecedor::where('status','Valido')->get();

        return view('app.paginas.compras.create',compact('categorias','fornecedores','subcategorias','produtos'));
   } catch (\Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->route('compras.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store23(Request $request)
    {
        try{
        $request->validate([
            'produto_id'=>'required|exists:produtos,id',
            'quantidade'=>'required|integer|min:0',
            'preco_compra'=>'required|min:0'
        ]);
        $custo_unitario=$request->preco_compra/$request->quantidade;
        $compra=Compra::create([
            'produto_id'=>$request->produto_id,
            'quantidade'=>$request->quantidade,
            'preco_compra'=>$request->preco_compra,
            'custo_unitario'=>$custo_unitario,
            'user_id'=>Auth::user()->id,
        ]);
        
        $produto=Produto::findOrFail($request->produto_id);
        $produto->quantidade_retalho=($compra->quantidade* $produto->quant_uni_grosso)+$produto->quantidade_retalho;
        $produto->quantidade=$produto->quantidade+ $compra->quantidade;
      
         $custoPond=(( $produto->quantidade*$produto->custo_compra)+(  $request->quantidade* $request->preco_compra))/(  $request->quantidade+ $produto->quantidade);
        $produto->custo_m_p=$custoPond;
        $produto->custo_compra= $compra->preco_compra; 
        $produto->save();

         MovimentacaoEstoque::create([
            'produto_id'=>$produto->id,
            'quantidade'=>$compra->quantidade,
            'preco_compra'=>$compra->preco_compra,
            'tipo'=>'Entrada',
            'user_id'=>Auth::user()->id,
         ]);
         return redirect()->back()->with('success','Stock atualizado com Sucesso');
        } catch (\Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
         try {
        // Validação dos dados

        $request->validate([
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'numero_fatura' => 'required|unique:compras,n_fatura',
            'produtos' => 'required|array',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|numeric|min:1',
            'produtos.*.custo_unitario' => 'required|numeric|min:0',
            'produtos.*.imposto' => 'required|numeric|min:0',
        ]);

        // Inicia uma transação para garantir que tudo seja salvo corretamente
        DB::beginTransaction();

       
            // Cria a compra
            $compra = Compra::create([
                'n_fatura' => $request->numero_fatura,
                'fornecedor_id' => $request->fornecedor_id,
                'user_id' => Auth::id(),
                'quantidade_total' => 0, // Será calculado depois
                'custo_total' => 0, // Será calculado depois
            ]);

            $quantidadeTotal = 0;
            $custoTotal = 0;

            // Para cada produto inserido no formulário
            foreach ($request->produtos as $produto) {
                // Cria o item de compra
                $item = new ItemCompra([
                    'produto_id' => $produto['produto_id'],
                    'quantidade' => $produto['quantidade'],
                    'custo_unitario' => $produto['custo_unitario'],
                    'imposto' => $produto['imposto'],
                    'custo_total' =>bcmul(bcmul((string)$produto['quantidade'], (string)$produto['custo_unitario'], 6), bcadd('1', bcdiv((string)$produto['imposto'], '100', 6), 6), 6),
                ]);

                // Associa o item à compra
                $compra->itensCompra()->save($item);

                // Acumula os valores para calcular o total da compra
                $quantidadeTotal += $produto['quantidade'];
                $custoTotal =bcadd($custoTotal,strval( $item->custo_total),6);


                $produto2=Produto::findOrFail($produto['produto_id']);
             //   $produto2->quantidade=$produto2->quantidade+$produto['quantidade'];
                $produto2->quantidade_retalho=( $produto['quantidade']* $produto2->quant_uni_grosso)+$produto2->quantidade_retalho;
                
        //calculode custos
        $precisao=20;
        $casas_monetarias=2;
          // Quantidades e valores como strings
        $qtd1 = strval($produto2->quantidade);
        $qtd2 = strval($produto['quantidade']);
        $custo1 = strval($produto2->custo_m_p);
        $custo2 = strval($produto['custo_unitario']);
        $uniGrosso = strval($produto2->quant_uni_grosso);


            // Parte de cima do cálculo: (qtd1 * custo1) + (qtd2 * custo2)
        $parcial1 = bcmul($qtd1, $custo1, $precisao);
        $parcial2 = bcmul($qtd2, $custo2, $precisao);
        $somaParcial = bcadd($parcial1, $parcial2, $precisao);

// Soma das quantidades
$somaQtd = bcadd($qtd1, $qtd2, 0); // pode ser inteiro


// Custo médio ponderado (evita divisão por zero)
$custo_calculado = bccomp($somaQtd, '0',$precisao) !== 0
    ? bcdiv($somaParcial, $somaQtd, $precisao)
    : '0.00';

//$custoPond =$this->bcround2($custo_calculado,$casas_monetarias);
$custoPond =$custo_calculado;
// Atribuição ao campo
$produto2->custo_m_p = $custoPond;

// custo_m_p_retalho
$produto2->custo_m_p_retalho = bccomp($uniGrosso, '0',$precisao) !== 0
    ? bcdiv($custoPond, $uniGrosso, $precisao)
    : '0.000000';

          

if (bccomp($uniGrosso, '0', $precisao) !== 0) {
    // Faz a divisão com alta precisão
    $custo_retalho_preciso = bcdiv($custoPond, $uniGrosso, $precisao);

    // Arredonda para 2 casas decimais (ou o que quiser)
   // $produto2->custo_m_p_retalho = $this->bcround2($custo_retalho_preciso, 4); // ex: 2 casas
      $produto2->custo_m_p_retalho = $custo_retalho_preciso;

} else {
    $produto2->custo_m_p_retalho = '0.00';
}






                //    dd($produto['quantidade'],$produto['custo_unitario'],$produto['quantidade'], $produto2->quantidade);
         // $custoPond=(( $produto2->quantidade*$produto2->custo_m_p)+(  $produto['quantidade']* $produto['custo_unitario']))/(  $produto['quantidade']+ $produto2->quantidade);
                //custo medio ponderado a grosso 
           //     $produto2->custo_m_p=$custoPond;
              
             //   $produto2->custo_m_p_retalho=($produto2->quant_uni_grosso!=0?$custoPond/$produto2->quant_uni_grosso:0) ;
 
                
                $produto2->custo_compra_ante=$produto2->custo_compra;
                $produto2->custo_compra= $item->custo_total; 
                $produto2->custo_u_compra=$produto['custo_unitario'];
                $produto2->custo_u_retalho_compra=$produto['custo_unitario']/$produto2->quant_uni_grosso;
                $produto2->quantidade=$produto2->quantidade+ $produto['quantidade'];
                $produto2->save();

                //nepas
                MovimentacaoEstoque::create([
                    'produto_id'=>$produto2->id,
                    'quantidade'=>$item->quantidade,
                    'preco_compra'=>$item->custo_total,
                    'tipo'=>'Entrada',
                    'user_id'=>Auth::user()->id,
                 ]);
            }

            // Atualiza a compra com os valores totais
            $compra->update([
                'quantidade_total' => $quantidadeTotal,
                'custo_total' => $custoTotal,
            ]);

            // Confirma a transação
            DB::commit();

            return redirect()->route('compras.index')->with('success', 'Compra adicionada com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, reverte a transação
            DB::rollBack();
            return back()->with('error', 'Erro ao adicionar a compra: ' . $e->getMessage());
        }
    }

    function bcround2($number, $precision = 2)
{
    $precision = (int)$precision;
    $factor = bcpow('10', $precision + 1);
    $tmp = bcmul($number, $factor, 0);
    $lastDigit = (int)bcmod($tmp, '10');
    $number = bcdiv($number, '1', $precision);

    if ($lastDigit >= 5) {
        $increment = bcdiv('1', bcpow('10', $precision), $precision);
        return bcadd($number, $increment, $precision);
    }

    return $number;
}

    function bcround25(string $number, int $precision = 2): string {
    // Adiciona "0.005" (ajustado pela precisão) para forçar o arredondamento
    $add = '0.' . str_repeat('0', $precision - 1) . '5';
    if (bccomp($number, '0', $precision + 1) >= 0) {
        return bcadd($number, $add, $precision);
    } else {
        return bcsub($number, $add, $precision);
    }
}

function bcround288(string $number, int $precision = 2): string
    {
        // Precisão interna: uma casa decimal a mais que a desejada
        $internalPrecision = $precision + 1;

        // Define o valor a ser somado/subtraído para arredondar
        // Ex: para 2 casas -> soma 0.005 (caso positivo) ou subtrai 0.005 (caso negativo)
        $add = '0.' . str_repeat('0', $internalPrecision - 1) . '5';

        // Aplica o arredondamento correto conforme o sinal do número
        if (bccomp($number, '0', $internalPrecision) >= 0) {
            $rounded = bcadd($number, $add, $internalPrecision);
        } else {
            $rounded = bcsub($number, $add, $internalPrecision);
        }

        // Corta para a precisão final desejada (ex: 2 casas)
        return bcadd($rounded, '0', $precision);
    }





    public function cancelar($id)
    {
        //
        try {
              $compra = Compra::findOrFail($id);
       if($compra!=null && $compra->status=='Valida'){
 DB::beginTransaction();
          
            $compra->status="Cancelada";
            // Criando a venda
          
            
    
            foreach ($compra->itensCompra as $item) {
                // Cria o item de compra

                // Associa o item à compra

                // Acumula os valores para calcular o total da compra
          

                $produto2=Produto::findOrFail($item->produto_id );
                $precisao=20;
                $casas_monetarias=2;
                //recalcular o custo medio ponderado
               $denominador = bcsub(strval( $produto2->quantidade),$item->quantidade,$precisao);

if (bccomp($denominador,'0',$precisao)  === 0) {
   
    $novoCustoPonderado = 0.00;  
} else {

 
$mult1=bcmul(strval($produto2->custo_m_p),strval($produto2->quantidade),$precisao);
$mult2=bcmul(strval($item->custo_unitario),strval($item->quantidade),$precisao);
$sub1=bcsub($mult1,$mult2,$precisao);
$custo_calculado=bcdiv($sub1,$denominador,$precisao);

//$novoCustoPonderado =$this->bcround2($custo_calculado,$casas_monetarias);
$novoCustoPonderado =$custo_calculado;

//dd('Mult1 : '.$mult1,'Mult2 : '.$mult2,'Sub1 : '.$sub1,'Novo Custo ponderado : '.$novoCustoPonderado,'Denominador : '. $denominador,'qnt1 : '.$produto2->quantidade,'qnt1 : '.$item->quantidade);
     /*   $novoCustoPonderado =
      ( ($produto2->custo_m_p * $produto2->quantidade) - ($item->custo_unitario * $item->quantidade))
        / $denominador;
     
        */
  }
                $produto2->custo_m_p=$novoCustoPonderado;
                $quant_uni_grosso = strval($produto2->quant_uni_grosso);

if (bccomp($quant_uni_grosso, '0', $precisao) !== 0) {
    // Faz a divisão com alta precisão
    $custo_retalho_preciso = bcdiv($novoCustoPonderado, $quant_uni_grosso, $precisao);

    // Arredonda para 2 casas decimais (ou o que quiser)
   // $produto2->custo_m_p_retalho = $this->bcround2($custo_retalho_preciso, $casas_monetarias); // ex: 2 casas
            $produto2->custo_m_p_retalho = $custo_retalho_preciso;
} else {
    $produto2->custo_m_p_retalho = '0.00';
}

                $produto2->quantidade_retalho=$produto2->quantidade_retalho-($item->quantidade * $produto2->quant_uni_grosso);
                $produto2->quantidade=$produto2->quantidade-$item->quantidade;

               
                $produto2->custo_compra=0; 
                $produto2->custo_compra_ante=0;    
                             

               
                
                $produto2->save();

                //nepas
                MovimentacaoEstoque::create([
                    'produto_id'=>$produto2->id,
                    'quantidade'=>$item->quantidade,
                    'preco_compra'=>$item->custo_total,
                    'tipo'=>'Entrada Cancelada',
                    'user_id'=>Auth::id(),
                 ]);
            }

       
          $compra->save();
          DB::commit();
          return redirect()->route('compras.index')->with("success", "Compraa Cancelada com sucesso!");
       }else{
         return redirect()->route('compras.index')->with("error", " Está entrada já foi cancelada ou mesmo não existe!" );
       }
        
           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao Cancelar Compra: " . $e->getMessage());
            return redirect()->route('compras.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
}
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function show(Compra $compra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(Compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compra $compra)
    {
        //
    }
}
