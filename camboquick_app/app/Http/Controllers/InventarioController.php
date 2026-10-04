<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\InventarioEstoque;
use App\Models\Produto;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventarioController extends Controller
{
    //

          public function documento($id){
    try{

 
        $inventario=Inventario::findOrfail($id);
         return view('app.paginas.consultas.inventarios.documento',compact('inventario'));
    }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->route('inventarios.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
      
    }
public function index (){
try{
    $inventarios=Inventario::orderBy('created_at','DESC')->get();

return view('app.paginas.consultas.inventarios.consultas',compact('inventarios'));
   }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
}


    public function periodo(Request $request){
      try{
                $request->validate(['data_inicio'=>'required','data_fim'=>'required|after_or_equal:data_inicio']);
                $data_inicio=Carbon::parse($request->data_inicio)->startOfDay();
                $data_fim=Carbon::parse($request->data_fim)->endOfDay();
                    
                $inventarios=Inventario::whereBetween('created_at',[$data_inicio,$data_fim])
       
                ->orderBy('created_at','DESC')->get();
        
                    $resultados= "Período: " . Carbon::parse($request->data_inicio)->format('d/m/Y'). " até " . Carbon::parse($request->data_fim)->format('d/m/Y') ;

            
             
                return view('app.paginas.consultas.inventarios.consultas',compact('inventarios','resultados'));
    }     catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->route('inventarios.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function store()
    {
        DB::beginTransaction();

        try {
            // Criar o inventário
            $inventario = Inventario::create([
                'user_id' => Auth::id(),
            ]);



        
         
            // Suponha que você recebe um array de produtos e quantidades
            foreach (Produto::where('status','Valido')->get() as $produto) {
                   $qntrestante= $produto->quantidade_retalho- $produto->quantidade*$produto->quant_uni_grosso;
                InventarioEstoque::create([
                    'inventario_id' => $inventario->id,
                   'produto_id' => $produto->id,
            'qnt1_grosso' => $produto->quantidade,
            'qnt1_restante' =>$qntrestante,
            'qnt1_retalho' => $produto->quantidade_retalho,
                ]);
            }

            DB::commit();

$inventarios=Inventario::orderBy('created_at','DESC')->get();
//inventarios.index
 return redirect()->route('inventarios.index')->with('success', 'Inventario guardado com sucesso');
return view('app.paginas.consultas.inventarios.consultas',compact('inventarios'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('inventarios.index')->with('error', 'Erro ao registrar inventário: ' . $e->getMessage());
        }
    }
}
