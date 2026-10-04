<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class ConsultasVendasController extends Controller
{
    //
    public function index(){
        try{
        $users=User::all();
        return view('app.paginas.consultas.vendas.consultas',compact('users'));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->route('vendas.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }


    public function vendas_data(Request $request)
{
    try {
        $request->validate([
            'data' => 'required|date',
            'n_registros' => 'nullable',
             'status'=>'required|in:Valida,Cancelada',
        ]);

        $userId = $request->user_id;

        $vendas = Venda::whereDate('created_at', $request->data)
        ->where('status',$request->status)
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->orderBy('created_at', 'desc')
            ->get();

        $total_vendas = Venda::whereDate('created_at', $request->data)
        ->where('status',$request->status)
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->sum('total');

        $n_vendas =  $vendas ->count();

            $user=User::find($userId); 
            if($user==null){
                $pesquisa = "Data : " . Carbon::parse($request->data)->format('d/m/Y') ;
            }else{
               
                $pesquisa = "Data : " . Carbon::parse($request->data)->format('d/m/Y') ."_____Usuario : ".$user->name;
            }
    
        /*
        $vendas->appends([
            'data' => $request->data,
          'n_registros' => $request->n_registros,
            'total_vendas' => $total_vendas,
            'n_vendas' => $n_vendas,
            'pesquisa' => $pesquisa,
            'user_id' => $userId,
        ]);*/

        $users = User::all();
        return view('app.paginas.consultas.vendas.consultas', compact('vendas', 'total_vendas', 'n_vendas', 'pesquisa', 'users'));
    } catch (\Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('consultas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}   public function vendas_periodo(Request $request)
{
    try {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'n_registros' => 'nullable',
             'status'=>'required|in:Valida,Cancelada',
        ]);

        $data_inicio = Carbon::parse($request->data_inicio)->startOfDay();
        $data_fim = Carbon::parse($request->data_fim)->endOfDay();
        $userId = $request->user_id;

        $vendas = Venda::whereBetween('created_at', [$data_inicio, $data_fim])
        ->where('status',$request->status)
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->orderBy('created_at', 'desc')
            ->get();

        $total_vendas = Venda::whereBetween('created_at', [$data_inicio, $data_fim])
        ->where('status',$request->status)
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->sum('total');

        $n_vendas =  $vendas ->count();


            $user=User::find($userId); 
            if($user==null){
                $pesquisa = "Período: " . Carbon::parse($request->data_inicio)->format('d/m/Y'). " até " . Carbon::parse($request->data_fim)->format('d/m/Y') ;

            }else{
                $pesquisa = "Período: " .  Carbon::parse($request->data_inicio)->format('d/m/Y'). " até " . Carbon::parse($request->data_fim)->format('d/m/Y') ."______Usuario : ".$user->name;
            }
       
      /*  $vendas->appends([
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
          'n_registros' => $request->n_registros,
            'total_vendas' => $total_vendas,
            'n_vendas' => $n_vendas,
            'pesquisa' => $pesquisa,
            'user_id' => $userId,
        ]);
*/
        $users = User::all();
        return view('app.paginas.consultas.vendas.consultas', compact('vendas', 'total_vendas', 'n_vendas', 'pesquisa', 'users'));
    } catch (\Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('consultas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
} public function vendas_mes(Request $request)
{
    try {
        $request->validate([
            'mes' => 'required|date_format:Y-m',
            'n_registros' => 'nullable',
             'status'=>'required|in:Valida,Cancelada',
        ]);

        $data = Carbon::createFromFormat('Y-m', $request->mes);
        $ano = $data->year;
        $mes = $data->month;
        $userId = $request->user_id;

        $vendas = Venda::whereYear('created_at', $ano)
            ->whereMonth('created_at', $mes)
            ->where('status',$request->status)
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->orderBy('created_at', 'desc')
            ->get();

        $total_vendas = Venda::whereYear('created_at', $ano)
            ->whereMonth('created_at', $mes)
            ->where('status',$request->status)
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->sum('total');

      $n_vendas =  $vendas ->count();

        $nome_mes = $data->translatedFormat('F');
        $user=User::find($userId); 
        if($user==null){
            $pesquisa = "Mês: $nome_mes ____ Ano: $ano " ;
        }else{
            $pesquisa = "Mês: $nome_mes ____ Ano: $ano "."_____Usuario : ".$user->name;
        }
       
/*
        $vendas->appends([
            'mes' => $request->mes,
          //  'n_registros' => $request->n_registros,
            'total_vendas' => $total_vendas,
            'n_vendas' => $n_vendas,
            'pesquisa' => $pesquisa,
            'user_id' => $userId,
        ]);
*/
        $users = User::all();
        return view('app.paginas.consultas.vendas.consultas', compact('vendas', 'total_vendas', 'n_vendas', 'pesquisa', 'users'));
    } catch (\Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('consultas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
} public function vendas_ano(Request $request)
{
    try {
        $request->validate([
            'ano' => 'required',
            'status'=>'required|in:Valida,Cancelada',
        ]);

        $userId = $request->user_id;

        $vendas = Venda::whereYear('created_at', $request->ano)
            ->where('status',$request->status)
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->orderBy('created_at', 'desc')
            ->get();

        $total_vendas = Venda::whereYear('created_at', $request->ano)
        ->where('status',$request->status)
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->sum('total');

       $n_vendas =  $vendas ->count();

           $user=User::find($userId); 
           if($user==null){
            $pesquisa = "Ano: " . $request->ano ;
           }else{
            $pesquisa = "Ano: " . $request->ano ."____Usuario : ".$user->name;
           }
        
/*
        $vendas->appends([
            'ano' => $request->ano,
          //  'n_registros' => $request->n_registros,
            'total_vendas' => $total_vendas,
            'n_vendas' => $n_vendas,
            'pesquisa' => $pesquisa,
            'user_id' => $userId,
        ]);
*/
        $users = User::all();
        return view('app.paginas.consultas.vendas.consultas', compact('vendas', 'total_vendas', 'n_vendas', 'pesquisa', 'users'));
    } catch (\Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('consultas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}
  

    public function vendas_fatura(Request $request){

        try{

        $request->validate([
            'n_fatura'=>'required',
        ]);
        $venda2=Venda::where('n_fatura',$request->n_fatura)->first();
        $pesquisa=" Fatura : ".$request->n_fatura;

       $chave="ola";
       $users=User::all();
        return view('app.paginas.consultas.vendas.consultas',compact('venda2','pesquisa','chave','users'));
    } catch (\Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('consultas.vendas')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
    }

}
