<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
    <ul>
    <li class="active">
        @role('caixa')
        <a href="/dashboard2"><img src="/empresa/assets/img/icons/dashboard.svg" alt="img"><span> Dashboard</span> </a>
        @endrole

        @hasanyrole('admin|gerente')
        <a href="/dashboard1"><img src="/empresa/assets/img/icons/dashboard.svg" alt="img"><span> Painel Controle</span> </a>
        @endhasanyrole
</li>
    <li class="submenu">
    <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/product.svg" alt="img"><span> Stock</span> <span class="menu-arrow"></span></a>
    <ul>
    <li><a href="{{route('produtos.index')}}">Produtos</a></li>
 
  
    </ul>
    </li>
    <li class="submenu">
        <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/sales1.svg" alt="img"><span>Vendas</span> <span class="menu-arrow"></span></a>
        <ul>
        <li><a href="{{route('vendas.create')}}">Adicionar  Vendas</a></li>

        @role('caixa')
             <li><a href="{{route('vendas.index')}}">Minhas Vendas</a></li>
        @endrole
       @hasanyrole('admin|gerente')
        <li><a href="{{route('vendas.lista')}}">Listar  Vendas</a></li>
        @endhasanyrole
         </ul>
        </li>

        <li class="submenu">
            <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/sales1.svg" alt="img"><span>Pedidos</span> <span class="menu-arrow"></span></a>
            <ul>
            <li><a href="{{route('pedidos.create')}}">Pedidos</a></li>
            </ul>
            </li>
 
    @hasanyrole('admin|gerente')
    <li class="submenu">
    <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/purchase1.svg" alt="img"><span>Entrada  Stock</span> <span class="menu-arrow"></span></a>
    <ul>
    <li><a href="{{route('compras.create')}}">Adicionar Entrada</a></li>
    <li><a href="{{route('compras.index')}}">Listar Entradas</a></li>
    </ul>
    </li>
    @endhasanyrole
    @hasanyrole('admin|gerente')
    <li class="submenu">
        <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/sales1.svg" alt="img"><span>Movimentos</span> <span class="menu-arrow"></span></a>
        <ul>
        <li><a href="{{route('movimentacoes.index')}}">Lista</a></li>

        </ul>
        </li>
     
    <li class="submenu">
    <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/purchase1.svg" alt="img"><span>  Consultas</span> <span class="menu-arrow"></span></a>
    <ul>
    <li><a href="{{route('consultas.vendas')}}">Vendas</a></li>
    <li><a href="{{route('consultas.compras')}}">Entradas Stock</a></li>
        <li><a href="{{route('turnos.geral')}}">Turnos</a></li>
        <li><a href="{{route('inventarios.index')}}">Inventarios</a></li>
    </ul>
    </li>

        <li class="submenu">
    <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/purchase1.svg" alt="img"><span>  Relatórios</span> <span class="menu-arrow"></span></a>
    <ul>
    <li><a href="{{route('relatorio.lucros')}}">Lucros</a></li>
    <li><a href="{{route('relatorio.perdas')}}">Perdas</a></li>
    </ul>
    </li>

    
    @endhasanyrole
  
    @hasanyrole('caixa')
  
      <li class="submenu">
    <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/users1.svg" alt="img"><span>Consultas </span> <span class="menu-arrow"></span></a>
    <ul>
    <li><a href="{{route('minhas.vendas')}}">Vendas</a></li>
        <li><a href="{{route('turnos.meus')}}">Turnos</a></li>
    </ul>
    </li>
    @endhasanyrole
    
  
    
    @hasanyrole('admin')
    <li class="submenu">
    <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/users1.svg" alt="img"><span> Usuarios</span> <span class="menu-arrow"></span></a>
    <ul>
    <li><a href="{{route('usuario.todos')}}">Lista</a></li>

    </ul>
    </li>
    @endhasanyrole
    @hasanyrole('admin|gerente')
    <li class="submenu">
    <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/purchase1.svg" alt="img"><span>Perdas Stock</span> <span class="menu-arrow"></span></a>
    <ul>
         <li><a href="{{route('perdas.create')}}">Adicionar Perdas</a></li>
         <li><a href="{{route('perdas.index')}}">Perdas</a></li>
  
    </ul>
    </li>
    @endhasanyrole
    @hasanyrole('admin|gerente')
    <li class="submenu">
    <a href="javascript:void(0);"><img src="/empresa/assets/img/icons/settings.svg" alt="img"><span> Configurações</span> <span class="menu-arrow"></span></a>
    <ul>
        @hasanyrole('admin')
        <li><a href="{{route('empresas.index')}}">Empresa</a></li>
        @endhasanyrole
        <li><a href="{{route('fornecedores.index')}}">Fornecedores</a></li>
        @hasanyrole('admin|gerente')
        <li><a href="{{route('categorias.index')}}">Categorias</a></li>
        <li><a href="{{route('armazens.index')}}">Armazens</a></li>
        <li><a href="{{route('mesas.index')}}">Mesas</a></li>
        @endhasanyrole
    </ul>
    </li>
    @endhasanyrole
    </ul>
    </div>
    </div>
    </div>