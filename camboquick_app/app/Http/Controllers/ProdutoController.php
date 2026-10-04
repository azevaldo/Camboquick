<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;
use App\Models\SubCategoria;
use App\Models\Armazem;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; 

class ProdutoController extends Controller
{
    public function __construct(){
        $this->middleware('roles:admin,gerente')->only(['destroy','update','create','store','arquivar']);
    }
    /**
     * Exibir uma lista dos produtos.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProdutosPorSubcategoria($id)
{
    $produtos = Produto::where('subCategoria_id', $id)->where('status','Valido')->get();

    return response()->json($produtos);
}

    public function index()
    {
        try{
      //  dd(session()->all());
        $categorias = Categoria::where('status','Valido')->get();
        $subcategorias=SubCategoria::where('status','Valido')->get();
        $produtos = Produto::with(['subcategoria'])->orderBy('created_at','DESC')->get();
        $armazens=Armazem::where('status','Valido')->get();

        return view("app.paginas.produtos.index", compact("produtos",'categorias','subcategorias','armazens'));

          } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function buscarNome(Request $request){
            $request->validate([
                "nome"=>"required|string",
            ]);
            $categorias = Categoria::where('status','Valido')->get();
            //$escalas = Escala::all();
            $produtos=Produto::where("nome","like","%".$request->nome."%")->paginate(5);
            return view("app.produtos.index", compact("produtos",'categorias', 'escalas'));
    }
    /**
     * Mostrar o formulário para criar um novo produto.
     *
     * @return \Illuminate\Http\Response
     */
    



    public function gerarSlug($para){
        // Gerar um slug único
   $slugBase = Str::slug($para);
   $slug = $slugBase;
   $contador = 1;
   while (Produto::where('slug', $slug)->exists()) {
       $slug = $slugBase . '-' . $contador;
       $contador++;
   }
   return $slug;
   }

   public function atualizarSlug($para,$produto){
       // Gerar um slug único
       if ($para !== $produto->nome) {
           $slugBase = Str::slug($para);
           $slug = $slugBase;
           $contador = 1;
           while (Produto::where('slug', $slug)->where('id', '!=', $produto->id)->exists()) {
               $slug = $slugBase . '-' . $contador;
               $contador++;
           }
           $produto->slug = $slug;
     }
  
  }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|max:255',
                'preco' => 'required|numeric|min:1',
                'limite_minimo' => 'nullable|integer|min:0',
                'imposto' => 'nullable|integer|min:0',
                'subCategoria_id' => 'required|exists:subcategorias,id',
                'armazem_id' => 'required|exists:armazens,id',
                'quant_uni_grosso' => 'required|integer|min:1',
            ]);
            $slug=$this->gerarSlug(  $request->nome);
            Produto::create([
                'nome' => $request->nome,
                'preco' => $request->preco,
                'limite_minimo' => $request->limite_minimo ?? 0,
                'imposto' => $request->imposto ?? 0,
                'codigo' => strtoupper(Str::random(10)), // Gera um código aleatório único
                'slug' => $slug,
                'subCategoria_id' => $request->subCategoria_id,
                'armazem_id' => $request->armazem_id,
                'quant_uni_grosso' => $request->quant_uni_grosso,
            ]);


            session()->put('success', 'Produto criado com sucesso!' );

    return redirect()->route('produtos.index');
    
 
        } catch (Exception $e) {
            Log::error("Erro ao criar produto: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Exibir os detalhes de um produto específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = Produto::with(['categoria', 'escala'])->findOrFail($id);
        return view("app.produtos.show", compact("produto"));
    }

    /**
     * Mostrar o formulário para editar um produto existente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Atualizar um produto existente no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nome' => 'required|max:255',
                'preco' => 'required|numeric|min:1',
                'limite_minimo' => 'nullable|integer|min:0',
                'imposto' => 'nullable|integer|min:0',
                'subCategoria_id' => 'required|exists:subcategorias,id',
                'armazem_id' => 'required|exists:armazens,id',
                'quant_uni_grosso' => 'required|integer|min:1',
                'status'=>'required|in:Valido,Arquivado',
            ]);
    
            $produto = Produto::findOrFail($id);
            $this->atualizarSlug($request->nome,$produto);
            $produto->update([
                'nome' => $request->nome,
                'preco' => $request->preco,
                'limite_minimo' => $request->limite_minimo ?? 0,
                'imposto' => $request->imposto ?? 0,
                'subCategoria_id' => $request->subCategoria_id,
                'armazem_id' => $request->armazem_id,
                'quant_uni_grosso' => $request->quant_uni_grosso,
                'status'=> $request->status,
            ]);

                   session()->put('success', 'Produto atualizado com sucesso! ' );

    return redirect()->route('produtos.index');
    
            return redirect()->route("produtos.index")->with("success", "Produto atualizado com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao atualizar produto: " . $e->getMessage());
          
              session()->put('error', 'Ocorreu um erro: ' . $e->getMessage());

    return redirect()->route('produtos.index');
          //  return redirect()->intended(route('produtos.index'))
    //->with("error", "Ocorreu um erro: " . $e->getMessage());

        }
    }

    /**
     * Remover um produto do banco de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function arquivar($id)
    {
        try {
            $produto = Produto::findOrFail($id);
            $produto->status="Arquivado";
            $produto->save();
             session()->put('success', 'Produto Arquivado com sucesso! ');

            return redirect()->route('produtos.index');
          
        } catch (Exception $e) {
            Log::error("Erro ao deletar produto: " . $e->getMessage());
            //return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
             
            session()->put('error', 'Ocorreu um erro: ' . $e->getMessage());

            return redirect()->route('produtos.index');
        }
    }
    public function destroy($id)
    {
        /*
        try {
            $produto = Produto::findOrFail($id);
            $produto->status="Arquivado";
            $produto->save();
            return redirect()->route("produtos.index")->with("success", "Produto deletado com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao deletar produto: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }*/
    }
}
