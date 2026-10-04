<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClienteController extends Controller
{
    public function index()
    {
        try{
        $clientes = Cliente::paginate(5);
        return view("app.clientes.index", compact("clientes"));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    public function create()
    {
        return view("clientes.create");
    }

    public function gerarSlug($para)
    {
        $slugBase = Str::slug($para);
        $slug = $slugBase;
        $contador = 1;

        while (Cliente::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    public function atualizarSlug($para, $cliente)
    {
        if ($para !== $cliente->nome) {
            $slugBase = Str::slug($para);
            $slug = $slugBase;
            $contador = 1;

            while (Cliente::where('slug', $slug)->where('id', '!=', $cliente->id)->exists()) {
                $slug = $slugBase . '-' . $contador;
                $contador++;
            }

            $cliente->slug = $slug;
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|max:255',
                'nif' => 'required|unique:clientes,nif',
                'localizacao' => 'nullable|max:255',
            ]);

            $slug = $this->gerarSlug($request->nome);

            Cliente::create([
                'nome' => $request->nome,
                'nif' => $request->nif,
                'localizacao' => $request->localizacao,
                'slug' => $slug,
            ]);

            return redirect()->route('clientes.index')->with('success', 'Cliente criado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao criar cliente: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view("clientes.show", compact("cliente"));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view("clientes.edit", compact("cliente"));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nome' => 'required|max:255',
                'nif' => 'required|unique:clientes,nif,' . $id,
                'localizacao' => 'nullable|max:255',
            ]);

            $cliente = Cliente::findOrFail($id);
            $this->atualizarSlug($request->nome, $cliente);

            $cliente->update([
                'nome' => $request->nome,
                'nif' => $request->nif,
                'localizacao' => $request->localizacao,
                'slug' => $cliente->slug,
            ]);

            return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar cliente: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
          /*
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            return redirect()->route('clientes.index')->with('success', 'Cliente deletado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao deletar cliente: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }*/
    }
}
