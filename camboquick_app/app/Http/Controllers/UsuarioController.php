<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Venda;
use App\Models\Fornecedor;
use App\Models\Armazem;
use Exception;

class UsuarioController extends Controller
{
    
    public function __construct(){
        $this->middleware('roles:admin')->only(['destroy','edit','update']);
    }
    public function adm(){
        return view("app.dashboard");
    }
    public function dashboard1(){
        try{
           // dd(Carbon::now()->startOfWeek(Carbon::SUNDAY),Carbon::now()->endOfWeek(Carbon::SATURDAY));
            $totalHoje=Venda::whereDate('created_at',Carbon::today())->where('status','Valida')->sum('total');
            $totalSemana=Venda::whereBetween('created_at',[Carbon::now()->startOfWeek(Carbon::SUNDAY),Carbon::now()->endOfWeek(Carbon::SATURDAY)])->where('status','Valida')->sum('total');
            $totalMes=Venda::whereMonth('created_at',
            Carbon::now()->month)
            ->whereYear('created_at',Carbon::now()->year)
            ->where('status','Valida')
            ->sum('total');
    
            $totalAno=Venda::whereYear('created_at',Carbon::now()

            ->year)
            ->where('status','Valida')
            ->sum('total');
    
    
            $vendaHoje=Venda::whereDate('created_at',Carbon::today())
            ->where('status','Valida')
            ->count();
            $vendaSemana=Venda::whereBetween('created_at',[
                Carbon::now()->startOfWeek(Carbon::SUNDAY),Carbon::now()->endOfWeek(Carbon::SATURDAY)
            ])
            ->where('status','Valida')
            ->count();
    
            $vendaMes=Venda::whereMonth('created_at',Carbon::now()->month)
            ->whereYear('created_at',Carbon::now()->year)
            ->where('status','Valida')
            ->count();
    
            $vendaAno=Venda::whereYear('created_at',Carbon::now()->year)
            ->where('status','Valida')
            ->count();
    
            $gestores=User::role('gerente')->count();
            $caixas=User::role('caixa')->count();
             $fornecedores=Fornecedor::count();
             $armazens=Armazem::count();

     $user = auth()->user();
    $notificacoes = $user->notifications;
    $notificacoesNaoLidas = $user->unreadNotifications;
    $totalNaoLidas = $notificacoesNaoLidas->count();
            return view("app.paginas.dashboard.dashboard1",compact(
                'totalHoje','totalSemana','totalMes','totalAno',
                'vendaHoje','vendaSemana','vendaMes','vendaAno',
                'gestores','caixas','fornecedores','armazens','totalNaoLidas'
    
            ));
        }catch(Exception $e){
            Log::error("Ocorreu um erro ".$e->getMessage());
            return redirect('/')->with("error","Ocorreu um erro ".$e->getMessage());
        }
       
        
    }
    public function marcarComoLida($id)
{
    try {
    $notificacao = auth()->user()->notifications()->findOrFail($id);
    $notificacao->markAsRead();
    return back()->with('success', 'Notificação marcada como lida.');
     } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
}

public function marcarComoNaoLida($id)
{
    try {
    $notificacao = auth()->user()->notifications()->findOrFail($id);
    $notificacao->markAsUnread();
    return back()->with('success', 'Notificação marcada como não lida.');
     } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
}


   public function notificacoes()
{
    try {
    $user = auth()->user();
    $notificacoes = $user->notifications()->paginate(10);
    $notificacoesNaoLidas = $user->unreadNotifications;
    $totalNaoLidas = $notificacoesNaoLidas->count();

    return view('app.paginas.notificacoes.index', compact('notificacoes', 'totalNaoLidas'));
     } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
}


    public function dashboard2(){
        return view("app.paginas.dashboard.dashboard2");
        
    }

    public function loja2(){
        try{
        return view("app.dashboard2");
    }catch(Exception $e){
        Log::error("Ocorreu um erro ".$e->getMessage());
        return redirect('/')->with("error","Ocorreu um erro ".$e->getMessage());
    }
    }
    public function loja(){
        try{
        $totalHoje=Venda::whereDate('created_at',Carbon::today())->sum('total');
        $totalSemana=Venda::whereBetween('created_at',[Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])->sum('total');
        $totalMes=Venda::whereMonth('created_at',
        Carbon::now()->month)
        ->whereYear('created_at',Carbon::now()->year)
        ->sum('total');

        $totalAno=Venda::whereYear('created_at',Carbon::now()
        ->year)->sum('total');


        $vendaHoje=Venda::whereDate('created_at',Carbon::today())
        ->count();
        $vendaSemana=Venda::whereBetween('created_at',[
            Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()
        ])->count();

        $vendaMes=Venda::whereMonth('created_at',Carbon::now()->month)
        ->whereYear('created_at',Carbon::now()->year)->count();

        $vendaAno=Venda::whereYear('created_at',Carbon::now()->year)->count();

        $gestores=User::role('gerente')->count();
        $caixas=User::role('caixa')->count();
         $fornecedores=Fornecedor::count();
         $armazens=Armazem::count();

        return view("app.paginas.dashboard",compact(
            'totalHoje','totalSemana','totalMes','totalAno',
            'vendaHoje','vendaSemana','vendaMes','vendaAno',
            'gestores','caixas','fornecedores','armazens'

        ));
    }catch(Exception $e){
        Log::error("Ocorreu um erro ".$e->getMessage());
        return redirect('/')->with("error","Ocorreu um erro ".$e->getMessage());
    }
    }
    public function index()
    {
        //
        try{
            $this->authorize("auth");
            $this->authorize("adm");
            $usuarios = User::paginate(8);
            return view("app.paginas.usuarios.todos",compact("usuarios"));
        }catch(Exception $e){
            Log::error("Ocorreu um erro ".$e->getMessage());
            return redirect()->back()->with("error","Ocorreu um erro ".$e->getMessage());
        }
    }
    public function todos(){
        try{
          
            $usuarios = User::paginate(10);
            return view("app.paginas.usuarios.todos",compact("usuarios"));
        }catch(Exception $e){
            Log::error("Ocorreu um erro ".$e->getMessage());
            return redirect()->back()->with("error","Ocorreu um erro ".$e->getMessage());
        }
   
    }
    public function alterar_papel(Request $request,$id){
        try{
            $request->validate([
                'papel'=>'required',
            ]);
            $usuario = User::find($id);
            if(!$usuario){
                return redirect()->route('usuario.todos')->with('error','Usuario Não existe!');
            }
            $usuario->syncRoles($request->papel);
            return redirect()->route('usuario.todos')->with('success','Papel alterado com sucesso!');
           
        }catch(Exception $e){
            Log::error("Ocorreu um erro ".$e->getMessage());
            return redirect()->route('usuario.todos')->with("error","Ocorreu um erro ".$e->getMessage());
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
           
        /*  try{
           $user=User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with("success","Usuario deletado Com Sucesso");
   
        }catch(\Exception $e){
        Log::error("Ocorreu um erro ".$e->getMessage());
        return redirect()->back()->with("error","Ocorreu um erro ".$e->getMessage());
        }
        */
         
    }
}
