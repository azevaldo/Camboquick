<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Turno;
use App\Models\TurnoEstoque;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TurnoController extends Controller
{
    //

    public function geral_turno_periodo(Request $request){
      try{
                $request->validate(['data_inicio'=>'required','data_fim'=>'required|after_or_equal:data_inicio']);
                $data_inicio=Carbon::parse($request->data_inicio)->startOfDay();
                $data_fim=Carbon::parse($request->data_fim)->endOfDay();
                    $userId = $request->user_id;
                $turnos=Turno::whereBetween('inicio',[$data_inicio,$data_fim])
                ->when($userId, fn($query) =>$query ->where('usuario_id',$userId))
                ->orderBy('created_at','DESC')->get();
        
                $user=User::find($userId);
            if($user==null){
                   $resultados= "Período: " . Carbon::parse($request->data_inicio)->format('d/m/Y'). " até " . Carbon::parse($request->data_fim)->format('d/m/Y') ;

            }else{
                 $resultados = "Período: " .  Carbon::parse($request->data_inicio)->format('d/m/Y'). " até " . Carbon::parse($request->data_fim)->format('d/m/Y') ."______Usuario : ".$user->name;
            }
              $users=User::all();
                return view('app.paginas.turnos.geral',compact('turnos','resultados','users'));
    }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->route('turnos.geral')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function meu_turno_periodo(Request $request){
       
            try{
                $request->validate(['data_inicio'=>'required','data_fim'=>'required|after_or_equal:data_inicio']);
                $data_inicio=Carbon::parse($request->data_inicio)->startOfDay();
                $data_fim=Carbon::parse($request->data_fim)->endOfDay();
                
                $turnos=auth()->user()->turnos()->whereBetween('inicio',[$data_inicio,$data_fim])->orderBy('created_at','DESC')->get();
                $resultados='Data_Inicio : '.$data_inicio->format('d/m/Y').'__ Data_fim : '.$data_fim->format('d/m/Y');
        
                return view('app.paginas.minhas.turnos.index',compact('turnos','resultados'));
    }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->route('turnos.meus')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function turnoGeral(){
       
            try{
          $turnos=Turno::orderBy('created_at','DESC')->get();
      $users=User::all();
         return view('app.paginas.turnos.geral',compact('turnos','users'));
    }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function index(){
    try{
          $turnos=auth()->user()->turnos()->orderBy('created_at','DESC')->get();
      
         return view('app.paginas.minhas.turnos.index',compact('turnos'));
    }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
      
    }

  public function documento2($turno){
    try{

        $turno=Turno::findOrFail($turno);
          
      
         return view('app.paginas.minhas.turnos.documento',compact('turno'));
    }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
      
    }
        public function documento($turno){
    try{

          $turno=auth()->user()->turnos()->findOrFail($turno);
      
         return view('app.paginas.minhas.turnos.documento',compact('turno'));
    }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
      
    }

public function fecharTurno()
{
    try {
        DB::beginTransaction();

        $turno = Auth::user()->turnos()->where('estado','aberto')->first();

        if (!$turno){

            session()->put('error', 'Não Existe Turno aberto!');

    return redirect()->route('produtos.index');
        }
            

        foreach ($turno->turnoEstoques as $item) {
            $qntrestante = $item->produto->quantidade_retalho - $item->produto->quantidade * $item->produto->quant_uni_grosso;

            $item->update([
                'qnt2_grosso' => $item->produto->quantidade,
                'qnt2_restante' => $qntrestante,
                'qnt2_retalho' => $item->produto->quantidade_retalho,
            ]);
        }

        Log::info("ANTES DE SALVAR", ['inicio' => $turno->inicio, 'fim' => Carbon::now()]);

        $turno->update([
            'fim' => Carbon::now(),
            'estado' => 'fechado',
        ]);

        DB::commit();

            session()->put('success', 'Turno encerrado com sucesso!');

    return redirect()->route('produtos.index');

     
    } catch (Exception $e) {
        DB::rollback();
        Log::error("Erro : " . $e->getMessage());
        return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
    }
}


    public function  abrirTurno()
{
    try{
        DB::beginTransaction();
        if(Auth::user()->turnos()->where('estado','aberto')->first()){

            session()->put('error', 'Não pode abrir Um Turno Tendo Outro Aberto!');

    return redirect()->route('produtos.index');
        }
   
    
        $turno = Turno::create([
        'usuario_id' => auth()->id(),
        'inicio' =>  Carbon::now(),
    ]);

    // Registra a quantidade inicial dos produtos
    foreach (Produto::where('status','Valido')->get() as $produto) {
      $qntrestante= $produto->quantidade_retalho- $produto->quantidade*$produto->quant_uni_grosso;
        TurnoEstoque::create([
            'turno_id' => $turno->id,
            'produto_id' => $produto->id,
            'qnt1_grosso' => $produto->quantidade,
            'qnt1_restante' =>$qntrestante,
            'qnt1_retalho' => $produto->quantidade_retalho,
        ]);
    }
DB::commit();
 
    session()->put('success', 'Turno iniciado e inventário salvo!');

    return redirect()->route('produtos.index');

        }     catch (Exception $e) {
         DB::rollback();
        Log::error("Erro : " . $e->getMessage());
       // return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
         
        session()->put('error', 'Ocorreu um erro: ' . $e->getMessage());

    return redirect()->route('produtos.index');
    //  session()->put('error', 'Ocorreu um erro: ' . $e->getMessage());

    //return redirect()->route('produtos.index');
       //return redirect()->route('produtos.index')->with("error", "Ocorreu um erro: " . $e->getMessage());

         // use para codificar a mensagem
//$errorMsg = base64_encode('Ocorreu um erro : '.$e->getMessage());

// usa o name da rota dos produtos (ajusta se o name for outro)
//return redirect()->route('produtos.index', ['flash_error' => $errorMsg]);
        }
}

}
