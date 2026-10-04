<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\RelatorioLucro;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RelatorioLucroController extends Controller
{
    //
    public function index(){
        $produtos=Produto::where('status','Valido')->get();
        return view('app.paginas.lucro.consultas',compact('produtos'));
    }

     public function lucros_data(Request $request)
{
    try {
        $request->validate([
            'data' => 'required|date',]);

        $produtoId = $request->produto_id;

        $lucros = RelatorioLucro::whereDate('created_at', $request->data)
        
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
             ->whereHas('venda',function ($query){
                $query->where('status','Valida');
            })
            ->orderBy('created_at', 'desc')
            ->get();
 
        $total_lucros = RelatorioLucro::whereDate('created_at', $request->data)
        
          ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
           ->whereHas('venda',function ($query){
                $query->where('status','Valida');
            })
            ->sum('lucro_total');

        $n_lucros =  $lucros->count(); 


            $produto=Produto::find($produtoId); 
            if($produto==null){
                $pesquisa = "Data : " . Carbon::parse($request->data)->format('d/m/Y') ;
            }else{
               
                $pesquisa = "Data : " . Carbon::parse($request->data)->format('d/m/Y') ."___Produto : ".$produto->nome;
            }
    
      $lucro2="";

        $produtos = Produto::where('status','Valido')->get();
        return view('app.paginas.lucro.consultas', compact('lucros', 'total_lucros', 'n_lucros', 'pesquisa', 'produtos','lucro2'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('relatorio.lucros')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
} 


    public function lucros_periodo(Request $request)
{
    try {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
      
        ]);

        $data_inicio = Carbon::parse($request->data_inicio)->startOfDay();
        $data_fim = Carbon::parse($request->data_fim)->endOfDay();
        $produtoId = $request->produto_id;

        $lucros = RelatorioLucro::whereBetween('created_at', [$data_inicio, $data_fim])
            
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
            ->whereHas('venda',function ($query){
                $query->where('status','Valida');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $total_lucros = RelatorioLucro::whereBetween('created_at', [$data_inicio, $data_fim])
       
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
             ->whereHas('venda',function ($query){
                $query->where('status','Valida');
            })
            ->sum('lucro_total');

         $n_lucros =  $lucros->count(); 
            $produto=Produto::find($produtoId); 
            if($produto==null){
                $pesquisa = "Período: " . Carbon::parse($request->data_inicio)->format('d/m/Y') . " até " .  Carbon::parse($request->data_fim)->format('d/m/Y') ;

            }else{
                $pesquisa = "Período: " . $request->data_inicio . " até " . $request->data_fim ."______Produto : ".$produto->nome;
            }
       
    $lucro2="";
        $produtos = Produto::where('status','Valido')->get();
        return view('app.paginas.lucro.consultas', compact('lucro2','lucros', 'total_lucros', 'n_lucros', 'pesquisa', 'produtos'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('relatorio.lucros')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}

public function lucros_mes(Request $request)
{
    try {
        $request->validate([
            'mes' => 'required|date_format:Y-m',
            
        ]);

        $data = Carbon::createFromFormat('Y-m', $request->mes);
        $ano = $data->year;
        $mes = $data->month;
        $produtoId = $request->produto_id;

        $lucros = RelatorioLucro::whereYear('created_at', $ano)
            ->whereMonth('created_at', $mes)
           
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
              ->whereHas('venda',function ($query){
                $query->where('status','Valida');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $total_lucros = RelatorioLucro::whereYear('created_at', $ano)
            ->whereMonth('created_at', $mes)
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
              ->whereHas('venda',function ($query){
                $query->where('status','Valida');
            })
            ->sum('lucro_total');

        $n_lucros =  $lucros->count(); 

        $nome_mes = $data->translatedFormat('F');
        $produto=Produto::find($produtoId); 
        if($produto==null){
            $pesquisa = "Mês: $nome_mes ____ Ano: $ano " ;
        }else{
            $pesquisa = "Mês: $nome_mes ____ Ano: $ano "."_____Produto : ".$produto->nome;
        }
        $lucro2="";
        $produtos = Produto::where('status','Valido') ->get();
        return view('app.paginas.lucro.consultas', compact('lucros', 'total_lucros', 'n_lucros', 'pesquisa', 'produtos','lucro2'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('relatorio.lucros')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
} 

public function lucros_ano(Request $request)
{
    try {
        $request->validate([
            'ano' => 'required',
           
        ]);

        $produtoId = $request->produto_id;

        $lucros =RelatorioLucro::whereYear('created_at', $request->ano)
             ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
            ->whereHas('venda', function ($query){
                $query->where('status','Valida');
            })
             ->orderBy('created_at', 'desc')
            ->get();

        $total_lucros = RelatorioLucro::whereYear('created_at', $request->ano)
        
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
              ->whereHas('venda', function ($query){
                $query->where('status','Valida');
            })
            ->sum('lucro_total');

          $n_lucros =  $lucros->count(); 

           $produto=Produto::find($produtoId); 
           if($produto==null){
            $pesquisa = "Ano: " . $request->ano ;
           }else{
            $pesquisa = "Ano: " . $request->ano ."____Usuario : ".$produto->nome;
           }
        
           $lucro2=" ";
          $produtos = Produto::where('status','Valido') ->get();
        return view('app.paginas.lucro.consultas', compact('lucros', 'total_lucros', 'n_lucros', 'pesquisa', 'produtos','lucro2'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('relatorio.lucros')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}

}
