<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\RelatorioPerda;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RelatorioPerdaController extends Controller
{
    //


     //
    public function index(){
        $produtos=Produto::where('status','Valido')->get();
        return view('app.paginas.perdas.consultas',compact('produtos'));
    }

     public function perdas_data(Request $request)
{
    try {
        $request->validate([
            'data' => 'required|date', ]);

        $produtoId = $request->produto_id;

        $perdas = RelatorioPerda::whereDate('created_at', $request->data)
        
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
             ->whereHas('perda',function ($query){
                $query->where('status','Valida');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $total_perdas = RelatorioPerda::whereDate('created_at', $request->data)
        
          ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
           ->whereHas('perda',function ($query){
                $query->where('status','Valida');
            })
            ->sum('valor_total_perda');

        $n_perdas =$perdas->count();

            $produto=Produto::find($produtoId); 
            if($produto==null){
                $pesquisa = "Data : " . Carbon::parse($request->data)->format('d/m/Y') ;
            }else{
               
                $pesquisa = "Data : " . Carbon::parse($request->data)->format('d/m/Y') ."___Produto : ".$produto->nome;
            }
    
      $perda2="";

        $produtos = Produto::where('status','Valido')->get();
        return view('app.paginas.perdas.consultas', compact('perdas', 'total_perdas', 'n_perdas', 'pesquisa', 'produtos','perda2'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('relatorio.perdas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
} 


    public function perdas_periodo(Request $request)
{
    try {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
      
        ]);

        $data_inicio = Carbon::parse($request->data_inicio)->startOfDay();
        $data_fim = Carbon::parse($request->data_fim)->endOfDay();
        $produtoId = $request->produto_id;

        $perdas= RelatorioPerda::whereBetween('created_at', [$data_inicio, $data_fim])
            
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
            ->whereHas('perda',function ($query){
                $query->where('status','Valida');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $total_perdas = RelatorioPerda::whereBetween('created_at', [$data_inicio, $data_fim])
       
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
             ->whereHas('perda',function ($query){
                $query->where('status','Valida');
            })
            ->sum('valor_total_perda');

               $n_perdas =$perdas->count();

            $produto=Produto::find($produtoId); 
            if($produto==null){
                $pesquisa = "Período: " . Carbon::parse($request->data_inicio)->format('d/m/Y') . " até " .  Carbon::parse($request->data_fim)->format('d/m/Y')  ;

            }else{
                $pesquisa = "Período: " . $request->data_inicio . " até " . $request->data_fim ."______Produto : ".$produto->nome;
            }
       
    $perda2="";
        $produtos = Produto::where('status','Valido')->get();
        return view('app.paginas.perdas.consultas', compact('perda2','perdas', 'total_perdas', 'n_perdas', 'pesquisa', 'produtos'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('relatorio.perdas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}

public function perdas_mes(Request $request)
{
    try {
        $request->validate([
            'mes' => 'required|date_format:Y-m',
            
        ]);

        $data = Carbon::createFromFormat('Y-m', $request->mes);
        $ano = $data->year;
        $mes = $data->month;
        $produtoId = $request->produto_id;

        $perdas = RelatorioPerda::whereYear('created_at', $ano)
            ->whereMonth('created_at', $mes)
           
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
              ->whereHas('perda',function ($query){
                $query->where('status','Valida');
            })
            ->orderBy('created_at', 'desc')
            ->get(); 

        $total_perdas = RelatorioPerda::whereYear('created_at', $ano)
            ->whereMonth('created_at', $mes)
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
              ->whereHas('perda',function ($query){
                $query->where('status','Valida');
            })
            ->sum('valor_total_perda');

               $n_perdas =$perdas->count();

        $nome_mes = $data->translatedFormat('F');
        $produto=Produto::find($produtoId); 
        if($produto==null){
            $pesquisa = "Mês: $nome_mes ____ Ano: $ano " ;
        }else{
            $pesquisa = "Mês: $nome_mes ____ Ano: $ano "."_____Produto : ".$produto->nome;
        }
        $perda2="";
        $produtos = Produto::where('status','Valido') ->get();
        return view('app.paginas.perdas.consultas', compact('perdas', 'total_perdas', 'n_perdas', 'pesquisa', 'produtos','perda2'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('relatorio.perdas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
} 

public function perdas_ano(Request $request)
{
    try {
        $request->validate([
            'ano' => 'required',
        ]);

        $produtoId = $request->produto_id;

        $perdas =RelatorioPerda::whereYear('created_at', $request->ano)
             ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
            ->whereHas('perda', function ($query){
                $query->where('status','Valida');
            })
             ->orderBy('created_at', 'desc')
            ->get();

        $total_perdas = RelatorioPerda::whereYear('created_at', $request->ano)
            ->when($produtoId, fn($query) => $query->where('produto_id', $produtoId))
              ->whereHas('perda', function ($query){
                $query->where('status','Valida');
            })
            ->sum('valor_total_perda');

               $n_perdas =$perdas->count();

           $produto=Produto::find($produtoId); 
           if($produto==null){
            $pesquisa = "Ano: " . $request->ano ;
           }else{
            $pesquisa = "Ano: " . $request->ano ."____Produto : ".$produto->nome;
           }
        
           $perda2=" ";
          $produtos = Produto::where('status','Valido') ->get();
        return view('app.paginas.perdas.consultas', compact('perdas', 'total_perdas', 'n_perdas', 'pesquisa', 'produtos','perda2'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('relatorio.perdas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}
}
