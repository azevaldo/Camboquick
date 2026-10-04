<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategoria;
use App\Models\Categoria;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubCategoriaController extends Controller
{
    public function __construct(){
        $this->middleware('roles:admin,gerente')->only(['store','update','arquivar']);
    }
    public function getSubCategorias($id){
         $cat=Categoria::find($id);
         $subcategorias=$cat->subCategorias()->where('status','Valido')->get();
         return response()->json($subcategorias);
        }
    public function index2($slug)
    {
        try {
        $categoria=Categoria::where('slug',$slug)->first();
        $subs = $categoria->subCategorias()->get();
        return view("app.paginas.subcategorias.index", compact("subs","categoria"));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    public function index()
    {
         
    }

    /**
     * Mostrar o formulário para criar uma nova escala.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("app.subcategorias.create");
    }

    /**
     * Armazenar uma nova escala no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function gerarSlug($para){
         // Gerar um slug único
    $slugBase = Str::slug($para);
    $slug = $slugBase;
    $contador = 1;
    while (SubCategoria::where('slug', $slug)->exists()) {
        $slug = $slugBase . '-' . $contador;
        $contador++;
    }
    return $slug;
    }

    public function atualizarSlug($para,$sub){
        // Gerar um slug único
        if ($para !== $sub->sub) {
            $slugBase = Str::slug($para);
            $slug = $slugBase;
            $contador = 1;
            while (SubCategoria::where('slug', $slug)->where('id', '!=', $sub->id)->exists()) {
                $slug = $slugBase . '-' . $contador;
                $contador++;
            }
            $sub->slug = $slug;
      }
   
   }
    public function store(Request $request)
    {
        try {
            $request->validate([
                "sub" => "required|unique:subcategorias,sub|max:255",
                'categoria_id'=>'required|exists:categorias,id',
            ]);
           $slug =$this->gerarSlug($request->sub);
            SubCategoria::create([
                "sub" => $request->sub,
                'slug'=>$slug,
                'categoria_id'=> $request->categoria_id,
            ]);

            return redirect()->back()->with("success", "SubCategoria criada com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao criar subcategoria: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Exibir os detalhes de uma escala específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Mostrar o formulário para editar uma escala existente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Atualizar uma escala existente no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                "sub" => "required|unique:subcategorias,sub," . $id . "|max:255",
                 'status'=>'required|in:Valido,Arquivado',
            ]);

            $sub = SubCategoria::findOrFail($id);
            $this->atualizarSlug($request->sub,$sub);
            $sub->update([
                "sub" => $request->sub,
                "status" => $request->status,
            ]);

            return redirect()->back()->with("success", "SubCategoria atualizada com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao atualizar Subcategoria: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Remover uma escala do banco de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function arquivar($id)
    {
        try {
            $subcategoria = Subcategoria::findOrFail($id);
            $subcategoria->status="Arquivado";
            $subcategoria->save();
            return redirect()->back()->with("success", "Subcategoria Arquivada com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao arquivar Subcategoria: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        
    }
}
