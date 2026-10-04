<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Empresa;
use App\Models\MovimentacaoEstoque;
use App\Models\RelatorioLucro;
use App\Models\User;
use App\Notifications\ProdutoEstoqueBaixo;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 

class VendaController extends Controller
{
 
    public function __construct(){
        $this->middleware('roles:admin,gerente')->only(['destroy','edit','update','arquivar']);
        $this->middleware('roles:caixa')->only(['index']);
    }
    public function index()
    {
        //
        try {

        $vendas=Venda::with(['itensVenda','usuario'])->where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->get();
        return view('app.paginas.vendas.index',compact('vendas'));
    } catch (Exception $e) {
        Log::error("Ocorreu um erro : " . $e->getMessage());
        return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
    }
    }

        public function lista()
    {
        //
        try {

        $vendas=Venda::with(['itensVenda','usuario'])->orderBy('created_at','DESC')->get();
        return view('app.paginas.vendas.index2',compact('vendas'));
    } catch (Exception $e) {
        Log::error("Ocorreu um erro : " . $e->getMessage());
        return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
    }
    }

    public function fatura($id){
        try{
        $venda=venda::with(['itensVenda','usuario'])->find($id);
        if(!$venda){
            return redirect()->back()->with('error','Venda Não Existe');
        }
        $valor_tributavel=0;
        $tot_imposto=0;
        foreach($venda->itensVenda as $item){
            $valor_tributavel+=$item->quantidade*$item->preco;
            $tot_imposto+=($item->quantidade*$item->preco)*($item->imposto/100);
        }
        $empresa=Empresa::first();
        return view('app.paginas.vendas.fatura',compact('venda','tot_imposto','valor_tributavel','empresa'));
    } catch (Exception $e) {
        Log::error("Ocorreu um erro : " . $e->getMessage());
        return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
    }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create2()
    {
        //
        $produtos=Produto::where('status','Valido')->get();
        return  view('app.paginas.vendas.create',compact('produtos'));
    
    }
    public function create()
    {
        //
        try{
             $turno=auth()->user()->turnos()->where('estado','aberto')->first();
            if(!$turno){
                 session()->put('error', 'Nenhum Turno Aberto, Não é possivel efectuar vendas sem abrir um turno');

                return redirect()->route('produtos.index');
 
             }
             $produtos=Produto::where('status','Valido')->get();
        return  view('app.paginas.vendas.create2',compact('produtos'));
      } catch (Exception $e) {
        Log::error("Ocorreu um erro : " . $e->getMessage());
        

           session()->put('error', 'Ocorreu um erro: ' . $e->getMessage());
           return redirect()->back();
    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     public function store(Request $request)
     {
         try {
         // Validação básica
         $request->validate([
            'nome_cliente' => 'nullable|string',
        'produtos' => 'required|array|min:1',
        'produtos.*.produto_id' => 'required|exists:produtos,id',
        'produtos.*.quantidade' => 'required|integer|min:1',
        'produtos.*.preco' => 'required|numeric|min:0',
    'produtos.*.imposto' => 'required|numeric|min:0',
  
    ]);

    DB::beginTransaction();

        $total = 0;

        // Calcular total do pedido
        foreach ($request->produtos as $item) {
      $total = bcadd($total, bcmul(bcmul((string)$item['quantidade'], (string)$item['preco'], 6), bcadd('1', bcdiv((string)$item['imposto'], '100', 6), 6), 6), 6);

        }
 
        // Criar Venda
$venda = Venda::create([
       'nome_cliente' =>$request->nome_cliente?$request->nome_cliente:'Cliente Final',
       'nif_cliente'=> 'Nif Final',
       'contato_cliente'=> 'Contato Final',
       'local_cliente'=> 'Local Final',
       'total' => $total,
       'user_id'=>Auth::user()->id,
   ]);


              $quant=0;
        // Inserir os produtos do pedido

        foreach ($request->produtos as $item) {

   ItemVenda::create([
           'produto_id' => $item['produto_id'],
           'venda_id' => $venda->id,
           'quantidade' => $item['quantidade'],
           'preco' => $item['preco'],
           'imposto'=> $item['imposto'],
    'total' => bcmul(bcmul((string)$item['quantidade'], (string)$item['preco'], 6), bcadd('1', bcdiv((string)$item['imposto'], '100', 6), 6), 6),

       ]);
          
            $quant+=$item['quantidade'];
            // Atualizando o estoque de produtos
            $produto = Produto::find($item['produto_id']);
        
            $temp=$produto->quantidade_retalho- $item['quantidade'];
            $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
            $produto->quantidade_retalho =$temp;
            $produto->save();


            //lucro
            $precisao=20;
            $custo_m_unitario=strval($produto->custo_m_p_retalho) ;
            $preco_venda_unitario=strval($item['preco'])  ;
            $quantidade2=strval($item['quantidade']) ;

            $lucro_unitario= bcsub($preco_venda_unitario,$custo_m_unitario,$precisao) ;
            $lucro_total=bcmul($lucro_unitario,$quantidade2,$precisao) ;


     try {
    RelatorioLucro::create([
        'quantidade' => $quantidade2,
        'preco_venda_unitario' => $preco_venda_unitario,
        'preco_custo_unitario' => $custo_m_unitario,
        'lucro_unitario' => $lucro_unitario,
        'lucro_total' => $lucro_total,
        'venda_id' => $venda->id,
        'produto_id' => $produto->id,
    ]);
} catch (Exception $e2) {
    throw new \Exception('Erro ao registrar lucro: ' . $e2->getMessage());
}


           

  if ($produto->quantidade <= $produto->limite_minimo) {
    $usuarios = User::role(['admin', 'gerente'])->get();

    foreach ($usuarios as $usuario) {
        $usuario->notify(new ProdutoEstoqueBaixo($produto));
    }
}

           MovimentacaoEstoque::create([
           'produto_id'=>$produto->id,
           'quantidade'=> $item['quantidade'],
           'preco_compra'=>bcmul(strval( $item['preco']),strval($item['quantidade']),6),
           'tipo'=>'Saida',
           'user_id'=>Auth::user()->id,
       ]);
        }
$venda->quantidade=$quant;
      $venda->n_fatura="FR A1VR".now()->year."/".$venda->id;
 $venda->save();
        DB::commit();

        return redirect()->route('vendas.create')->with('success', 'Venda Feita com sucesso!');
    } catch (Exception $e) {
        DB::rollback();
        return redirect()->route('vendas.create')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}
    public function store2(Request $request)
    {
        try{
        $data = $request->validate([
            'nome_cliente' => 'nullable|string',
            'nif_cliente'=>'nullable',
            'contato_cliente'=>'nullable',
            'local_cliente'=>'nullable',
            'itens' => 'required|array',
            'total' => 'required|numeric',
        ]);


       DB::beginTransaction();
        // Criando a venda
        $venda = Venda::create([
            'nome_cliente' => $data['nome_cliente']?$data['nome_cliente']:'Cliente Final',
            'nif_cliente'=> $data['nif_cliente']?$data['nif_cliente']:'Nif Final',
            'contato_cliente'=>  $data['contato_cliente']?$data['contato_cliente']:'Contato Final',
            'local_cliente'=> $data['local_cliente']?$data['local_cliente']:'Local Final',
            'total' => $data['total'],
            'user_id'=>Auth::user()->id,
        ]);

        $quant=0;

        foreach ($data['itens'] as $item){
            // Criando item da venda
            ItemVenda::create([
                'produto_id' => $item['id'],
                'venda_id' => $venda->id,
                'quantidade' => $item['quantidade'],
                'preco' => $item['preco'],
                'imposto'=> $item['imposto'],
                'total' => $item['quantidade'] * $item['preco']*(1+$item['imposto']/100),
            ]);

            $quant+=$item['quantidade'];
            // Atualizando o estoque de produtos
            $produto = Produto::find($item['id']);
        
            $temp=$produto->quantidade_retalho- $item['quantidade'];
            $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
            $produto->quantidade_retalho =$temp;
            $produto->save();

            MovimentacaoEstoque::create([
                'produto_id'=>$produto->id,
                'quantidade'=> $item['quantidade'],
                'preco_compra'=>$item['preco']*$item['quantidade'],
                'tipo'=>'Saida',
                'user_id'=>Auth::user()->id,
            ]);

        }
        $venda->quantidade=$quant;
        //strtoupper(Str::random(10)),
        $venda->n_fatura="FR A1VR".now()->year."/".$venda->id;
      $venda->save();
      DB::commit();
        return response()->json(['success' => true]);

    } catch (Exception $e) {
        // Em caso de erro, reverte a transação
      DB::rollBack();
        Log::error("Erro : " . $e->getMessage());
        return response()->json(['success'=>false]);
         
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
      
        
            DB::beginTransaction();
            $venda = Venda::findOrFail($id);
            $venda->status="Cancelada";
            // Criando a venda
          
            $quant=0;
    
            foreach ($venda->itensVenda as $item){
                // Criando item da venda
             
    
               
                // Atualizando o estoque de produtos
                $produto = Produto::find($item->id);
                $temp=$produto->quantidade_retalho+$item->quantidade;
                $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
                $produto->quantidade_retalho =$temp;
                $produto->save();
    
                MovimentacaoEstoque::create([
                    'produto_id'=>$produto->id,
                    'quantidade'=> $item->quantidade,
                    'preco_compra'=>$item->preco*$item->quantidade,
                    'tipo'=>'Saida Cancelada',
                    'user_id'=>Auth::user()->id,
                ]);
    
            }
       
          $venda->save();
          DB::commit();
          return redirect()->back()->with("success", "Venda Cancelada com sucesso!");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao Cancelar Venda: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    
    }

    public function cancelar($id)
    {
        //
        try {
      
        $venda = Venda::findOrFail($id);
            if($venda!=null && $venda->status=='Valida'){
  DB::beginTransaction();
            
            

            $venda->status="Cancelada";
            // Criando a venda
          
            
    
            foreach ($venda->itensVenda as $item){
                // Criando item da venda
             
    
               
                // Atualizando o estoque de produtos
                $produto = Produto::find($item->produto_id);
                $temp=$produto->quantidade_retalho+$item->quantidade;
                $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
                $produto->quantidade_retalho =$temp;
               
                $produto->save();
    
                MovimentacaoEstoque::create([
                    'produto_id'=>$produto->id,
                    'quantidade'=> $item->quantidade,
                    'preco_compra'=> bcmul(strval($item->preco),strval($item->quantidade),6)   ,
                    'tipo'=>'Saida Cancelada',
                    'user_id'=>Auth::user()->id,
                ]);
    
            }
       
          $venda->save();
          DB::commit();
          return redirect()->back()->with("success", "Venda Cancelada com sucesso!");

            }else{

                 return redirect()->back()->with("error", "Está Venda já foi cancelada ou mesmo não existe! " );
            }

          
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao Cancelar Venda: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    



    }














       public function sombraStore(Request $request)
     {
         try {
         // Validação básica
         $request->validate([
            'nome_cliente' => 'nullable|string',
        'produtos' => 'required|array|min:1',
        'produtos.*.produto_id' => 'required|exists:produtos,id',
        'produtos.*.quantidade' => 'required|integer|min:1',
        'produtos.*.preco' => 'required|numeric|min:0',
    'produtos.*.imposto' => 'required|numeric|min:0',
  
    ]);

    DB::beginTransaction();

        $total = 0;

        // Calcular total do pedido
        foreach ($request->produtos as $item) {
      $total = bcadd($total, bcmul(bcmul((string)$item['quantidade'], (string)$item['preco'], 6), bcadd('1', bcdiv((string)$item['imposto'], '100', 6), 6), 6), 6);

        }

        // Criar Venda
$venda = Venda::create([
       'nome_cliente' =>$request->nome_cliente?$request->nome_cliente:'Cliente Final',
       'nif_cliente'=> 'Nif Final',
       'contato_cliente'=> 'Contato Final',
       'local_cliente'=> 'Local Final',
       'total' => $total,
       'user_id'=>Auth::user()->id,
   ]);


              $quant=0;
        // Inserir os produtos do pedido

        foreach ($request->produtos as $item) {

   ItemVenda::create([
           'produto_id' => $item['produto_id'],
           'venda_id' => $venda->id,
           'quantidade' => $item['quantidade'],
           'preco' => $item['preco'],
           'imposto'=> $item['imposto'],
    'total' => bcmul(bcmul((string)$item['quantidade'], (string)$item['preco'], 6), bcadd('1', bcdiv((string)$item['imposto'], '100', 6), 6), 6),

       ]);
          
            $quant+=$item['quantidade'];
            // Atualizando o estoque de produtos
            $produto = Produto::find($item['produto_id']);
        
            $temp=$produto->quantidade_retalho- $item['quantidade'];
            $produto->quantidade=intdiv($temp,$produto->quant_uni_grosso) ;
            $produto->quantidade_retalho =$temp;
            $produto->save();


            //lucro
            $precisao=20;
            $custo_m_unitario=strval($produto->custo_m_p_retalho) ;
            $preco_venda_unitario=strval($item['preco'])  ;
            $quantidade2=strval($item['quantidade']) ;

            $lucro_unitario= bcsub($preco_venda_unitario,$custo_m_unitario,$precisao) ;
            $lucro_total=bcmul($lucro_unitario,$quantidade2,$precisao) ;

            RelatorioLucro::create([
                'quantidade'=>$quantidade2,
                'preco_venda_unitario'=>$preco_venda_unitario,
                'preco_custo_unitario'=>$custo_m_unitario,
                'lucro_unitario'=>$lucro_unitario,
                'lucro_total'=>$lucro_total,
                'venda_id'=>$venda->id,
                'produto_id'=>$produto->id,
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
           'preco_compra'=>bcmul(strval( $item['preco']),strval($item['quantidade']),6),
           'tipo'=>'Saida',
           'user_id'=>Auth::user()->id,
       ]);
        }
$venda->quantidade=$quant;
      $venda->n_fatura="FR A1VR".now()->year."/".$venda->id;
 $venda->save();
        DB::commit();

        return redirect()->route('vendas.create')->with('success', 'Venda Feita com sucesso!');
    } catch (Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}
}
