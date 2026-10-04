@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Lista de Movimentações De Estoque</h4>
    <h6>Vizualizar e pesquisar os armazens</h6>
    </div>
   
    </div>



    <div class="card">
        <div class="card-body">
        <div class="table-top">
        <div class="search-set">
        <div class="search-path">
        <a class="btn btn-filter" id="filter_search">
        <img src="/empresa/assets/img/icons/filter.svg" alt="img">
        <span><img src="/empresa/assets/img/icons/closes.svg" alt="img"></span>
        </a>
        </div>
        <div class="search-input">
        <a class="btn btn-searchset"><img src="/empresa/assets/img/icons/search-white.svg" alt="img"></a>
        </div>
        </div>
        <div class="wordset">
        <ul>
        <li>
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="/empresa/assets/img/icons/pdf.svg" alt="img"></a>
        </li>
        <li>
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="/empresa/assets/img/icons/excel.svg" alt="img"></a>
        </li>
        <li>
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="/empresa/assets/img/icons/printer.svg" alt="img"></a>
        </li>
        </ul>
        </div>
        </div>
        
        <div class="card" id="filter_inputs">
        <div class="card-body pb-0">
        <div class="row">
        <div class="col-lg-2 col-sm-6 col-12">
        <div class="form-group">
        <select class="select">
        <option>Choose Category</option>
        <option>Computers</option>
        </select>
        </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-12">
        <div class="form-group">
        <select class="select">
        <option>Choose Sub Category</option>
        <option>Fruits</option>
        </select>
        </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-12">
        <div class="form-group">
        <select class="select">
        <option>Choose Sub Brand</option>
        <option>Iphone</option>
        </select>
        </div>
        </div>
        <div class="col-lg-1 col-sm-6 col-12 ms-auto">
        <div class="form-group">
        <a class="btn btn-filters ms-auto"><img src="/empresa/assets/img/icons/search-whites.svg" alt="img"></a>
        </div>
        </div>
        </div>
        </div>
        </div>
        

        <div class="mb-3">
            <label for="tipoFiltro" class="form-label">Selecione o Tipo de Filtro:</label>
            <select id="tipoFiltro" class="form-control" onchange="mostrarFiltro()">
                <option value="4">Secione um filtro</option>
                <option value="1">Filtro Por Periodo</option>
          
            </select>
        </div>

        <!-- Filtro Básico -->
        <form method="GET" action="{{route('consultas.movimentos.periodo')}}" class="mb-4 d-none" id="filtro1">
            <div class="row">
                <div class="col-md-3">
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select class="form-control" id="categoria" name="categoria_id" required>
                             <option selected disabled>Seleciona a Categoria</option>
                        @foreach($categorias as $categoria)

                            <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="escala_id" class="form-label">SubCategoria</label>
                    <select class="form-control" id="subcategoria" name="subCategoria_id" required>
                          <option selected disabled>Seleciona a SubCategoria</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="escala_id" class="form-label">Produtos</label>
                    <select class="form-control" id="produto" name="produto_id" required>
                          <option selected disabled>Seleciona um produto</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Data Inicio</label>
                    <input type="date" name="data_inicio" id="data" class="form-control" value="{{ request('data_inicio') }}" required>
                </div>
                <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Data Fim</label>
                    <input type="date" name="data_fim" id="data" class="form-control" value="{{ request('data_fim') }}" required>
                </div>
                <div class="col-md-3">
                    <label for="tipoFiltro" class="form-label">Selecione o Tipo de Movimento:</label>
                    <select  name="tipo" class="form-control">
                        <option value="Entrada">Entrada</option>
                      
                        <option value="Saida">Saida</option>
                        <option value="Perda">Perda</option>
                        <option value="Saida Cancelada">Saida Cancelada</option>
                        <option value="Entrada Cancelada">Entrada Cancelada</option>
                        <option value="Perda Cancelada">Perda Cancelada</option>

                  
                    </select>
                </div>
               
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>

        @if(isset($total_mov))
        <p>Pesquisa Por :  {{$pesquisa}}</p>

                <p> <strong>Total </strong>  :
         <span class="fs-2 text-success">{{ number_format( $total_mov,'2',',','.')}}Kz </span>
    &nbsp;&nbsp;  
         <strong>Número de Registros</strong>   : <span class="fs-2 text-info">{{$n_mov}}</span> </p>
        
               @endif
        <div class="table-responsive">
        <table class="table  datanew">
        <thead>
        <tr>
        <th>
        <label class="checkboxs">
        <input type="checkbox" id="select-all">
        <span class="checkmarks"></span>
        </label>
        </th>
        <th>Nome Prod</th>
        <th>Cat</th>
        <th>Sub</th>
        <th>Qnt</th>
        <th>Custo</th>
        <th>Data</th>
        <th>Tipo</th>
        <th>Usuario</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($movimentacoes as $movimento)
        <tr>
            
   <td>
        <label class="checkboxs">
        <input type="checkbox">
        <span class="checkmarks"></span>
        </label>
        </td>
     
     <td class="productimgname">
        <a href="javascript:void(0);" class="product-img">
        <img src="/empresa/assets/img/product/noimage.png" alt="product">
        </a>
        <a href="javascript:void(0);">{{$movimento ->produto->nome }}</a>
        </td>
        
        <td>{{ $movimento ->produto->subcategoria->categoria->categoria }}</td>
        <td>{{ $movimento ->produto->subcategoria->sub }}</td>
        <td>{{ $movimento->quantidade }}</td>
        <td>{{ number_format($movimento->preco_compra, 2, ',', '.') }}</td>
        <td>{{ $movimento->created_at->format('d/m/Y H:i') }}</td>
        @if($movimento->tipo=='Entrada')
        <td class="text-success">{{ $movimento->tipo }}</td>
        @elseif($movimento->tipo=='Saida')
        <td class="text-danger">{{ $movimento->tipo }}</td>
        @elseif($movimento->tipo=='Saida Cancelada' ||$movimento->tipo=='Entrada Cancelada' || $movimento->tipo=='Perda Cancelada')
        <td class="text-warning">{{ $movimento->tipo }}</td>
        @else
        <td class="text-danger">{{ $movimento->tipo }}</td>
@endif
        <td>{{ $movimento->usuario->name }}</td>
       
        <td>
            
        <a class="me-3"data-bs-toggle="modal" data-bs-target="#modalVisualizar{{ $movimento->id }}" title="Visualizar" >
        <img src="/empresa/assets/img/icons/eye.svg" alt="img">
        </a>
        


<!-- Modal para visualizar os detalhes do produto -->
<div class="modal fade" id="modalVisualizar{{ $movimento->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Movimento Nº {{ $movimento->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <tr>
                        <th>Data Movimento</th>
                        <td>{{ $movimento->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <td>{{ $movimento->tipo }}</td>
                    </tr>
                    <tr>
                        <th>Nome Prod</th>
                        <td>{{ $movimento->produto->nome }}</td>
                    </tr>
                    <tr>
                        <th>Categoria</th>
                        <td>{{ $movimento->produto->subcategoria->categoria->categoria }}</td>
                    </tr>
                                       <tr>
                        <th>SubCategoria</th>
                        <td>{{ $movimento->produto->subcategoria->sub }}</td>
                    </tr>
               
                    <tr>
                        <th>Quant Prod</th>
                        <td>{{ $movimento->quantidade }}</td>
                    </tr>
                    <tr>
                        <th>Custo</th>
                        <td>R$ {{ number_format($movimento->preco_compra, 2, ',', '.') }}</td>
                    </tr>
                        <tr>
                        <th>Usuario</th>
                        <td>{{ $movimento->usuario->name }}</td>
                    </tr>
                 

                    <!-- Adicione mais campos conforme necessário -->
                </table>
            </div>
            <div class="modal-footer">
                 
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

        

        

        
                     
        </td>
    </tr>
    @endforeach
           </tbody>
        </table>
        </div>
        </div>
        </div>
       

        
 
    <script>
        function mostrarFiltro() {
            let tipo = document.getElementById("tipoFiltro").value;
    
            // Oculta todos os filtros existentes de forma segura
            for (let i = 1; i <= 6; i++) {
                let filtro = document.getElementById("filtro" + i);
                if (filtro) {
                    filtro.classList.add("d-none");
                }
            }
    
            // Mostra apenas o filtro selecionado, se existir
            let filtroSelecionado = document.getElementById("filtro" + tipo);
            if (filtroSelecionado) {
                filtroSelecionado.classList.remove("d-none");
            }
        }
    </script>
    <script>

        document.getElementById('categoria').addEventListener('change', function() {
            const categoriaId = this.value;
        
            fetch(`/subcategorias/buscar/${categoriaId}`)
                .then(response => response.json())
                .then(data => {
                    let subcategoriaSelect = document.getElementById('subcategoria');
                    subcategoriaSelect.innerHTML = '<option selected>Seleciona a subcategoria</option>';
                    data.forEach(subcategoria => {
                        let option = document.createElement('option');
                        option.value = subcategoria.id;
                        option.text = subcategoria.sub;
                        subcategoriaSelect.appendChild(option);
                    });
                });
        });
        </script>
        
        <script>
            document.getElementById('subcategoria').addEventListener('change', function () {
                let subcategoriaId = this.value;
         
        
                fetch(`/produtos/subcategoria/${subcategoriaId}`)
                    .then(response => response.json())
                    .then(data => {
                        let produtoSelect = document.getElementById('produto');
                        produtoSelect.innerHTML = '<option selected>Seleciona um produto</option>';
                    data.forEach(produto => {
                        let option = document.createElement('option');
                        option.value = produto.id;
                        option.text = produto.nome;
                        produtoSelect.appendChild(option);
                    });
                    })
                   
            });
        </script>
@endsection