<?php

use App\Http\Controllers\ArmazemController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ConsultasComprasController;
use App\Http\Controllers\ConsultasVendasController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\GeralController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\MinhasVendasController;
use App\Http\Controllers\MovimentacaoEstoqueController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PerdaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelatorioLucroController;
use App\Http\Controllers\RelatorioPerdaController;
use App\Http\Controllers\SubCategoriaController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VendaController;
use App\Models\SubCategoria;
use Illuminate\Support\Facades\Route;



Route::get('/',[HomeController::class,'home'])->name('home');
 //usuario
 

Route::middleware('auth')->group(function () {
 

//erro critico
Route::get('erro/critico',[GeralController::class,'erro'])->name('erro.critico');


    //Admin And Gerente
    Route::middleware('roles:admin,gerente')->group(function(){

      //inventario de estoque
      Route::get('inventarios/store',[InventarioController::class,'store'])->name('inventarios.store');
       Route::get('inventarios/periodo',[InventarioController::class,'periodo'])->name('inventarios.periodo');
        Route::get('inventarios/',[InventarioController::class,'index'])->name('inventarios.index');
         Route::get('inventarios/documento/{id}',[InventarioController::class,'documento'])->name('inventarios.documento');

      //turnos geral 
     Route::get('/turno/geral/documeto/{turno}',[TurnoController::class,'documento2'])->name('turno.documento2');
    Route::get('/turnos/geral',[TurnoController::class,'turnoGeral'])->name('turnos.geral');
        Route::get('/turnos/geral/periodo',[TurnoController::class,'geral_turno_periodo'])->name('turnos.geral.periodo');

//cancelamento
      //compras
        Route::put('/compras/cancelar/{id}',[CompraController::class,'cancelar'])->name('compras.cancelar');
      //vendas
        Route::put('/vendas/cancelar/{id}',[VendaController::class,'cancelar'])->name('vendas.cancelar');
      //perda
        Route::put('/perdas/cancelar/{id}',[PerdaController::class,'cancelar'])->name('perdas.cancelar');

//arquivamento
      //produtos
        Route::get('/produtos/arquivar/{id}', [ProdutoController::class, 'arquivar'])->name('produtos.arquivar');
      //fornecedores
        Route::get('/fornecedores/arquivar/{id}', [FornecedorController::class, 'arquivar'])->name('fornecedores.arquivar');
      //categorias
        Route::get('/categorias/arquivar/{id}', [CategoriaController::class, 'arquivar'])->name('categorias.arquivar');
      //armazens
        Route::get('/armazens/arquivar/{id}', [ArmazemController::class, 'arquivar'])->name('armazens.arquivar');
      //mesas
        Route::get('/mesas/arquivar/{id}', [MesaController::class, 'arquivar'])->name('mesas.arquivar');
      //subcategorias
        Route::get('/subcategorias/arquivar/{id}', [SubCategoriaController::class, 'arquivar'])->name('subcategorias.arquivar');

//usuarios
      Route::resource("usuarios",UsuarioController::class)->middleware('roles:admin');
      Route::get("/usuario/todos",[UsuarioController::class,"todos"])->middleware('roles:admin')->name("usuario.todos");
      Route::put("/usuario/papel/alterar/{id}",[UsuarioController::class,"alterar_papel"])->middleware('roles:admin')->name("usuario.alterar.papel");
//vendas
      Route::get("/vendas/create2",[VendaController::class,"create2"])->middleware('roles:admin')->name("vendas.create2");
      Route::get("/vendas/lista",[VendaController::class,"lista"])->name("vendas.lista");

//compras
      Route::get("compras/fatura/{id}",[CompraController::class,"fatura"])->name("compras.fatura");

//pedidos
      Route::get('/pedidos/todos/sem/mesa', [PedidoController::class, 'semmesa2'])->name('pedidos.semmesa2');
      Route::get('/pedidos/todos/mesa/{id}', [PedidoController::class, 'listarPorMesa2'])->name('pedidos.mesa2');

//resources
      Route::resource("subcategorias",SubCategoriaController::class);
      Route::resource("armazens",ArmazemController::class);
      Route::resource("categorias",CategoriaController::class);
      Route::resource("fornecedores",FornecedorController::class);
      Route::resource("categorias",CategoriaController::class);
      Route::resource("mesas",MesaController::class);
      Route::resource("perdas",PerdaController::class);
      Route::resource("movimentacoes",MovimentacaoEstoqueController::class);
      Route::resource("compras",CompraController::class);

//consultas relatorio lucro
 
      Route::get('/relatorio/lucros/',[RelatorioLucroController::class,'index'])->name('relatorio.lucros');
      Route::get('/relatorio/lucros/data',[RelatorioLucroController::class,'lucros_data'])->name('relatorio.lucros.data');
     Route::get('/relatorio/lucros/periodo',[RelatorioLucroController::class,'lucros_periodo'])->name('relatorio.lucros.periodo');
      Route::get('/relatorio/lucros/mes',[RelatorioLucroController::class,'lucros_mes'])->name('relatorio.lucros.mes');
    Route::get('/relatorio/lucros/ano',[RelatorioLucroController::class,'lucros_ano'])->name('relatorio.lucros.ano');
    

    //consultas relatorio perda
 
      Route::get('/relatorio/perdas/',[RelatorioPerdaController::class,'index'])->name('relatorio.perdas');
      Route::get('/relatorio/perdas/data',[RelatorioPerdaController::class,'perdas_data'])->name('relatorio.perdas.data');
     Route::get('/relatorio/perdas/periodo',[RelatorioPerdaController::class,'perdas_periodo'])->name('relatorio.perdas.periodo');
      Route::get('/relatorio/perdas/mes',[RelatorioPerdaController::class,'perdas_mes'])->name('relatorio.perdas.mes');
    Route::get('/relatorio/perdas/ano',[RelatorioPerdaController::class,'perdas_ano'])->name('relatorio.perdas.ano');
    

//consultas vendas
 
      Route::get('/consultas/vendas/',[ConsultasVendasController::class,'index'])->name('consultas.vendas');
      Route::get('/consultas/vendas/data',[ConsultasVendasController::class,'vendas_data'])->name('consultas.vendas.data');
      Route::get('/consultas/vendas/periodo',[ConsultasVendasController::class,'vendas_periodo'])->name('consultas.vendas.periodo');
      Route::get('/consultas/vendas/mes',[ConsultasVendasController::class,'vendas_mes'])->name('consultas.vendas.mes');
      Route::get('/consultas/vendas/ano',[ConsultasVendasController::class,'vendas_ano'])->name('consultas.vendas.ano');
      Route::get('/consultas/vendas/fatura',[ConsultasVendasController::class,'vendas_fatura'])->name('consultas.vendas.fatura');

//consultas compras;
      Route::get('/consultas/compras/',[ConsultasComprasController::class,'index'])->name('consultas.compras');
      Route::get('/consultas/compras/data',[ConsultasComprasController::class,'compras_data'])->name('consultas.compras.data');
      Route::get('/consultas/compras/periodo',[ConsultasComprasController::class,'compras_periodo'])->name('consultas.compras.periodo');
      Route::get('/consultas/compras/mes',[ConsultasComprasController::class,'compras_mes'])->name('consultas.compras.mes');
      Route::get('/consultas/compras/ano',[ConsultasComprasController::class,'compras_ano'])->name('consultas.compras.ano');
      Route::get('/consultas/compras/fatura',[ConsultasComprasController::class,'compras_fatura'])->name('consultas.compras.fatura');
 
 //consultas
      Route::get('/consultas/movimentos/periodo',[MovimentacaoEstoqueController::class,'movimentos_periodo'])->name('consultas.movimentos.periodo');
//dashboard      
      Route::get('/dashboard1',[UsuarioController::class,'dashboard1'])->middleware(['auth', 'verified'])->name('dashboard1');

//notificacoes
      Route::patch('/notificacoes/{id}/ler', [UsuarioController::class, 'marcarComoLida'])->name('notificacoes.marcarLida');
      Route::patch('/notificacoes/{id}/nao-lida', [UsuarioController::class, 'marcarComoNaoLida'])->name('notificacoes.marcarNaoLida');
      Route::get('/notificacoes', [UsuarioController::class, 'notificacoes'])->name('notificacoes');


//empresa
Route::resource("empresas",EmpresaController::class)->middleware('roles:admin');
Route::post("empresa/telefone/add",[EmpresaController::class,"storeTelefone"])->middleware('roles:admin')->name("empresa.addTelefone");
Route::put("empresa/telefone/atualizar/{id}",[EmpresaController::class,"updateTelefone"])->middleware('roles:admin')->name("empresa.updateTelefone");
Route::delete("empresa/telefone/deletar/{id}",[EmpresaController::class,"destroyTelefone"])->middleware('roles:admin')->name("empresa.destroyTelefone");
Route::get("empresa/telefones",[EmpresaController::class,"telefones"])->middleware('roles:admin')->name("empresa.telefones");

});
 
 
 
 
 
 
 
 
 
 
 
 Route::middleware('roles:caixa')->group(function (){
  
//turnos meus
Route::get('/meus/turnos',[TurnoController::class,'index'])->name('turnos.meus');
Route::get('/meus/turnos/periodo',[TurnoController::class,'meu_turno_periodo'])->name('meus.turnos.periodo');


// Minhas consultas vendas
 
      Route::get('/minhas/vendas/',[MinhasVendasController::class,'index'])->name('minhas.vendas');
      Route::get('/minhas/vendas/data',[MinhasVendasController::class,'vendas_data'])->name('minhas.vendas.data');
      Route::get('/minhas/vendas/periodo',[MinhasVendasController::class,'vendas_periodo'])->name('minhas.vendas.periodo');
      Route::get('/minhas/vendas/mes',[MinhasVendasController::class,'vendas_mes'])->name('minhas.vendas.mes');
      Route::get('/minhas/vendas/ano',[MinhasVendasController::class,'vendas_ano'])->name('minhas.vendas.ano');
      Route::get('/minhas/vendas/fatura',[MinhasVendasController::class,'vendas_fatura'])->name('minhas.vendas.fatura');

 });
   
 //produtos 
      Route::resource("produtos",ProdutoController::class);

 //dashboard  
   Route::get('/dashboard2',[UsuarioController::class,'dashboard2'])->middleware(['auth', 'verified'])->name('dashboard2');

 //categoria  
   Route::get('categoria/subcategoria/{slug}',[SubCategoriaController::class,'index2'])->name("subcategorias.index2");
 
 //subcategoria    
      Route::resource("subcategorias",SubCategoriaController::class);
      Route::get('/produtos/subcategoria/{id}', [ProdutoController::class, 'getProdutosPorSubcategoria']);
      Route::get('/subcategorias/buscar/{categoriaId}',[SubCategoriaController::class,'getSubCategorias'])->name("getSubCategorias");
  
//pedidos
    Route::get('/pedidos/cancelar/{id}', [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');
    Route::get('/pedidos/sem/mesa', [PedidoController::class, 'semmesa'])->name('pedidos.semmesa');
    Route::get('/pedidos/finalizar', [PedidoController::class, 'finalizar'])->name('pedidos.finalizar');
    Route::get('/pedidos/mesa/{id}', [PedidoController::class, 'listarPorMesa'])->name('pedidos.mesa');
    Route::get('/pedidos/mesa/create/{mesa_id}', [PedidoController::class, 'addPedido'])->name('pedidos.add');
    Route::get('/pedidos/semmesa/create', [PedidoController::class, 'addPedido2'])->name('pedidos.add2');
    Route::resource("pedidos",PedidoController::class);
    Route::put('/pedidos/atualizar/{id}', [PedidoController::class, 'atualizar'])->name('pedidos.atualizar');

//vendas
      Route::resource("vendas",VendaController::class);
      Route::get("venda/fatura/{id}",[VendaController::class,"fatura"])->name("vendas.fatura");
   

  
   
     //turnos
     Route::get('/turno/documeto/{turno}',[TurnoController::class,'documento'])->name('turno.documento');
     Route::get('/turno/abrir',[TurnoController::class,'abrirTurno'])->name('turno.abrir');
Route::get('/turno/fechar',[TurnoController::class,'fecharTurno'])->name('turno.fechar');

 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
