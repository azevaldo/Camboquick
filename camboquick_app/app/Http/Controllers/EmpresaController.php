<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Telefone;
use Exception;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    // Listar todas as empresas
    public function index()
    {
        try{
        $empresa = Empresa::first();
        return view('app.paginas.empresa.index', compact('empresa'));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    public function telefones()
    {
        try{
        $empresa = Empresa::first();
        $telefones=$empresa->telefones()->get();

        return view('app.paginas.empresa.telefones.index', compact('telefones'));
         } catch (Exception $e) {
            Log::error("Ocorreu um erro: " . $e->getMessage());
            return redirect()->route('empresas.index')->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    public function storeTelefone(Request $request)
    {
        try {
            $request->validate([
                'numero' => 'required|min:4|max:255',
            ]);
            $empresa = Empresa::first();
            Telefone::create([
                'numero'=>$request->numero,
                'empresa_id'=>$empresa->id,
            ]);
            return redirect()->route('empresa.telefones')->with('success', 'Telefone Adiconado Com Sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao cadastrar telefone: ' . $e->getMessage());
           
           
            return redirect()->route('empresa.telefones')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }
}

    public function updateTelefone(Request $request,$id)
    {
        try {
            $request->validate([
                'numero' => 'required|min:4|max:255',
            ]);
            $telefone = Telefone::findOrFail($id);
            $telefone->update($request->all());

            return redirect()->route('empresa.telefones')->with('success', 'Telefone Atualizado Com Sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao cadastrar telefone: ' . $e->getMessage());
           
           
            return redirect()->route('empresa.telefones')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
    }  }

    public function destroyTelefone($id)
    {
        try {
            $telefone = Telefone::findOrFail($id);
            $telefone->delete();
            return redirect()->route('empresa.telefones')->with("success", "Telefone deletado com sucesso!");
        } catch (\Exception $e){
            Log::error("Erro ao deletar Telefone: " . $e->getMessage());
            return redirect()->route('empresa.telefones')->with("error", "Erro ao deletar: " . $e->getMessage());
        }
    }
    // Mostrar formulário de criação
    

    // Armazenar nova empresa
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|max:255',
                'email' => 'required|email|unique:empresa,email',
                'nif' => 'required|unique:empresa,nif|min:14|max:14',
                'local' => 'required|max:255',
            ]);

            Empresa::create($request->all());

            return redirect()->route('empresas.index')->with('success', 'Empresa cadastrada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao cadastrar empresa: ' . $e->getMessage());
            return redirect()->route('empresas.index')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    // Exibir detalhes de uma empresa
   

    // Mostrar formulário de edição
    

    // Atualizar dados da empresa
    public function update(Request $request, $id)
    {
        try {
            $empresa = Empresa::findOrFail($id);

            $request->validate([
                'nome' => 'required|max:255',
                'email' => 'required|email|unique:empresa,email,' . $id,
                'nif' => 'required|unique:empresa,nif,' . $id."|min:14|max:14",
                'local' => 'required|max:255|min:3',
            ]);

            $empresa->update($request->all());

            return redirect()->route('empresas.index')->with('success', 'Empresa atualizada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar empresa: ' . $e->getMessage());
            return redirect()->route('empresas.index')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

    // Remover empresa

}

