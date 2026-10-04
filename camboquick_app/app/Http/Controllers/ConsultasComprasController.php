<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class ConsultasComprasController extends Controller
{
    public function index(){
        try{
        $fornecedores = Fornecedor::all();
        return view('app.paginas.consultas.compras.consultas', compact('fornecedores'));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->route('compras.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    public function compras_data(Request $request)
    {
        try {
            $request->validate([
                'data' => 'required|date',
                'n_registros' => 'nullable',
                 'status'=>'required|in:Valida,Cancelada',
            ]);

            $fornecedorId = $request->fornecedor_id;

            $compras = Compra::whereDate('created_at', $request->data)
            ->where('status',$request->status)
                ->when($fornecedorId, fn($query) => $query->where('fornecedor_id', $fornecedorId))
                ->orderBy('created_at', 'desc')
                ->get();

            $total_compras = Compra::whereDate('created_at', $request->data)
            ->where('status',$request->status)
                ->when($fornecedorId, fn($query) => $query->where('fornecedor_id', $fornecedorId))
                ->sum('custo_total');

            $n_compras =$compras->count();

            $fornecedor = Fornecedor::find($fornecedorId); 
            if ($fornecedor == null) {
                $pesquisa = " Data: " . Carbon::parse($request->data)->format('d/m/Y') ;
            } else {
                $pesquisa = " Data: " . Carbon::parse($request->data)->format('d/m/Y') . ",  Fornecedor " . $fornecedor->nome;
            }
/*
            $compras->appends([
                'data' => $request->data,
                //'n_registros' => $request->n_registros,
                'total_compras' => $total_compras,
                'n_compras' => $n_compras,
                'pesquisa' => $pesquisa,
                'fornecedor_id' => $fornecedorId,
            ]);
*/
            $fornecedores = Fornecedor::all();
            return view('app.paginas.consultas.compras.consultas', compact('compras', 'total_compras', 'n_compras', 'pesquisa', 'fornecedores'));
        } catch (\Exception $e) {
            Log::error("Erro: " . $e->getMessage());
            return redirect()->route('consultas.compras')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    public function compras_periodo(Request $request)
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
            $fornecedorId = $request->fornecedor_id;

            $compras = Compra::whereBetween('created_at', [$data_inicio, $data_fim])
            ->where('status',$request->status)
                ->when($fornecedorId, fn($query) => $query->where('fornecedor_id', $fornecedorId))
                ->orderBy('created_at', 'desc')
                ->get();

            $total_compras = Compra::whereBetween('created_at', [$data_inicio, $data_fim])
            ->where('status',$request->status)
                ->when($fornecedorId, fn($query) => $query->where('fornecedor_id', $fornecedorId))
                ->sum('custo_total');

                      $n_compras =$compras->count();

            $fornecedor = Fornecedor::find($fornecedorId); 
            if ($fornecedor == null) {
                $pesquisa = "Período: " .Carbon::parse($request->data_inicio)->format('d/m/Y')  . " até " .Carbon::parse($request->data_fim)->format('d/m/Y') ;
            } else {
                $pesquisa = "Período: " . $request->data_inicio . " até " . $request->data_fim . ",  Fornecedor " . $fornecedor->nome;
            }
/*
            $compras->appends([
                'data_inicio' => $request->data_inicio,
                'data_fim' => $request->data_fim,
               // 'n_registros' => $request->n_registros,
                'total_compras' => $total_compras,
                'n_compras' => $n_compras,
                'pesquisa' => $pesquisa,
                'fornecedor_id' => $fornecedorId,
            ]);*/

            $fornecedores = Fornecedor::all();
            return view('app.paginas.consultas.compras.consultas', compact('compras', 'total_compras', 'n_compras', 'pesquisa', 'fornecedores'));
        } catch (\Exception $e) {
            Log::error("Erro: " . $e->getMessage());
            return redirect()->route('consultas.compras')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    public function compras_mes(Request $request)
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
            $fornecedorId = $request->fornecedor_id;

            $compras = Compra::whereYear('created_at', $ano)
                ->whereMonth('created_at', $mes)
                ->where('status',$request->status)
                ->when($fornecedorId, fn($query) => $query->where('fornecedor_id', $fornecedorId))
                ->orderBy('created_at', 'desc')
                ->get();

            $total_compras = Compra::whereYear('created_at', $ano)
                ->whereMonth('created_at', $mes)
                ->where('status',$request->status)
                ->when($fornecedorId, fn($query) => $query->where('fornecedor_id', $fornecedorId))
                ->sum('custo_total');

                       $n_compras =$compras->count();

            $nome_mes = $data->translatedFormat('F');
            $fornecedor = Fornecedor::find($fornecedorId); 
            if ($fornecedor == null) {
                $pesquisa = "Mês: $nome_mes  Ano: $ano  ";
            } else {
                $pesquisa = "Mês: $nome_mes,  Ano: $ano,    Fornecedor " . $fornecedor->nome;
            }
/*
            $compras->appends([
                'mes' => $request->mes,
              //  'n_registros' => $request->n_registros,
                'total_compras' => $total_compras,
                'n_compras' => $n_compras,
                'pesquisa' => $pesquisa,
                'fornecedor_id' => $fornecedorId,
            ]);*/

            $fornecedores = Fornecedor::all();
            return view('app.paginas.consultas.compras.consultas', compact('compras', 'total_compras', 'n_compras', 'pesquisa', 'fornecedores'));
        } catch (\Exception $e) {
            Log::error("Erro: " . $e->getMessage());
            return redirect()->route('consultas.compras')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    public function compras_ano(Request $request)
    {
        try {
            $request->validate([
                'ano' => 'required',
                'n_registros' => 'nullable',
                 'status'=>'required|in:Valida,Cancelada',
            ]);

            $fornecedorId = $request->fornecedor_id;

            $compras = Compra::whereYear('created_at', $request->ano)
            ->where('status',$request->status)
                ->when($fornecedorId, fn($query) => $query->where('fornecedor_id', $fornecedorId))
                ->orderBy('created_at', 'desc')
                ->get();

            $total_compras = Compra::whereYear('created_at', $request->ano)
            ->where('status',$request->status)
                ->when($fornecedorId, fn($query) => $query->where('fornecedor_id', $fornecedorId))
                ->sum('custo_total');

                       $n_compras =$compras->count();

            $fornecedor = Fornecedor::find($fornecedorId); 
            if ($fornecedor == null) {
                $pesquisa = "Ano: " . $request->ano ;
            } else {
                $pesquisa = "Ano: " . $request->ano . ",  Fornecedor : " . $fornecedor->nome;
            }
/*
            $compras->appends([
                'ano' => $request->ano,
             //   'n_registros' => $request->n_registros,
                'total_compras' => $total_compras,
                'n_compras' => $n_compras,
                'pesquisa' => $pesquisa,
                'fornecedor_id' => $fornecedorId,
            ]);*/

            $fornecedores = Fornecedor::all();
            return view('app.paginas.consultas.compras.consultas', compact('compras', 'total_compras', 'n_compras', 'pesquisa', 'fornecedores'));
        } catch (\Exception $e) {
            Log::error("Erro: " . $e->getMessage());
            return redirect()->route('consultas.compras')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    public function compras_fatura(Request $request){

        try{

        $request->validate([
            'n_fatura'=>'required',
        ]);
        $compra2=Compra::where('n_fatura',$request->n_fatura)->first();
        $pesquisa=" Fatura : ".$request->n_fatura;

       $chave="ola";
       $fornecedores=Fornecedor::all();
        return view('app.paginas.consultas.compras.consultas',compact('compra2','pesquisa','chave','fornecedores'));
    } catch (\Exception $e) {
        Log::error("Erro : " . $e->getMessage());
        return redirect()->route('consultas.compras')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
    }
}
