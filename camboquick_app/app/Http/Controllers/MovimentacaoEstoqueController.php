<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\MovimentacaoEstoque;
use App\Models\Produto;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class MovimentacaoEstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
 
        $movimentacoes=MovimentacaoEstoque::with(['produto','usuario'])->orderBy('created_at','DESC')->get();
        $categorias=Categoria::all();
        return view('app.paginas.movimentacoes.index',compact('movimentacoes','categorias'));
    }

    public function movimentos_periodo(Request $request)
    {
        try {
            $request->validate([
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date|after_or_equal:data_inicio',
                'produto_id'=>'required|exists:produtos,id',
                'tipo'=>'required',
                'n_registros' => 'nullable',
            ]);

            $data_inicio = Carbon::parse($request->data_inicio)->startOfDay();
            $data_fim = Carbon::parse($request->data_fim)->endOfDay();
            $fornecedorId = $request->fornecedor_id;

            $movimentacoes =MovimentacaoEstoque::whereBetween('created_at', [$data_inicio, $data_fim])
                ->where('produto_id',$request->produto_id)
                ->where('tipo',$request->tipo)
                ->orderBy('created_at', 'desc')
                ->get();

            $total_mov =MovimentacaoEstoque::whereBetween('created_at', [$data_inicio, $data_fim])
            ->where('produto_id',$request->produto_id)
            ->where('tipo',$request->tipo)  ->sum('preco_compra');

            $n_mov =$movimentacoes ->count();

           $produto=Produto::find($request->produto_id);
                $pesquisa = "Período: " . Carbon::parse($request->data_inicio)->format('d/m/Y')   . " até " . Carbon::parse($request->data_fim)->format('d/m/Y') ."_______ Produto : " .$produto->nome."____ Tipo :  ".$request->tipo;
            

           /* $movimentacoes->appends([
                'data_inicio' => $request->data_inicio,
                'data_fim' => $request->data_fim,
                'n_registros' => $request->n_registros,
                'total_mov' => $total_mov,
                'n_mov' => $n_mov,
                'produto_id'=>$request->produto_id,
                'pesquisa' => $pesquisa,
                'tipo' => $request->tipo,
            ]);
 */
            $categorias=Categoria::all();
            return view('app.paginas.movimentacoes.index', compact('movimentacoes', 'total_mov', 'n_mov', 'pesquisa', 'categorias'));
        } catch (Exception $e) {
            Log::error("Erro: " . $e->getMessage());
            return redirect()->route('movimentacoes.index')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MovimentacaoEstoque  $movimentacaoEstoque
     * @return \Illuminate\Http\Response
     */
    public function show(MovimentacaoEstoque $movimentacaoEstoque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MovimentacaoEstoque  $movimentacaoEstoque
     * @return \Illuminate\Http\Response
     */
    public function edit(MovimentacaoEstoque $movimentacaoEstoque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MovimentacaoEstoque  $movimentacaoEstoque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MovimentacaoEstoque $movimentacaoEstoque)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MovimentacaoEstoque  $movimentacaoEstoque
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          /*
        try {
            $movimentacao = MovimentacaoEstoque::findOrFail($id);
            $movimentacao->delete();

            return redirect()->back()->with("success", "Movimentacao deletado com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao deletar Venda: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }*/
    }
}
