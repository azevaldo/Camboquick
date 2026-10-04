<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FornecedorController extends Controller
{
    public function index()
    {
        try {
        $fornecedores = Fornecedor::get();
        return view("app.paginas.fornecedores.index", compact("fornecedores"));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    public function create()
    {
        return view("fornecedores.create");
    }

    public function gerarSlug($para)
    {
        $slugBase = Str::slug($para);
        $slug = $slugBase;
        $contador = 1;

        while (Fornecedor::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    public function atualizarSlug($para, $fornecedor)
    {
        if ($para !== $fornecedor->nome) {
            $slugBase = Str::slug($para);
            $slug = $slugBase;
            $contador = 1;

            while (Fornecedor::where('slug', $slug)->where('id', '!=', $fornecedor->id)->exists()) {
                $slug = $slugBase . '-' . $contador;
                $contador++;
            }

            $fornecedor->slug = $slug;
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|min:3|max:255',
                'nif' => 'required|unique:fornecedores,nif|min:14|max:14',
                'contato' => 'required|max:255|min:4',
                'localizacao' => 'nullable|max:255|min:3',
            ]);

            $slug = $this->gerarSlug($request->nome);

            Fornecedor::create([
                'nome' => $request->nome,
                'nif' => $request->nif,
                'localizacao' => $request->localizacao,
                'contato' => $request->contato,
                'slug' => $slug,
            ]);

            return redirect()->route('fornecedores.index')->with('success', 'Fornecedor criado com sucesso!');
        } catch (Exception $e) {
            Log::error("Erro ao criar fornecedor: " . $e->getMessage());
            return redirect()->route('fornecedores.index')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        return view("fornecedores.show", compact("fornecedor"));
    }

    public function edit($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        return view("fornecedores.edit", compact("fornecedor"));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nome' => 'required|max:255',
                'nif' => 'required|unique:fornecedores,nif,' . $id,
                'contato' => 'required|max:255',
                'localizacao' => 'nullable|max:255',
                'status'=>'required|in:Valido,Arquivado',
            ]);

            $fornecedor = Fornecedor::findOrFail($id);
          $this->atualizarSlug($request->nome, $fornecedor);

            $fornecedor->update([
                'nome' => $request->nome,
                'nif' => $request->nif,
                'localizacao' => $request->localizacao,
                'contato' => $request->contato,
              
                'status' => $request->status,
            ]);

            return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar fornecedor: " . $e->getMessage());
            return redirect()->route('fornecedores.index')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    public function arquivar($id)
    {
        try {
            $fornecedor = Fornecedor::findOrFail($id);
            $fornecedor->status="Arquivado";
            $fornecedor->save();
            return redirect()->route("fornecedores.index")->with("success", "Fornecedor Arquivado com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao arquivar fornecedor: " . $e->getMessage());
            return redirect()->route('fornecedores.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        /*  try {
            $fornecedor = Fornecedor::findOrFail($id);
            $fornecedor->delete();

            return redirect()->route('fornecedores.index')->with('success', 'Fornecedor deletado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao deletar fornecedor: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }*/
    }
}
