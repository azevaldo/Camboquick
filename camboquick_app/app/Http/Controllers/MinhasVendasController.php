<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MinhasVendasController extends Controller
{
    //
     public function index(){
        try{
        
        return view('app.paginas.minhas.vendas.consultas');
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }


    public function vendas_data(Request $request)
{
    try {
        $request->validate([
            'data' => 'required|date',
           
             'status'=>'required|in:Valida,Cancelada',
        ]);

        $userId = Auth::user()->id;

        $vendas = Venda::whereDate('created_at', $request->data)
        ->where('status',$request->status)
         ->where('user_id',$userId)
           
            ->orderBy('created_at', 'desc')
            ->get();

        $total_vendas = Venda::whereDate('created_at', $request->data)
        ->where('status',$request->status)
        ->where('user_id',$userId)
            ->sum('total');

        $n_vendas =  $vendas ->count();

            $user=User::find($userId); 
             
               
                $pesquisa = "Data : " . Carbon::parse($request->data)->format('d/m/Y') ."_____Usuario : ".$user->name;
         
    
       

        
        return view('app.paginas.minhas.vendas.consultas', compact('vendas', 'total_vendas', 'n_vendas', 'pesquisa'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('minhas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}   public function vendas_periodo(Request $request)
{
    try {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
          
             'status'=>'required|in:Valida,Cancelada',
        ]);

        $data_inicio = Carbon::parse($request->data_inicio)->startOfDay();
        $data_fim = Carbon::parse($request->data_fim)->endOfDay();
        $userId = Auth::user()->id;

        $vendas = Venda::whereBetween('created_at', [$data_inicio, $data_fim])
        ->where('status',$request->status)
           ->where('user_id',$userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $total_vendas = Venda::whereBetween('created_at', [$data_inicio, $data_fim])
        ->where('status',$request->status)
           ->where('user_id',$userId)
            ->sum('total');

           $n_vendas =  $vendas ->count();


            $user=User::find($userId); 

                $pesquisa = "Período: " . Carbon::parse($request->data_inicio)->format('d/m/Y') . " até " . Carbon::parse($request->data_fim)->format('d/m/Y')  ."______Usuario : ".$user->name;
            
       
 
        
        return view('app.paginas.minhas.vendas.consultas', compact('vendas', 'total_vendas', 'n_vendas', 'pesquisa'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('minhas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
} public function vendas_mes(Request $request)
{
    try {
        $request->validate([
            'mes' => 'required|date_format:Y-m',
        
             'status'=>'required|in:Valida,Cancelada',
        ]);

        $data = Carbon::createFromFormat('Y-m', $request->mes);
        $ano = $data->year;
        $mes = $data->month;
        $userId = Auth::user()->id;

        $vendas = Venda::whereYear('created_at', $ano)
            ->whereMonth('created_at', $mes)
            ->where('status',$request->status)
            ->where('user_id',$userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $total_vendas = Venda::whereYear('created_at', $ano)
            ->whereMonth('created_at', $mes)
            ->where('status',$request->status)
          ->where('user_id',$userId)
            ->sum('total');

          $n_vendas =  $vendas ->count();

        $nome_mes = $data->translatedFormat('F');
        $user=User::find($userId); 
       
            $pesquisa = "Mês: $nome_mes ____ Ano: $ano "."_____Usuario : ".$user->name;
       
       

        return view('app.paginas.minhas.vendas.consultas', compact('vendas', 'total_vendas', 'n_vendas', 'pesquisa'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('minhas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
} public function vendas_ano(Request $request)
{
    try {
        $request->validate([
            'ano' => 'required',
            'status'=>'required|in:Valida,Cancelada',
        ]);

        $userId = Auth::user()->id;

        $vendas = Venda::whereYear('created_at', $request->ano)
            ->where('status',$request->status)
            ->where('user_id',$userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $total_vendas = Venda::whereYear('created_at', $request->ano)
        ->where('status',$request->status)
            ->where('user_id',$userId)
            ->sum('total');

            $n_vendas =  $vendas ->count();

           $user=User::find($userId); 
         
            $pesquisa = "Ano: " . $request->ano ."____Usuario : ".$user->name;
          
 
    
        return view('app.paginas.minhas.vendas.consultas', compact('vendas', 'total_vendas', 'n_vendas', 'pesquisa'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('minhas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}
  

    public function vendas_fatura(Request $request){

        try{

        $request->validate([
            'n_fatura'=>'required',
        ]);
        $venda2=Venda::where('n_fatura',$request->n_fatura) ->where('user_id',Auth::user()->id)->first();
        $pesquisa=" Fatura : ".$request->n_fatura;

       $chave="ola";
       $users=User::all();
        return view('app.paginas.minhas.vendas.consultas',compact('venda2','pesquisa','chave'));
    } catch (Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('minhas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
    }
}
