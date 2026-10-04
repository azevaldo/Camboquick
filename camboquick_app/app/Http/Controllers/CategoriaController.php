<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    /**
     * Exibir uma lista das categorias.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
        $categorias = Categoria::all();
        return view("app.paginas.categorias.index", compact("categorias"));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Mostrar o formulário para criar uma nova categoria.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("categorias.create");
    }

    /**
     * Armazenar uma nova categoria no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function gerarSlug($para){
        // Gerar um slug único
   $slugBase = Str::slug($para);
   $slug = $slugBase;
   $contador = 1;
   while (Categoria::where('slug', $slug)->exists()) {
       $slug = $slugBase . '-' . $contador;
       $contador++;
   }
   return $slug;
   }

   public function atualizarSlug($para,$categoria){
       // Gerar um slug único
       if ($para !== $categoria->categoria) {
           $slugBase = Str::slug($para);
           $slug = $slugBase;
           $contador = 1;
           while (Categoria::where('slug', $slug)->where('id', '!=', $categoria->id)->exists()) {
               $slug = $slugBase . '-' . $contador;
               $contador++;
           }
           $categoria->slug = $slug;
     }
  
  }
    public function store(Request $request)
    {
        try {
            $request->validate([
                "categoria" => "required|unique:categorias,categoria|min:3|max:255",
            ]);
            $slug=$this->gerarSlug($request->categoria);
            Categoria::create([
                "categoria" => $request->categoria,
                'slug'=>$slug,
            ]);

            return redirect()->route("categorias.index")->with("success", "Categoria criada com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao criar categoria: " . $e->getMessage());
            return redirect()->route("categorias.index")->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Exibir os detalhes de uma categoria específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view("categorias.show", compact("categoria"));
    }

    /**
     * Mostrar o formulário para editar uma categoria existente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view("categorias.edit", compact("categoria"));
    }

    /**
     * Atualizar uma categoria existente no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                "categoria" => "required|unique:categorias,categoria," . $id . "|min:3|max:255",
                  'status'=>'required|in:Valido,Arquivado',
            ]);

            $categoria = Categoria::findOrFail($id);
           $this->atualizarSlug($request->categoria,$categoria);
            $categoria->update([
                "categoria" => $request->categoria,
                "status"=> $request->status,
            ]);

            return redirect()->route("categorias.index")->with("success", "Categoria atualizada com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar categoria: " . $e->getMessage());
            return redirect()->route("categorias.index")->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Remover uma categoria do banco de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

         public function arquivar($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->status="Arquivado";
            $categoria->save();
            return redirect()->route("categorias.index")->with("success", "Categoria Arquivada com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao arquivar Categoria: " . $e->getMessage());
            return redirect()->route("categorias.index")->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function destroy($id)
    {
          /*
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();

            return redirect()->route("categorias.index")->with("success", "Categoria deletada com sucesso!");
        } catch (\Exception $e) {
            Log::error("Erro ao deletar categoria: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }*/
    }
}
