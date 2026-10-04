<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Support\Facades\Log;

class ItemVendaController extends Controller
{
    /**
     * Exibir uma lista de itens de venda.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2($id)
    {
        $venda = Venda::find($id);
        $produtos=Produto::all();
        $vendas=Venda::all();
        $itemVendas = ItemVenda::with(['produto', 'venda'])->paginate(10);
        return view('app.item_vendas.index', compact('itemVendas',"venda","vendas","produtos"));
    }

    /**
     * Mostrar o formulário para criar um novo item de venda.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produtos = Produto::all();
        $vendas = Venda::all();
        return view('app.item_vendas.create', compact('produtos', 'vendas'));
    }

    /**
     * Armazenar um novo item de venda no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'produto_id' => 'required|exists:produtos,id',
                'venda_id' => 'required|exists:vendas,id',
                'quantidade' => 'required|integer|min:1',
                'preco' => 'required|numeric|min:0.01',
            ]);

            $total = $request->quantidade * $request->preco;

            ItemVenda::create([
                'produto_id' => $request->produto_id,
                'venda_id' => $request->venda_id,
                'quantidade' => $request->quantidade,
                'preco' => $request->preco,
                'total' => $total,
            ]);

            return redirect()->route('item_vendas.index')->with('success', 'Item de venda criado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao criar item de venda: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    /**
     * Exibir os detalhes de um item de venda específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $itemVenda = ItemVenda::with(['produto', 'venda'])->findOrFail($id);
        return view('app.item_vendas.show', compact('itemVenda'));
    }

    /**
     * Mostrar o formulário para editar um item de venda existente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $itemVenda = ItemVenda::findOrFail($id);
        $produtos = Produto::all();
        $vendas = Venda::all();
        return view('app.item_vendas.edit', compact('itemVenda', 'produtos', 'vendas'));
    }

    /**
     * Atualizar um item de venda existente no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'produto_id' => 'required|exists:produtos,id',
                'venda_id' => 'required|exists:vendas,id',
                'quantidade' => 'required|integer|min:1',
                'preco' => 'required|numeric|min:0.01',
            ]);

            $itemVenda = ItemVenda::findOrFail($id);
            $total = $request->quantidade * $request->preco;

            $itemVenda->update([
                'produto_id' => $request->produto_id,
                'venda_id' => $request->venda_id,
                'quantidade' => $request->quantidade,
                'preco' => $request->preco,
                'total' => $total,
            ]);

            return redirect()->route('item_vendas.index')->with('success', 'Item de venda atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar item de venda: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    /**
     * Remover um item de venda do banco de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $itemVenda = ItemVenda::findOrFail($id);
            $itemVenda->delete();

            return redirect()->route('item_vendas.index')->with('success', 'Item de venda deletado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao deletar item de venda: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
}
