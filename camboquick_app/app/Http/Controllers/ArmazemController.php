<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Armazem;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArmazemController extends Controller
{
    public function index()
    {
        try{
        $armazens = Armazem::get();
        return view("app.paginas.armazens.index", compact("armazens"));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    public function create()
    {
        return view("app.armazens.create");
    }

    private function gerarSlug($descricao)
    {
        $slugBase = Str::slug($descricao);
        $slug = $slugBase;
        $contador = 1;

        while (Armazem::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    private function atualizarSlug($novaDescricao, $armazem)
    {
        if ($novaDescricao !== $armazem->descricao) {
            $slugBase = Str::slug($novaDescricao);
            $slug = $slugBase;
            $contador = 1;

            while (Armazem::where('slug', $slug)->where('id', '!=', $armazem->id)->exists()) {
                $slug = $slugBase . '-' . $contador;
                $contador++;
            }

            $armazem->slug = $slug;
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                "descricao" => "required|min:3|max:255",
                "localizacao" => "required|min:3|max:255",
         
            ]);

            $slug = $this->gerarSlug($request->descricao);

            Armazem::create([
                "descricao" => $request->descricao,
                "localizacao" => $request->localizacao,
                'codigo' => strtoupper(Str::random(10)),
                "slug" => $slug,
            ]);

            return redirect()->route("armazens.index")->with("success", "Armazém cadastrado com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao cadastrar armazém: " . $e->getMessage());
            return redirect()->route("armazens.index")->with("error", "Erro ao cadastrar: " . $e->getMessage());
        }
    }

     

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                "descricao" => "required|min:3|max:255",
                "localizacao" => "required|min:3|max:255",
     'status'=>'required|in:Valido,Arquivado',
            ]);

            $armazem = Armazem::findOrFail($id);
            $this->atualizarSlug($request->descricao, $armazem);

            $armazem->update([
                "descricao" => $request->descricao,
                "localizacao" => $request->localizacao,
                "status"=> $request->status,
     
            ]);

            return redirect()->route("armazens.index")->with("success", "Armazém atualizado com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar armazém: " . $e->getMessage());
            return redirect()->route("armazens.index")->with("error", "Erro ao atualizar: " . $e->getMessage());
        }
    }
    public function arquivar($id)
    {
        try {
            $armazem = Armazem::findOrFail($id);
            $armazem->status="Arquivado";
            $armazem->save();
            return redirect()->route("armazens.index")->with("success", "Armazem Arquivado com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao arquivar armazem: " . $e->getMessage());
            return redirect()->route("armazens.index")->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function destroy($id)
    {
          /*
        try {
            $armazem = Armazem::findOrFail($id);
            $armazem->delete();
            return redirect()->route("armazens.index")->with("success", "Armazém deletado com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao deletar armazém: " . $e->getMessage());
            return redirect()->back()->with("error", "Erro ao deletar: " . $e->getMessage());
        }*/
    }
}
